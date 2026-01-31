<?php
/**
 * Migration: Create notification_logs table
 * Tracks SMS and email notification attempts with fallback status
 */

// Define ROOT_PATH if not defined
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(__DIR__)));
}

require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/app/core/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    echo "Creating notification_logs table...\n";
    
    $sql = "CREATE TABLE IF NOT EXISTS notification_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        phone VARCHAR(20) NULL,
        email VARCHAR(255) NULL,
        recipient_name VARCHAR(255) NULL,
        method ENUM('sms', 'email', 'failed') NOT NULL,
        status ENUM('success', 'failed') NOT NULL,
        message TEXT NULL,
        notes TEXT NULL COMMENT 'Additional info like fallback reason',
        created_at DATETIME NOT NULL,
        INDEX idx_phone (phone),
        INDEX idx_email (email),
        INDEX idx_method_status (method, status),
        INDEX idx_created_at (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $db->exec($sql);
    echo "✓ notification_logs table created successfully\n";
    
    // Add fallback tracking columns to bulk_message_recipients
    echo "\nAdding email fallback columns to bulk_message_recipients...\n";
    
    // Check if columns exist first
    $checkSql = "SHOW COLUMNS FROM bulk_message_recipients LIKE 'email_fallback_sent'";
    $result = $db->query($checkSql);
    
    if ($result->rowCount() == 0) {
        $alterSql = "ALTER TABLE bulk_message_recipients 
                     ADD COLUMN email_fallback_sent BOOLEAN DEFAULT FALSE,
                     ADD COLUMN email_sent_at DATETIME NULL,
                     ADD COLUMN delivery_method ENUM('sms', 'email', 'failed') NULL";
        
        $db->exec($alterSql);
        echo "✓ Email fallback columns added to bulk_message_recipients\n";
    } else {
        echo "ℹ Email fallback columns already exist\n";
    }
    
    // Create settings table if it doesn't exist
    echo "\nEnsuring settings table exists...\n";
    
    $settingsSql = "CREATE TABLE IF NOT EXISTS settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(100) UNIQUE NOT NULL,
        setting_value TEXT NULL,
        setting_type ENUM('boolean', 'string', 'integer', 'json') DEFAULT 'string',
        description TEXT NULL,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_setting_key (setting_key)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $db->exec($settingsSql);
    echo "✓ Settings table ready\n";
    
    // Insert default email fallback setting
    echo "\nInserting default email fallback setting...\n";
    
    $insertSetting = "INSERT INTO settings (setting_key, setting_value, setting_type, description) 
                      VALUES ('email_fallback_enabled', '1', 'boolean', 'Enable automatic email fallback when SMS fails')
                      ON DUPLICATE KEY UPDATE setting_key = setting_key";
    
    $db->exec($insertSetting);
    echo "✓ Default email fallback setting configured (enabled)\n";
    
    echo "\n=== Migration completed successfully! ===\n";
    echo "\nFeatures added:\n";
    echo "- Notification logging with SMS/email tracking\n";
    echo "- Email fallback columns for campaign recipients\n";
    echo "- Settings table for system configuration\n";
    echo "- Email fallback enabled by default\n\n";
    
} catch (PDOException $e) {
    echo "❌ Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
