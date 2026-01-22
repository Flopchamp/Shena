<?php
/**
 * Test admin login process
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define constants
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');

// Auto-load classes
spl_autoload_register(function ($class) {
    $paths = [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
        APP_PATH . '/services/',
        APP_PATH . '/core/',
        APP_PATH . '/helpers/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Load configuration
require_once CONFIG_PATH . '/config.php';

// Load helper functions
require_once APP_PATH . '/helpers/functions.php';

echo "=== Admin Login Test ===\n\n";

try {
    // Test 1: Database connection
    echo "Test 1: Database Connection\n";
    $db = Database::getInstance();
    echo "✓ Connected to database\n\n";
    
    // Test 2: Check admin user exists
    echo "Test 2: Checking for admin user\n";
    $admin = $db->fetch("SELECT * FROM users WHERE role = 'super_admin'");
    if ($admin) {
        echo "✓ Found admin user:\n";
        echo "  - ID: " . $admin['id'] . "\n";
        echo "  - Email: " . $admin['email'] . "\n";
        echo "  - First Name: " . $admin['first_name'] . "\n";
        echo "  - Role: " . $admin['role'] . "\n";
        echo "  - Status: " . $admin['status'] . "\n";
        echo "  - Password Hash: " . substr($admin['password'], 0, 20) . "...\n\n";
    } else {
        echo "✗ No admin user found\n\n";
        exit(1);
    }
    
    // Test 3: Test password verification
    echo "Test 3: Password Verification\n";
    $testPassword = 'password';
    $hash = $admin['password'];
    
    $result = password_verify($testPassword, $hash);
    echo "Testing password: '$testPassword'\n";
    echo "Hash in database: " . substr($hash, 0, 30) . "...\n";
    if ($result) {
        echo "✓ Password verification PASSED\n";
        echo "  The password is: $testPassword\n\n";
    } else {
        echo "✗ Password verification FAILED\n";
        echo "  The stored hash might not correspond to a simple test password\n\n";
        
        // Let's test some other common passwords
        $commonPasswords = ['admin', '123456', 'admin123', 'password123', 'shena', 'welcome'];
        echo "Testing common passwords:\n";
        foreach ($commonPasswords as $pwd) {
            if (password_verify($pwd, $hash)) {
                echo "  ✓ Found it! Password is: $pwd\n";
                break;
            } else {
                echo "  ✗ Not: $pwd\n";
            }
        }
        echo "\n";
    }
    
    // Test 4: Test User model
    echo "Test 4: User Model Test\n";
    $userModel = new User();
    
    // Test findByEmail
    $userByEmail = $userModel->findByEmail($admin['email']);
    if ($userByEmail && $userByEmail['id'] == $admin['id']) {
        echo "✓ findByEmail() works correctly\n";
    } else {
        echo "✗ findByEmail() failed\n";
    }
    
    // Test verifyPassword
    if ($userModel->verifyPassword($testPassword, $admin['password'])) {
        echo "✓ User model verifyPassword() works\n";
    } else {
        echo "✗ User model verifyPassword() failed\n";
    }
    
    echo "\n";
    
    // Test 5: Simulate admin login
    echo "Test 5: Simulating Admin Login Process\n";
    $username = $admin['email']; // or could be first name
    $password = $testPassword;
    
    // This mimics what AdminController@login does
    $foundAdmin = $userModel->findByEmail($username);
    
    if (!$foundAdmin) {
        // Try by first_name
        $query = "SELECT * FROM users WHERE first_name = ? AND role IN ('super_admin', 'manager') LIMIT 1";
        $foundAdmin = $db->fetch($query, [$username]);
    }
    
    if ($foundAdmin) {
        echo "✓ Admin found by username: " . $foundAdmin['first_name'] . "\n";
        
        if (password_verify($password, $foundAdmin['password'])) {
            echo "✓ Password verification succeeded\n";
            echo "✓ Admin login should SUCCEED\n";
        } else {
            echo "✗ Password verification failed\n";
            echo "✗ Admin login will FAIL\n";
        }
    } else {
        echo "✗ Admin not found\n";
        echo "✗ Admin login will FAIL\n";
    }
    
    echo "\n=== Test Complete ===\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
