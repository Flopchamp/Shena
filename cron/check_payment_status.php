<?php
/**
 * Automated Payment Monitoring - Check Missed Payments and Update Member Status
 * This script should run daily via cron job
 * 
 * Schedule: php /path/to/check_payment_status.php (run daily at midnight)
 * Cron: 0 0 * * * /usr/bin/php /path/to/Shena/cron/check_payment_status.php
 */

// Setup paths
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');

// Load configuration
require_once CONFIG_PATH . '/config.php';
require_once APP_PATH . '/core/Database.php';
require_once APP_PATH . '/core/BaseModel.php';
require_once APP_PATH . '/models/Member.php';
require_once APP_PATH . '/models/Payment.php';
require_once APP_PATH . '/services/EmailService.php';
require_once APP_PATH . '/services/SmsService.php';
require_once CONFIG_PATH . '/config.php';

// Load core classes
require_once APP_PATH . '/core/Database.php';
require_once APP_PATH . '/core/BaseModel.php';

// Load models
require_once APP_PATH . '/models/Member.php';
require_once APP_PATH . '/models/Payment.php';

// Load services
require_once APP_PATH . '/services/EmailService.php';
require_once APP_PATH . '/services/SmsService.php';

// Load helper functions
require_once APP_PATH . '/helpers/functions.php';

// Initialize
$memberModel = new Member();
$paymentModel = new Payment();
$emailService = new EmailService();
$smsService = new SmsService();

$logFile = ROOT_PATH . '/storage/logs/payment_monitoring_' . date('Y-m-d') . '.log';

/**
 * Log message to file
 */
function logMessage($message) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[{$timestamp}] {$message}" . PHP_EOL;
    
    // Ensure log directory exists
    $logDir = dirname($logFile);
    if (!file_exists($logDir)) {
        mkdir($logDir, 0777, true);
    }
    
    file_put_contents($logFile, $logEntry, FILE_APPEND);
    echo $logEntry;
}

logMessage("=== Starting Payment Monitoring Job ===");

