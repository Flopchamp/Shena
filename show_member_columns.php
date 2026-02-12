<?php
define('ROOT_PATH', __DIR__);
define('CONFIG_PATH', ROOT_PATH . '/config');
require_once CONFIG_PATH . '/config.php';
require_once 'app/core/Database.php';

$db = Database::getInstance()->getConnection();
$stmt = $db->query('DESCRIBE members');
$cols = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Members table columns:\n";
foreach ($cols as $col) {
    echo "  - " . $col['Field'] . "\n";
}
