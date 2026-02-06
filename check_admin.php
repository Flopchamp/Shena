<?php
define('ROOT_PATH', __DIR__);
define('CONFIG_PATH', ROOT_PATH . '/config');
define('APP_PATH', ROOT_PATH . '/app');

require_once CONFIG_PATH . '/config.php';
require_once APP_PATH . '/core/Database.php';

$db = Database::getInstance();
$result = $db->getConnection()->query('DESCRIBE members');

echo "=== MEMBERS TABLE STRUCTURE ===\n\n";
while($row = $result->fetch()) {
    echo str_pad($row['Field'], 30) . " " . $row['Type'] . "\n";
}
