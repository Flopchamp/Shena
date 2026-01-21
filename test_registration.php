<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');

require_once CONFIG_PATH . '/config.php';
require_once APP_PATH . '/core/Database.php';

echo "<h1>Registration Test</h1>";

try {
    $db = Database::getInstance();
    echo "✓ Database connected<br>";
    
    // Test user creation
    $testData = [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test' . time() . '@example.com',
        'phone' => '+254712' . rand(100000, 999999),
        'password' => password_hash('password123', PASSWORD_DEFAULT),
        'role' => 'member',
        'status' => 'pending'
    ];
    
    echo "<h2>Inserting test user...</h2>";
    $userId = $db->insert('users', $testData);
    echo "✓ User created with ID: $userId<br>";
    
    // Test member creation
    $memberData = [
        'user_id' => $userId,
        'member_number' => 'SC' . date('Y') . str_pad($userId, 4, '0', STR_PAD_LEFT),
        'id_number' => rand(10000000, 99999999),
        'date_of_birth' => '1990-01-01',
        'gender' => 'male',
        'package' => 'individual',
        'monthly_contribution' => 500.00,
        'status' => 'inactive'
    ];
    
    echo "<h2>Inserting test member...</h2>";
    $memberId = $db->insert('members', $memberData);
    echo "✓ Member created with ID: $memberId<br>";
    
    echo "<h2 style='color: green;'>✓ Registration process works!</h2>";
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>✗ Error: " . $e->getMessage() . "</h2>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
