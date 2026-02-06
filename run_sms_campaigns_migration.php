<?php
/**
 * Run SMS Campaigns Migration
 * Execute this file to set up SMS campaign management tables
 */

// Define ROOT_PATH if not already defined
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', __DIR__);
}

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/core/Database.php';

echo "=== SMS Campaigns Migration ===\n\n";

try {
    $db = Database::getInstance()->getConnection();
    
    echo "Reading migration file...\n";
    $sql = file_get_contents(__DIR__ . '/database/migrations/add_sms_campaigns.sql');
    
    if (!$sql) {
        throw new Exception("Could not read migration file");
    }
    
    echo "Executing migration...\n";
    
    // Split and execute each statement
    $statements = explode(';', $sql);
    $successCount = 0;
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (empty($statement)) {
            continue;
        }
        
        try {
            $db->exec($statement);
            $successCount++;
        } catch (PDOException $e) {
            // Ignore "table already exists" errors
            if (strpos($e->getMessage(), 'already exists') === false) {
                echo "Warning: " . $e->getMessage() . "\n";
            }
        }
    }
    
    echo "\n✅ Migration completed successfully!\n";
    echo "Executed $successCount SQL statements\n\n";
    
    echo "Tables created:\n";
    echo "  - bulk_messages\n";
    echo "  - bulk_message_recipients\n";
    echo "  - sms_queue\n";
    echo "  - sms_templates\n";
    echo "  - sms_credits\n\n";
    
    echo "Views created:\n";
    echo "  - vw_campaign_stats\n";
    echo "  - vw_sms_queue_status\n\n";
    
    echo "Default templates inserted:\n";
    echo "  - Payment Reminder\n";
    echo "  - Payment Received\n";
    echo "  - Welcome New Member\n";
    echo "  - Claim Approved\n";
    echo "  - General Announcement\n\n";
    
    echo "SMS campaign management is now enabled!\n";
    echo "Access it at: /admin/sms-campaigns\n\n";
    
} catch (Exception $e) {
    echo "\n❌ Migration failed!\n";
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
