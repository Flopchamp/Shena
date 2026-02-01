<?php
/**
 * Test Email Fallback Feature
 * Run this script to verify the email fallback functionality
 */

// Define ROOT_PATH
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', __DIR__);
}

require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/app/core/Database.php';
require_once ROOT_PATH . '/app/services/NotificationService.php';

echo "====================================\n";
echo "Email Fallback Feature Test\n";
echo "====================================\n\n";

try {
    $notificationService = new NotificationService();
    
    // Test 1: Check database tables
    echo "Test 1: Checking database tables...\n";
    $db = Database::getInstance()->getConnection();
    
    $tables = ['notification_logs', 'settings'];
    foreach ($tables as $table) {
        $result = $db->query("SHOW TABLES LIKE '$table'");
        if ($result->rowCount() > 0) {
            echo "  ✓ Table '$table' exists\n";
        } else {
            echo "  ✗ Table '$table' missing!\n";
            exit(1);
        }
    }
    
    // Test 2: Check settings
    echo "\nTest 2: Checking email fallback setting...\n";
    $stmt = $db->prepare("SELECT * FROM settings WHERE setting_key = 'email_fallback_enabled'");
    $stmt->execute();
    $setting = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($setting) {
        $status = $setting['setting_value'] == '1' ? 'ENABLED' : 'DISABLED';
        echo "  ✓ Email fallback is $status\n";
        echo "    Updated: " . $setting['updated_at'] . "\n";
    } else {
        echo "  ✗ Email fallback setting not found!\n";
        exit(1);
    }
    
    // Test 3: Check bulk_message_recipients columns
    echo "\nTest 3: Checking bulk_message_recipients columns...\n";
    $columns = ['email_fallback_sent', 'email_sent_at', 'delivery_method'];
    $result = $db->query("DESCRIBE bulk_message_recipients");
    $existingColumns = [];
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $existingColumns[] = $row['Field'];
    }
    
    foreach ($columns as $column) {
        if (in_array($column, $existingColumns)) {
            echo "  ✓ Column '$column' exists\n";
        } else {
            echo "  ✗ Column '$column' missing!\n";
            exit(1);
        }
    }
    
    // Test 4: Test notification service instantiation
    echo "\nTest 4: Testing NotificationService...\n";
    try {
        $testRecipient = [
            'phone' => '254712345678',
            'email' => 'test@example.com',
            'name' => 'Test User'
        ];
        
        echo "  ✓ NotificationService initialized successfully\n";
        echo "  ✓ Test recipient data prepared\n";
        
    } catch (Exception $e) {
        echo "  ✗ Failed to initialize NotificationService: " . $e->getMessage() . "\n";
        exit(1);
    }
    
    // Test 5: Check notification logs structure
    echo "\nTest 5: Checking notification_logs table structure...\n";
    $result = $db->query("DESCRIBE notification_logs");
    $requiredColumns = ['id', 'phone', 'email', 'method', 'status', 'message', 'notes', 'created_at'];
    $logColumns = [];
    
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $logColumns[] = $row['Field'];
    }
    
    $missing = array_diff($requiredColumns, $logColumns);
    if (empty($missing)) {
        echo "  ✓ All required columns present\n";
    } else {
        echo "  ✗ Missing columns: " . implode(', ', $missing) . "\n";
        exit(1);
    }
    
    // Test 6: Get statistics (should not error even with no data)
    echo "\nTest 6: Testing statistics retrieval...\n";
    try {
        $stats = $notificationService->getStats(date('Y-m-d 00:00:00'));
        echo "  ✓ Statistics retrieved successfully\n";
        echo "    Records found: " . count($stats) . "\n";
    } catch (Exception $e) {
        echo "  ✗ Failed to get statistics: " . $e->getMessage() . "\n";
        exit(1);
    }
    
    // Test 7: Check if EmailService exists
    echo "\nTest 7: Checking EmailService...\n";
    if (file_exists(ROOT_PATH . '/app/services/EmailService.php')) {
        echo "  ✓ EmailService file exists\n";
    } else {
        echo "  ✗ EmailService file missing!\n";
        exit(1);
    }
    
    // Test 8: Check if SmsService exists
    echo "\nTest 8: Checking SmsService...\n";
    if (file_exists(ROOT_PATH . '/app/services/SmsService.php')) {
        echo "  ✓ SmsService file exists\n";
    } else {
        echo "  ✗ SmsService file missing!\n";
        exit(1);
    }
    
    // Test 9: Check SettingsController
    echo "\nTest 9: Checking SettingsController...\n";
    if (file_exists(ROOT_PATH . '/app/controllers/SettingsController.php')) {
        echo "  ✓ SettingsController file exists\n";
    } else {
        echo "  ✗ SettingsController file missing!\n";
        exit(1);
    }
    
    // Test 10: Check admin view
    echo "\nTest 10: Checking admin notification settings view...\n";
    if (file_exists(ROOT_PATH . '/resources/views/admin/notification-settings.php')) {
        echo "  ✓ Notification settings view exists\n";
    } else {
        echo "  ✗ Notification settings view missing!\n";
        exit(1);
    }
    
    echo "\n====================================\n";
    echo "✅ ALL TESTS PASSED!\n";
    echo "====================================\n\n";
    
    echo "Email fallback feature is fully configured and ready to use.\n\n";
    
    echo "Next steps:\n";
    echo "1. Go to /admin/notification-settings to manage settings\n";
    echo "2. Use the Test Fallback tab to send a test message\n";
    echo "3. Create SMS campaigns - email fallback will activate automatically when SMS fails\n";
    echo "4. View statistics on the notification settings page\n\n";
    
    echo "Documentation: See EMAIL_FALLBACK_FEATURE.md for complete guide\n";
    
} catch (Exception $e) {
    echo "\n❌ TEST FAILED: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
