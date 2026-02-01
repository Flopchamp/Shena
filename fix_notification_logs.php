<?php
/**
 * Check and fix notification_logs table
 */

define('ROOT_PATH', __DIR__);

require_once 'config/config.php';
require_once 'app/core/Database.php';

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    echo "Checking notification_logs table...\n";
    echo str_repeat('=', 80) . "\n\n";
    
    // Check current structure
    $result = $conn->query('DESCRIBE notification_logs');
    $columns = $result->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Current columns:\n";
    $hasNotificationType = false;
    foreach ($columns as $col) {
        echo "- {$col['Field']} ({$col['Type']})\n";
        if ($col['Field'] === 'notification_type') {
            $hasNotificationType = true;
        }
    }
    
    echo "\n";
    
    if (!$hasNotificationType) {
        echo "Adding missing notification_type column...\n";
        $conn->exec("ALTER TABLE notification_logs ADD COLUMN notification_type VARCHAR(50) NULL AFTER method");
        echo "✓ Column added successfully\n";
    } else {
        echo "✓ notification_type column already exists\n";
    }
    
    echo "\n" . str_repeat('=', 80) . "\n";
    echo "Done!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
