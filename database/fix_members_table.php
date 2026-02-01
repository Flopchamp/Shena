<?php
// Quick script to add missing maturity_ends column
define('ROOT_PATH', dirname(__DIR__));
define('CONFIG_PATH', ROOT_PATH . '/config');
define('VIEWS_PATH', ROOT_PATH . '/resources/views');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/core/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // Check if column exists
    $stmt = $db->query("SHOW COLUMNS FROM members LIKE 'maturity_ends'");
    $exists = $stmt->fetch();
    
    if (!$exists) {
        echo "Adding maturity_ends column...\n";
        $db->exec("ALTER TABLE members ADD COLUMN maturity_ends DATE NULL AFTER status");
        echo "✓ Column maturity_ends added successfully!\n";
    } else {
        echo "✓ Column maturity_ends already exists.\n";
    }
    
    // Show current structure
    echo "\nCurrent members table structure:\n";
    $stmt = $db->query("DESCRIBE members");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  - {$row['Field']} ({$row['Type']})\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
