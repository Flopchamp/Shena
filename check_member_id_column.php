<?php
define('ROOT_PATH', __DIR__);
require_once 'config/config.php';
require_once 'app/core/Database.php';

$db = Database::getInstance();
$result = $db->fetchAll("SHOW COLUMNS FROM payments WHERE Field = 'member_id'");
print_r($result);

// Check if we need to alter the column
if (!empty($result) && $result[0]['Null'] === 'NO') {
    echo "\n\nmember_id does NOT allow NULL. Running ALTER command...\n";
    try {
        $db->execute("ALTER TABLE payments MODIFY COLUMN member_id INT NULL");
        echo "✓ member_id column modified to allow NULL\n";
    } catch (Exception $e) {
        echo "✗ Error: " . $e->getMessage() . "\n";
    }
}
