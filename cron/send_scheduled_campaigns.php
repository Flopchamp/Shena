<?php
/**
 * Cron Job: Send Scheduled Bulk SMS Campaigns
 * Run this script every 5-10 minutes via cron
 * 
 * Usage: php cron/send_scheduled_campaigns.php
 * Cron: */10 * * * * cd /path/to/Shena && php cron/send_scheduled_campaigns.php >> storage/logs/cron-campaigns.log 2>&1
 */

define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/app/core/Database.php';
require_once ROOT_PATH . '/app/services/BulkSmsService.php';

$db = Database::getInstance()->getConnection();
$bulkSmsService = new BulkSmsService();

echo "[" . date('Y-m-d H:i:s') . "] Starting scheduled campaigns check...\n";

try {
    // Get pending campaigns that are due to be sent
    $stmt = $db->prepare("
        SELECT * FROM scheduled_campaigns 
        WHERE status = 'pending' 
        AND scheduled_at <= NOW()
        ORDER BY scheduled_at ASC
        LIMIT 10
    ");
    $stmt->execute();
    $campaigns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($campaigns)) {
        echo "No pending campaigns to send.\n";
        exit(0);
    }
    
    echo "Found " . count($campaigns) . " campaigns to process.\n\n";
    
    foreach ($campaigns as $campaign) {
        echo "Processing campaign #{$campaign['id']}: {$campaign['campaign_name']}\n";
        
        // Update status to processing
        $updateStmt = $db->prepare("
            UPDATE scheduled_campaigns 
            SET status = 'processing', executed_at = NOW() 
            WHERE id = ?
        ");
        $updateStmt->execute([$campaign['id']]);
        
        try {
            // Get recipients based on filter
            $recipients = getRecipients($db, $campaign['recipient_type'], $campaign['recipient_filter']);
            
            if (empty($recipients)) {
                throw new Exception("No recipients found for this campaign");
            }
            
            // Update total recipients
            $updateStmt = $db->prepare("
                UPDATE scheduled_campaigns 
                SET total_recipients = ? 
                WHERE id = ?
            ");
            $updateStmt->execute([count($recipients), $campaign['id']]);
            
            echo "  - Sending to " . count($recipients) . " recipients...\n";
            
            $sentCount = 0;
            $failedCount = 0;
            
            // Send messages
            foreach ($recipients as $recipient) {
                try {
                    $bulkSmsService->sendSingleMessage($recipient['phone'], $campaign['message']);
                    $sentCount++;
                } catch (Exception $e) {
                    $failedCount++;
                    error_log("Failed to send SMS to {$recipient['phone']}: " . $e->getMessage());
                }
                
                // Small delay to avoid rate limiting
                usleep(100000); // 0.1 seconds
            }
            
            // Update campaign status
            $updateStmt = $db->prepare("
                UPDATE scheduled_campaigns 
                SET status = 'completed',
                    sent_count = ?,
                    failed_count = ?,
                    completed_at = NOW()
                WHERE id = ?
            ");
            $updateStmt->execute([$sentCount, $failedCount, $campaign['id']]);
            
            echo "  ✓ Campaign completed: {$sentCount} sent, {$failedCount} failed\n\n";
            
            // Notify admin via email
            notifyAdmin($campaign, $sentCount, $failedCount);
            
        } catch (Exception $e) {
            // Mark campaign as failed
            $updateStmt = $db->prepare("
                UPDATE scheduled_campaigns 
                SET status = 'failed',
                    error_message = ?,
                    completed_at = NOW()
                WHERE id = ?
            ");
            $updateStmt->execute([$e->getMessage(), $campaign['id']]);
            
            echo "  ✗ Campaign failed: " . $e->getMessage() . "\n\n";
        }
    }
    
    echo "[" . date('Y-m-d H:i:s') . "] Scheduled campaigns processing completed.\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}

/**
 * Get recipients based on filter criteria
 */
function getRecipients($db, $recipientType, $recipientFilter)
{
    $filter = json_decode($recipientFilter, true);
    
    switch ($recipientType) {
        case 'all':
            $stmt = $db->query("
                SELECT u.phone, u.first_name, u.last_name, m.member_number
                FROM members m
                JOIN users u ON m.user_id = u.id
                WHERE m.status != 'suspended'
            ");
            break;
            
        case 'active':
            $stmt = $db->query("
                SELECT u.phone, u.first_name, u.last_name, m.member_number
                FROM members m
                JOIN users u ON m.user_id = u.id
                WHERE m.status = 'active'
            ");
            break;
            
        case 'inactive':
            $stmt = $db->query("
                SELECT u.phone, u.first_name, u.last_name, m.member_number
                FROM members m
                JOIN users u ON m.user_id = u.id
                WHERE m.status = 'inactive'
            ");
            break;
            
        case 'by_package':
            $package = $filter['package'] ?? 'individual';
            $stmt = $db->prepare("
                SELECT u.phone, u.first_name, u.last_name, m.member_number
                FROM members m
                JOIN users u ON m.user_id = u.id
                WHERE m.package = ? AND m.status != 'suspended'
            ");
            $stmt->execute([$package]);
            break;
            
        case 'custom':
            // Custom SQL query from filter
            $memberIds = $filter['member_ids'] ?? [];
            if (empty($memberIds)) {
                return [];
            }
            $placeholders = implode(',', array_fill(0, count($memberIds), '?'));
            $stmt = $db->prepare("
                SELECT u.phone, u.first_name, u.last_name, m.member_number
                FROM members m
                JOIN users u ON m.user_id = u.id
                WHERE m.id IN ($placeholders)
            ");
            $stmt->execute($memberIds);
            break;
            
        default:
            throw new Exception("Invalid recipient type: $recipientType");
    }
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Notify admin about campaign completion
 */
function notifyAdmin($campaign, $sentCount, $failedCount)
{
    try {
        require_once ROOT_PATH . '/app/services/EmailService.php';
        $emailService = new EmailService();
        
        // Get admin email
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("
            SELECT email FROM users 
            WHERE role IN ('super_admin', 'manager') 
            AND status = 'active' 
            LIMIT 1
        ");
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$admin) return;
        
        $subject = "Scheduled Campaign Completed: {$campaign['campaign_name']}";
        $message = "
            <h2>Scheduled Campaign Report</h2>
            <p><strong>Campaign:</strong> {$campaign['campaign_name']}</p>
            <p><strong>Scheduled Time:</strong> {$campaign['scheduled_at']}</p>
            <p><strong>Executed At:</strong> " . date('Y-m-d H:i:s') . "</p>
            <hr>
            <p><strong>Total Recipients:</strong> {$campaign['total_recipients']}</p>
            <p><strong>Successfully Sent:</strong> <span style='color: green;'>{$sentCount}</span></p>
            <p><strong>Failed:</strong> <span style='color: red;'>{$failedCount}</span></p>
            <p><strong>Success Rate:</strong> " . round(($sentCount / $campaign['total_recipients']) * 100, 2) . "%</p>
        ";
        
        $emailService->sendEmail($admin['email'], $subject, $message);
    } catch (Exception $e) {
        error_log("Failed to notify admin: " . $e->getMessage());
    }
}
