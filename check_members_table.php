<?php
define('ROOT_PATH', __DIR__);
require 'config/config.php';
require 'app/core/Database.php';

$db = Database::getInstance();
$cols = $db->fetchAll('DESCRIBE members');
echo "Members table columns:\n";
foreach ($cols as $col) {
    echo "- " . $col['Field'] . " (" . $col['Type'] . ")\n";
}
