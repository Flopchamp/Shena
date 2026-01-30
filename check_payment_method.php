<?php
define('ROOT_PATH', __DIR__);
require 'config/config.php';
require 'app/core/Database.php';

$db = Database::getInstance();
$col = $db->fetch('SHOW COLUMNS FROM payments LIKE "payment_method"');
echo "payment_method column type:\n";
print_r($col);
