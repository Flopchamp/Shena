<?php
define('ROOT_PATH', __DIR__);
require_once 'config/config.php';
require_once 'app/core/Database.php';

$db = Database::getInstance()->getConnection();
$stmt = $db->prepare('DELETE FROM users WHERE phone = ? OR email = ?');
$stmt->execute(['+254712345678', 'test.upgrade@example.com']);
echo "Deleted " . $stmt->rowCount() . " test user records\n";
