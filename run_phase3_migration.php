<?php
/**
 * Run Phase 3 Migration
 * Creates agents, commissions, notification preferences, and bulk messaging tables
 */

define('ROOT_PATH', __DIR__);
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/core/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    echo "Running Phase 3 Migration...\n\n";
    
    // Read migration file
    $sql = file_get_contents(__DIR__ . '/database/migrations/003_add_agents_and_notifications.sql');
    
    // Remove comments
    $sql = preg_replace('/--.*$/m', '', $sql);
    $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
    
    // Execute the entire SQL at once
    try {
        $db->exec($sql);
        echo "✓ Successfully executed all migration statements\n\n";
    } catch (PDOException $e) {
        echo "Note: Some operations may have been skipped (tables already exist)\n";
        echo "Error details: " . $e->getMessage() . "\n\n";
    }
    
    // Verify tables
    echo "Verifying created tables:\n";
    $tables = ['agents', 'agent_commissions', 'notification_preferences', 'bulk_messages', 'bulk_message_recipients'];
    
    foreach ($tables as $table) {
        $result = $db->query("SHOW TABLES LIKE '$table'")->fetch();
        if ($result) {
            echo "✓ Table '$table' exists\n";
        } else {
            echo "✗ Table '$table' NOT FOUND\n";
        }
    }
    
    echo "\n";
    
} catch (Exception $e) {
    echo "Fatal error: " . $e->getMessage() . "\n";
    exit(1);
}
