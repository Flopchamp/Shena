<?php
define('ROOT_PATH', __DIR__);
require_once 'config/config.php';
require_once 'app/core/Database.php';

$db = Database::getInstance()->getConnection();

// Password for both accounts (easy to remember for testing)
$testPassword = 'Password123!';
$hashedPassword = password_hash($testPassword, PASSWORD_DEFAULT);

// Generate unique emails with timestamp
$timestamp = time();
$memberEmail = "member{$timestamp}@test.com";
$memberPhone = '2547' . substr($timestamp, -8);
$agentEmail = "agent{$timestamp}@test.com";
$agentPhone = '2549' . substr($timestamp, -8);

echo "=== CREATING TEST ACCOUNTS ===\n\n";

// Create test member
try {
    // Insert user
    $stmt = $db->prepare("
        INSERT INTO users (first_name, last_name, email, phone, password, role, status, email_verified_at)
        VALUES (?, ?, ?, ?, ?, 'member', 'active', NOW())
    ");
    
    $stmt->execute([
        'John',
        'Kamau',
        $memberEmail,
        $memberPhone,
        $hashedPassword
    ]);
    
    $userId = $db->lastInsertId();
    
    // Generate member number
    $stmt = $db->query("SELECT COUNT(*) as count FROM members");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    $memberNumber = 'SCA' . date('Y') . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    
    // Insert member
    $stmt = $db->prepare("
        INSERT INTO members (
            user_id, member_number, id_number, date_of_birth, gender, 
            package, package_key, monthly_contribution, status, 
            maturity_ends, coverage_ends
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, DATE_ADD(NOW(), INTERVAL 90 DAY), DATE_ADD(NOW(), INTERVAL 1 YEAR))
    ");
    
    $idNumber = '99' . substr($timestamp, -6); // Unique ID number
    
    $stmt->execute([
        $userId,
        $memberNumber,
        $idNumber,
        '1990-05-15',
        'male',
        'individual',
        'individual_below_70',
        1000.00,
        'active'
    ]);
    
    echo "✓ MEMBER CREATED SUCCESSFULLY\n";
    echo "Name: John Kamau\n";
    echo "Member Number: $memberNumber\n";
    echo "Email: $memberEmail\n";
    echo "Phone: $memberPhone\n";
    echo "Password: $testPassword\n";
    echo "Package: Individual (KES 1,000/month)\n";
    echo "Status: Active\n\n";
    
} catch (PDOException $e) {
    echo "✗ Error creating member: " . $e->getMessage() . "\n\n";
}

// Create test agent
try {
    // Insert user
    $stmt = $db->prepare("
        INSERT INTO users (first_name, last_name, email, phone, password, role, status, email_verified_at)
        VALUES (?, ?, ?, ?, ?, 'member', 'active', NOW())
    ");
    
    $stmt->execute([
        'Mary',
        'Wanjiru',
        $agentEmail,
        $agentPhone,
        $hashedPassword
    ]);
    
    $agentUserId = $db->lastInsertId();
    
    // Generate agent number
    $stmt = $db->query("SELECT COUNT(*) as count FROM agents");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    $agentNumber = 'AG' . date('Y') . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    
    // Insert agent
    $stmt = $db->prepare("
        INSERT INTO agents (
            user_id, agent_number, first_name, last_name, national_id,
            phone, email, county, commission_rate, status, registration_date
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE())
    ");
    
    $agentNationalId = '88' . substr($timestamp, -6); // Unique national ID
    
    $stmt->execute([
        $agentUserId,
        $agentNumber,
        'Mary',
        'Wanjiru',
        $agentNationalId,
        $agentPhone,
        $agentEmail,
        'Nairobi',
        15.00,
        'active'
    ]);
    
    echo "✓ AGENT CREATED SUCCESSFULLY\n";
    echo "Name: Mary Wanjiru\n";
    echo "Agent Number: $agentNumber\n";
    echo "Email: $agentEmail\n";
    echo "Phone: $agentPhone\n";
    echo "Password: $testPassword\n";
    echo "Commission Rate: 15%\n";
    echo "Status: Active\n\n";
    
} catch (PDOException $e) {
    echo "✗ Error creating agent: " . $e->getMessage() . "\n\n";
}

echo "\n=== LOGIN INSTRUCTIONS ===\n";
echo "Use the email or phone number with password: $testPassword\n";
echo "Member Portal: http://localhost:8000/member/dashboard\n";
echo "Agent Portal: http://localhost:8000/agent/dashboard\n";
