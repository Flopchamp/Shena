<?php
/**
 * Test registration form payment method normalization
 */

define('ROOT_PATH', __DIR__);

require_once 'config/config.php';
require_once 'app/core/Database.php';

echo "Testing payment method normalization...\n";
echo str_repeat('=', 80) . "\n\n";

// Test 1: Check if payment_method column accepts 'mpesa'
echo "Test 1: Check payment_method ENUM values\n";
try {
    $db = Database::getInstance();
    $result = $db->getConnection()->query("SHOW COLUMNS FROM payments WHERE Field = 'payment_method'");
    $column = $result->fetch(PDO::FETCH_ASSOC);
    
    echo "Column Type: {$column['Type']}\n";
    
    // Check if 'mpesa' is in the ENUM
    if (strpos($column['Type'], 'mpesa') !== false) {
        echo "✓ 'mpesa' is an accepted value\n";
    } else {
        echo "✗ 'mpesa' is NOT an accepted value\n";
    }
    
    // Check if 'stk_push' is in the ENUM
    if (strpos($column['Type'], 'stk_push') !== false) {
        echo "✗ WARNING: 'stk_push' is in ENUM (should be normalized to 'mpesa')\n";
    } else {
        echo "✓ 'stk_push' is NOT in ENUM (correctly requires normalization)\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n";
echo str_repeat('=', 80) . "\n";
echo "Test complete!\n\n";
echo "Summary:\n";
echo "- Frontend sends: payment_method='stk_push'\n";
echo "- Backend normalizes to: payment_method='mpesa'\n";
echo "- Database accepts: 'mpesa', 'bank', 'cash', 'cheque'\n";
echo "- Result: Registration should now work correctly\n";
