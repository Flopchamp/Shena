<?php
define('ROOT_PATH', __DIR__);
require_once 'config/config.php';
require_once 'app/core/Database.php';

$db = Database::getInstance()->getConnection();
$stmt = $db->prepare('SELECT id, first_name, last_name, total_commission FROM agents WHERE id = 9');
$stmt->execute();
$agent = $stmt->fetch();

echo "Agent ID: " . $agent['id'] . "\n";
echo "Name: " . $agent['first_name'] . " " . $agent['last_name'] . "\n";
echo "Total Commission in DB: KES " . number_format($agent['total_commission'], 2) . "\n";
