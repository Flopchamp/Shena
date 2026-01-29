<?php
/**
 * Create Test Agent User
 * Creates a test agent account for testing agent functionality
 */

define('ROOT_PATH', __DIR__);
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/core/BaseModel.php';
require_once __DIR__ . '/app/models/User.php';
require_once __DIR__ . '/app/models/Agent.php';

echo "\n========================================\n";
echo "CREATE TEST AGENT USER\n";
echo "========================================\n\n";

try {
    $db = Database::getInstance()->getConnection();
    $userModel = new User();
    $agentModel = new Agent();
    
    // Test agent data
    $testEmail = 'agent@test.com';
    $testPassword = 'Agent@123';
    $testPhone = '+254712345678';
    $testNationalId = 'AG' . time();
    
    echo "Creating test agent account...\n\n";
    
    // Check if agent already exists
    $existingUser = $userModel->findByEmail($testEmail);
    
    if ($existingUser) {
        echo "⚠️  Agent user already exists!\n\n";
        echo "Login Credentials:\n";
        echo "==================\n";
        echo "Email: $testEmail\n";
        echo "Password: $testPassword (if not changed)\n";
        echo "URL: http://localhost:8000/login\n\n";
        
        // Check if agent profile exists
        $agent = $agentModel->getAgentByUserId($existingUser['id']);
        if ($agent) {
            echo "Agent Profile:\n";
            echo "==============\n";
            echo "Agent Number: {$agent['agent_number']}\n";
            echo "Name: {$agent['first_name']} {$agent['last_name']}\n";
            echo "Status: {$agent['status']}\n";
            echo "Commission Rate: {$agent['commission_rate']}%\n";
        } else {
            echo "⚠️  User exists but agent profile not found.\n";
        }
        
        exit(0);
    }
    
    // Start transaction
    $db->beginTransaction();
    
    // 1. Create user account
    $userData = [
        'first_name' => 'Test',
        'last_name' => 'Agent',
        'email' => $testEmail,
        'phone' => $testPhone,
        'password' => password_hash($testPassword, PASSWORD_DEFAULT),
        'role' => 'agent',
        'status' => 'active'
    ];
    
    $userId = $userModel->create($userData);
    
    if (!$userId) {
        throw new Exception("Failed to create user account");
    }
    
    echo "✓ User account created (ID: $userId)\n";
    
    // 2. Create agent profile
    $agentData = [
        'user_id' => $userId,
        'first_name' => 'Test',
        'last_name' => 'Agent',
        'national_id' => $testNationalId,
        'phone' => $testPhone,
        'email' => $testEmail,
        'address' => 'Test Address, Nairobi',
        'county' => 'Nairobi',
        'commission_rate' => 10.00
    ];
    
    $agentId = $agentModel->createAgent($agentData);
    
    if (!$agentId) {
        throw new Exception("Failed to create agent profile");
    }
    
    echo "✓ Agent profile created (ID: $agentId)\n";
    
    // Get the created agent details
    $agent = $agentModel->getAgentById($agentId);
    
    // Commit transaction
    $db->commit();
    
    echo "\n========================================\n";
    echo "✅ TEST AGENT CREATED SUCCESSFULLY\n";
    echo "========================================\n\n";
    
    echo "Login Credentials:\n";
    echo "==================\n";
    echo "Email: $testEmail\n";
    echo "Password: $testPassword\n";
    echo "URL: http://localhost:8000/login\n\n";
    
    echo "Agent Details:\n";
    echo "==============\n";
    echo "Agent Number: {$agent['agent_number']}\n";
    echo "Name: {$agent['first_name']} {$agent['last_name']}\n";
    echo "National ID: {$agent['national_id']}\n";
    echo "Phone: {$agent['phone']}\n";
    echo "Email: {$agent['email']}\n";
    echo "County: {$agent['county']}\n";
    echo "Status: {$agent['status']}\n";
    echo "Commission Rate: {$agent['commission_rate']}%\n";
    echo "Registration Date: {$agent['registration_date']}\n\n";
    
    echo "========================================\n";
    echo "Next Steps:\n";
    echo "1. Login at http://localhost:8000/login\n";
    echo "2. Use email: $testEmail\n";
    echo "3. Use password: $testPassword\n";
    echo "4. Access agent dashboard (coming soon)\n";
    echo "========================================\n\n";
    
} catch (Exception $e) {
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    
    echo "\n✗ Error: " . $e->getMessage() . "\n\n";
    exit(1);
}
