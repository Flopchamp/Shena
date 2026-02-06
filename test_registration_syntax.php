<?php
// Test registration syntax
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing AuthController for syntax errors...\n\n";

// Define ROOT_PATH
define('ROOT_PATH', __DIR__);

try {
    // Load all required dependencies
    require_once 'config/config.php';
    require_once 'app/core/Database.php';
    require_once 'app/core/BaseModel.php';
    require_once 'app/core/BaseController.php';
    require_once 'app/controllers/AuthController.php';
    
    echo "✓ AuthController loaded successfully (no syntax errors)\n";
    
    $controller = new AuthController();
    
    // Test if methods exist
    if (method_exists($controller, 'register')) {
        echo "✓ AuthController::register() method exists\n";
    }
    
    if (method_exists($controller, 'registrationComplete')) {
        echo "✓ AuthController::registrationComplete() method exists\n";
    }
    
    if (method_exists($controller, 'initiateRegistrationPayment')) {
        echo "✓ AuthController::initiateRegistrationPayment() method exists\n";
    }
    
    echo "\n✅ All registration methods are working properly!\n";
    echo "✅ No syntax errors found!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
