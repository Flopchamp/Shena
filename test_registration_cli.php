<?php
/**
 * CLI Member Registration Test Script
 * Tests the registration process from command line
 */

define('ROOT_PATH', __DIR__);
define('CONFIG_PATH', ROOT_PATH . '/config');
define('APP_PATH', ROOT_PATH . '/app');

require_once CONFIG_PATH . '/config.php';
require_once APP_PATH . '/core/Database.php';
require_once APP_PATH . '/core/BaseModel.php';
require_once APP_PATH . '/models/User.php';
require_once APP_PATH . '/models/Member.php';

// Test data
$testData = [
    'first_name' => 'Jane',
    'last_name' => 'Doe',
    'email' => 'jane.doe' . time() . '@test.com',
    'phone' => '+254712' . rand(100000, 999999),
    'password' => 'Test@1234',
    'id_number' => 'ID' . rand(10000000, 99999999),
    'date_of_birth' => '1995-05-15',
    'gender' => 'female',
    'address' => '123 Test Street, Nairobi',
    'next_of_kin' => 'John Doe',
    'next_of_kin_phone' => '+254722' . rand(100000, 999999),
    'package' => 'individual_below_70'
];

echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║         CLI MEMBER REGISTRATION TEST                      ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n\n";

echo "📝 Test Data:\n";
echo str_repeat("─", 60) . "\n";
foreach ($testData as $key => $value) {
    if ($key !== 'password') {
        echo sprintf("%-20s: %s\n", ucwords(str_replace('_', ' ', $key)), $value);
    } else {
        echo sprintf("%-20s: %s\n", ucwords(str_replace('_', ' ', $key)), str_repeat('*', strlen($value)));
    }
}
echo str_repeat("─", 60) . "\n\n";

$userModel = new User();
$memberModel = new Member();
$db = Database::getInstance();

// Step 1: Validation
echo "🔍 Step 1: Validating Input Data\n";
echo str_repeat("─", 60) . "\n";

$errors = [];

