<?php
define('ROOT_PATH', __DIR__);
require_once 'config/config.php';
require_once 'app/core/Database.php';


$db = Database::getInstance()->getConnection();
$stmt = $db->query("SHOW COLUMNS FROM agent_commissions WHERE Field = 'commission_type'");
$column = $stmt->fetch();
echo 'Column Type: ' . $column['Type'] . PHP_EOL;
