<?php
/**
 * Debug Claim Submission - Test Script
 */
define('ROOT_PATH', __DIR__);
define('CONFIG_PATH', ROOT_PATH . '/config');
define('VIEWS_PATH', ROOT_PATH . '/resources/views');
define('UPLOADS_PATH', ROOT_PATH . '/storage/uploads');

require_once CONFIG_PATH . '/config.php';
require_once 'app/core/Database.php';
require_once 'app/core/BaseModel.php';
require_once 'app/models/Claim.php';
require_once 'app/models/Member.php';

session_start();

echo "===========================================\n";
echo "Claim Submission Debug Test\n";
echo "===========================================\n\n";

try {
    // Test database connection
    $db = Database::getInstance();
    echo "✓ Database connection successful\n\n";
    
    // Test Claim model
    $claimModel = new Claim();
    echo "✓ Claim model initialized\n\n";
    
    // Test Member model
    $memberModel = new Member();
    echo "✓ Member model initialized\n\n";
    
    // Get a test member
    $members = $memberModel->findAll(['status' => 'active'], 'id DESC', 1);
    if (empty($members)) {
        echo "✗ No active members found\n";
        exit(1);
    }
    
    $member = $members[0];
    echo "✓ Found test member: ID {$member['id']}\n\n";
    
    // Test claim data structure
    $testClaimData = [
        'member_id' => $member['id'],
        'beneficiary_id' => 1, // Assume exists
        'deceased_name' => 'Test Deceased',
        'deceased_id_number' => '12345678',
        'date_of_death' => date('Y-m-d'),
        'place_of_death' => 'Test Hospital',
        'cause_of_death' => 'Natural causes',
        'mortuary_name' => 'Test Mortuary',
        'mortuary_bill_amount' => 5000.00,
        'mortuary_days_count' => 7,
        'service_delivery_type' => 'standard_services'
    ];
    
    echo "Test Claim Data:\n";
    print_r($testClaimData);
    echo "\n";
    
    // Test if fields exist in table
    $columns = $db->fetchAll("DESCRIBE claims");
    $columnNames = array_column($columns, 'Field');
    
    echo "Checking if all fields exist in claims table:\n";
    foreach (array_keys($testClaimData) as $field) {
        $exists = in_array($field, $columnNames);
        echo ($exists ? "✓" : "✗") . " {$field}\n";
    }
    echo "\n";
    
    // Try a test insert (will rollback)
    echo "Testing claim insertion...\n";
    $conn = $db->getConnection();
    $conn->beginTransaction();
    
    try {
        $claimId = $claimModel->submitClaim($testClaimData);
        echo "✓ Test claim inserted successfully with ID: {$claimId}\n";
        
        // Rollback so we don't pollute database
        $conn->rollBack();
        echo "✓ Test transaction rolled back (no data saved)\n\n";
        
    } catch (Exception $e) {
        $conn->rollBack();
        echo "✗ Claim insertion failed: " . $e->getMessage() . "\n";
        echo "Stack trace:\n" . $e->getTraceAsString() . "\n\n";
    }
    
    echo "===========================================\n";
    echo "Debug Test Complete\n";
    echo "===========================================\n";
    
} catch (Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