try {
    // Get all active and grace_period members using custom query
    $db = Database::getInstance();
    $sql = "SELECT * FROM members WHERE status IN ('active', 'grace_period') AND maturity_ends <= :today";
    $members = $db->getConnection()->prepare($sql);
    $members->execute(['today' => $today]);
    $members = $members->fetchAll();
    
    logMessage("Found " . count($members) . " members to check");
    
    $today = date('Y-m-d');
    $counters = [
        'checked' => 0,
        'entered_grace' => 0,
        'defaulted' => 0,
        'warnings_sent' => 0,
        'errors' => 0
    ];
    
    foreach ($members as $member) {
        $counters['checked']++;
        $memberId = $member['id'];
        $memberNumber = $member['member_number'];
        
        try {
            // Skip if maturity period not yet completed
            if (!empty($member['maturity_ends']) && $member['maturity_ends'] > $today) {
                continue;
            }
            
            // Check if coverage has expired (no recent payments)
            if (empty($member['coverage_ends']) || $member['coverage_ends'] < $today) {
                $daysPastDue = floor((strtotime($today) - strtotime($member['coverage_ends'] ?? $today)) / 86400);
                
                // Calculate grace period (2 months = ~60 days)
                $gracePeriodDays = GRACE_PERIOD_MONTHS * 30;
                
                if ($daysPastDue > $gracePeriodDays) {
                    // Member has exceeded grace period - mark as defaulted
                    if ($member['status'] !== 'defaulted') {
                        $memberModel->update($memberId, [
                            'status' => 'defaulted',
                            'grace_period_expires' => null,
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                        
                        $counters['defaulted']++;
                        logMessage("Member {$memberNumber}: Marked as DEFAULTED ({$daysPastDue} days past due)");
                        
                        // Send account suspended notification
                        try {
                            $emailService->sendAccountSuspendedEmail($member['email'], [
                                'name' => $member['first_name'] . ' ' . $member['last_name'],
                                'member_number' => $memberNumber,
                                'days_past_due' => $daysPastDue,
                                'total_outstanding' => $member['outstanding_balance'] ?? 0
                            ]);
                            
                            $smsService->sendAccountDeactivationSms($member['phone'], [
                                'member_number' => $memberNumber
                            ]);
                            
                            logMessage("Member {$memberNumber}: Suspension notifications sent");
                        } catch (Exception $notifyEx) {
                            logMessage("ERROR sending suspension notifications to {$memberNumber}: " . $notifyEx->getMessage());
                        }
                    }
                } elseif ($daysPastDue > 0) {
                    // Member is within grace period
                    if ($member['status'] !== 'grace_period') {
                        $gracePeriodExpires = date('Y-m-d', strtotime($member['coverage_ends']) + ($gracePeriodDays * 86400));
                        
                        $memberModel->update($memberId, [
                            'status' => 'grace_period',
                            'grace_period_expires' => $gracePeriodExpires,
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                        
                        $counters['entered_grace']++;
                        logMessage("Member {$memberNumber}: Entered GRACE PERIOD ({$daysPastDue} days past due, expires {$gracePeriodExpires})");
                        
                        // Send grace period warning notification
                        try {
                            $daysLeft = GRACE_PERIOD_MONTHS * 30;
                            $emailService->sendGracePeriodWarning($member['email'], [
                                'name' => $member['first_name'] . ' ' . $member['last_name'],
                                'member_number' => $memberNumber,
                                'days_left' => $daysLeft,
                                'expiry_date' => date('F j, Y', strtotime($gracePeriodExpires)),
                                'monthly_amount' => $member['monthly_contribution'] ?? 0
                            ]);
                            
                            $smsService->sendGracePeriodWarning($member['phone'], [
                                'member_number' => $memberNumber,
                                'expiry_date' => date('M j, Y', strtotime($gracePeriodExpires))
                            ]);
                            
                            logMessage("Member {$memberNumber}: Grace period notifications sent");
                        } catch (Exception $notifyEx) {
                            logMessage("ERROR sending grace period notifications to {$memberNumber}: " . $notifyEx->getMessage());
                        }
                    }
                }
                
                // Send warning if approaching default (within 10 days of grace period expiry)
                if ($member['status'] === 'grace_period' && !empty($member['grace_period_expires'])) {
                    $daysUntilDefault = floor((strtotime($member['grace_period_expires']) - strtotime($today)) / 86400);
                    
                    if ($daysUntilDefault <= 10 && $daysUntilDefault > 0) {
                        // Send urgent warning notification
                        try {
                            $emailService->sendGracePeriodWarning($member['email'], [
                                'name' => $member['first_name'] . ' ' . $member['last_name'],
                                'member_number' => $memberNumber,
                                'days_left' => $daysUntilDefault,
                                'expiry_date' => date('F j, Y', strtotime($member['grace_period_expires'])),
                                'monthly_amount' => $member['monthly_contribution'] ?? 0,
                                'urgent' => true
                            ]);
                            
                            $smsService->sendPaymentReminderSms($member['phone'], [
                                'member_number' => $memberNumber,
                                'amount' => $member['monthly_contribution'] ?? 0
                            ]);
                            
                            $counters['warnings_sent']++;
                            logMessage("Member {$memberNumber}: URGENT warning sent - {$daysUntilDefault} days until default");
                        } catch (Exception $notifyEx) {
                            logMessage("ERROR sending urgent warning to {$memberNumber}: " . $notifyEx->getMessage());
                        }
                    }
                }
            }
            
        } catch (Exception $e) {
            $counters['errors']++;
            logMessage("ERROR processing member {$memberNumber}: " . $e->getMessage());
        }
    }
    
    logMessage("=== Payment Monitoring Complete ===");
    logMessage("Members checked: {$counters['checked']}");
    logMessage("Entered grace period: {$counters['entered_grace']}");
    logMessage("Marked as defaulted: {$counters['defaulted']}");
    logMessage("Warnings sent: {$counters['warnings_sent']}");
    logMessage("Errors: {$counters['errors']}");
    
} catch (Exception $e) {
    logMessage("FATAL ERROR: " . $e->getMessage());
    logMessage("Stack trace: " . $e->getTraceAsString());
    exit(1);
}

logMessage("=== Job Finished Successfully ===");
exit(0);