// Email validation
if (!filter_var($testData['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format";
} else {
    echo "✅ Email format valid\n";
}

// Phone validation
if (!preg_match('/^(\+?254|0)[17][0-9]{8}$/', $testData['phone'])) {
    $errors[] = "Invalid phone number format";
} else {
    echo "✅ Phone format valid (Kenyan)\n";
}

// Password validation
if (strlen($testData['password']) < 8) {
    $errors[] = "Password too short (minimum 8 characters)";
} else {
    echo "✅ Password length valid\n";
}

// Age validation
$dob = new DateTime($testData['date_of_birth']);
$now = new DateTime();
$age = $now->diff($dob)->y;
if ($age < 18 || $age > 100) {
    $errors[] = "Age must be between 18 and 100 (current: $age)";
} else {
    echo "✅ Age valid: $age years\n";
}

// Check if email exists
if ($userModel->findByEmail($testData['email'])) {
    $errors[] = "Email already registered";
} else {
    echo "✅ Email available\n";
}

// Check if phone exists
if ($userModel->findByPhone($testData['phone'])) {
    $errors[] = "Phone number already registered";
} else {
    echo "✅ Phone number available\n";
}

// Validate package
global $membership_packages;
if (!isset($membership_packages[$testData['package']])) {
    $errors[] = "Invalid membership package";
} else {
    $package = $membership_packages[$testData['package']];
    echo "✅ Package valid: {$package['name']}\n";
}

if (!empty($errors)) {
    echo "\n❌ VALIDATION FAILED:\n";
    foreach ($errors as $error) {
        echo "   • $error\n";
    }
    exit(1);
}

echo "\n✅ All validations passed!\n\n";

// Step 2: Database Transaction
echo "💾 Step 2: Creating Database Records\n";
echo str_repeat("─", 60) . "\n";

try {
    $db->getConnection()->beginTransaction();
    echo "✅ Transaction started\n";
    
    // Create user account
    $userData = [
        'email' => $testData['email'],
        'phone' => $testData['phone'],
        'password' => password_hash($testData['password'], PASSWORD_DEFAULT),
        'first_name' => $testData['first_name'],
        'last_name' => $testData['last_name'],
        'role' => 'member',
        'status' => 'active',
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    $userId = $userModel->create($userData);
    echo "✅ User account created (ID: $userId)\n";
    
    // Generate member number
    $memberNumber = 'SC' . date('Y') . str_pad($userId, 4, '0', STR_PAD_LEFT);
    echo "✅ Member number generated: $memberNumber\n";
    
    // Calculate monthly contribution and maturity
    $package = $membership_packages[$testData['package']];
    $monthlyContribution = $package['monthly_contribution'];
    $maturityMonths = $age >= 81 ? MATURITY_PERIOD_80_AND_ABOVE : MATURITY_PERIOD_UNDER_80;
    $maturityEnds = date('Y-m-d', strtotime("+{$maturityMonths} months"));
    
    echo "✅ Monthly contribution: KES " . number_format($monthlyContribution, 2) . "\n";
    echo "✅ Maturity period: $maturityMonths months (ends: $maturityEnds)\n";
    
    // Create member record
    $memberData = [
        'user_id' => $userId,
        'member_number' => $memberNumber,
        'id_number' => $testData['id_number'],
        'date_of_birth' => $testData['date_of_birth'],
        'gender' => $testData['gender'],
        'address' => $testData['address'],
        'next_of_kin' => $testData['next_of_kin'],
        'next_of_kin_phone' => $testData['next_of_kin_phone'],
        'package' => $package['category'] ?? 'individual',
        'monthly_contribution' => $monthlyContribution,
        'status' => 'inactive',
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    $memberId = $memberModel->create($memberData);
    echo "✅ Member record created (ID: $memberId)\n";
    
    $db->getConnection()->commit();
    echo "✅ Transaction committed\n\n";
    
    // Step 3: Verification
    echo "🔎 Step 3: Verifying Created Records\n";
    echo str_repeat("─", 60) . "\n";
    
    $createdUser = $userModel->find($userId);
    $createdMember = $memberModel->find($memberId);
    
    if ($createdUser && $createdMember) {
        echo "✅ User record verified\n";
        echo "✅ Member record verified\n\n";
        
        // Display summary
        echo "╔═══════════════════════════════════════════════════════════╗\n";
        echo "║              REGISTRATION SUCCESSFUL                      ║\n";
        echo "╚═══════════════════════════════════════════════════════════╝\n\n";
        
        echo "📋 Account Details:\n";
        echo str_repeat("─", 60) . "\n";
        echo "Member Number      : $memberNumber\n";
        echo "Full Name          : {$testData['first_name']} {$testData['last_name']}\n";
        echo "Email              : {$testData['email']}\n";
        echo "Phone              : {$testData['phone']}\n";
        echo "National ID        : {$testData['id_number']}\n";
        echo "Date of Birth      : {$testData['date_of_birth']} (Age: $age)\n";
        echo "Gender             : " . ucfirst($testData['gender']) . "\n";
        echo "Package            : {$package['name']}\n";
        echo "Monthly Contribution: KES " . number_format($monthlyContribution, 2) . "\n";
        echo "Status             : inactive (pending payment)\n";
        echo "Maturity Date      : $maturityEnds\n";
        echo "Registration Date  : " . date('Y-m-d H:i:s') . "\n";
        echo str_repeat("─", 60) . "\n\n";
        
        echo "🎯 Next Steps:\n";
        echo "   1. Pay registration fee: KES 200\n";
        echo "   2. Make first monthly contribution: KES " . number_format($monthlyContribution, 2) . "\n";
        echo "   3. Account will activate after payment confirmation\n";
        echo "   4. Benefits coverage starts after $maturityMonths months\n\n";
        
        echo "🔐 Login Credentials:\n";
        echo "   Email    : {$testData['email']}\n";
        echo "   Password : {$testData['password']}\n";
        echo "   URL      : http://localhost:8000/login\n\n";
        
        // Database queries for manual verification
        echo "📊 Manual Verification Queries:\n";
        echo str_repeat("─", 60) . "\n";
        echo "-- Check user record:\n";
        echo "SELECT * FROM users WHERE id = $userId;\n\n";
        echo "-- Check member record:\n";
        echo "SELECT * FROM members WHERE id = $memberId;\n\n";
        echo "-- Check full details:\n";
        echo "SELECT u.*, m.* FROM users u \n";
        echo "JOIN members m ON u.id = m.user_id \n";
        echo "WHERE u.id = $userId;\n";
        echo str_repeat("─", 60) . "\n\n";
        
        echo "✅ TEST COMPLETED SUCCESSFULLY!\n\n";
        exit(0);
        
    } else {
        throw new Exception("Verification failed: Records not found after creation");
    }
    
} catch (Exception $e) {
    $db->getConnection()->rollback();
    echo "❌ Transaction rolled back\n\n";
    
    echo "╔═══════════════════════════════════════════════════════════╗\n";
    echo "║              REGISTRATION FAILED                          ║\n";
    echo "╚═══════════════════════════════════════════════════════════╝\n\n";
    
    echo "❌ Error: " . $e->getMessage() . "\n\n";
    echo "📍 Stack Trace:\n";
    echo str_repeat("─", 60) . "\n";
    echo $e->getTraceAsString() . "\n\n";
    exit(1);
}
