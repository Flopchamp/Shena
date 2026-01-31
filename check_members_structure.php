<?php
/**
 * Check members table structure
 */

define('ROOT_PATH', __DIR__);

require_once 'config/config.php';
require_once 'app/core/Database.php';

try {
    $db = Database::getInstance();
    $result = $db->getConnection()->query('DESCRIBE members');
    $columns = $result->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Members table structure:\n";
    echo str_repeat('=', 80) . "\n";
    
    foreach ($columns as $column) {
        echo sprintf(
            "%-25s %-20s %-10s %-10s\n",
            $column['Field'],
            $column['Type'],
            $column['Null'],
            $column['Key']
        );
    }
    
    echo "\nChecking for maturity_ends column...\n";
    $hasMaturityEnds = false;
    foreach ($columns as $column) {
        if ($column['Field'] === 'maturity_ends') {
            $hasMaturityEnds = true;
            break;
        }
    }
    
    if ($hasMaturityEnds) {
        echo "✓ maturity_ends column EXISTS\n";
    } else {
        echo "✗ maturity_ends column MISSING\n";
        echo "\nTo fix this, run the following SQL:\n";
        echo "ALTER TABLE members ADD COLUMN maturity_ends DATE NULL AFTER status;\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
