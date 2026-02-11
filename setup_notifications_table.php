<?php
/**
 * Setup Notifications Table
 */
$pdo = new PDO('mysql:host=localhost;dbname=shena_welfare_db;charset=utf8mb4', 'root', '4885');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if notifications table exists
$stmt = $pdo->query("SHOW TABLES LIKE 'notifications'");
if ($stmt->rowCount() > 0) {
    echo "✓ Notifications table exists\n";
} else {
    echo "Creating notifications table...\n";
    $sql = "CREATE TABLE IF NOT EXISTS notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        type VARCHAR(50) NOT NULL,
        message TEXT NOT NULL,
        action_url VARCHAR(255) NULL,
        metadata JSON NULL,
        is_read BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        read_at TIMESTAMP NULL,
        INDEX idx_notifications_type (type),
        INDEX idx_notifications_read (is_read),
        INDEX idx_notifications_created (created_at)
    ) ENGINE=InnoDB";
    
    $pdo->exec($sql);
    echo "✓ Notifications table created successfully\n";
}
