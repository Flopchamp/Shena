<?php
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/config.php';
// Database singleton load
require_once ROOT_PATH . '/app/core/Database.php';

try {
    $db = Database::getInstance();
    
    echo "Testing Basic Select...\n";
    $db->fetchAll("SELECT id FROM members LIMIT 1");
    echo "Basic Select OK.\n";
    
    echo "Testing package_key...\n";
    $db->fetchAll("SELECT package_key FROM members LIMIT 1");
    echo "package_key OK.\n";
    
    echo "Testing date_of_birth...\n";
    $db->fetchAll("SELECT date_of_birth FROM members LIMIT 1");
    echo "date_of_birth OK.\n";

} catch (Exception $e) {
    echo "FAIL: " . $e->getMessage() . "\n";
}
