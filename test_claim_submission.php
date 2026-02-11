<?php
/**
 * Test claim submission functionality
 */

// Define ROOT_PATH constant
define('ROOT_PATH', __DIR__);

// Include configuration and autoloader
require_once 'config/config.php';

// Include core classes
require_once 'app/core/Database.php';
require_once 'app/core/BaseModel.php';

// Include models
require_once 'app/models/Member.php';
require_once 'app/models/Claim.php';
require_once 'app/models/Beneficiary.php';
require_once 'app/models/ClaimServiceChecklist.php';
require_once 'app/models/ClaimDocument.php';

try {
    // Initialize database connection
    $memberModel = new Member();
    $claimModel = new Claim();
    $beneficiaryModel = new Beneficiary();

    // Find the test member
    $member = $memberModel->getMemberByNumber('SCA20260004');

    if (!$member) {
        echo "❌ Test member SCA20260004 not found\n";
        exit;
    }

    echo "✅ Found test member: " . $member['first_name'] . " " . $member['last_name'] . "\n";

    // Check if member has beneficiaries
    $beneficiaries = $beneficiaryModel->getMemberBeneficiaries($member['id']);
    echo "📋 Member has " . count($beneficiaries) . " beneficiaries\n";

    if (empty($beneficiaries)) {
        echo "❌ Member has no beneficiaries - claim submission will fail\n";
        echo "Creating a test beneficiary...\n";

        // Create a test beneficiary
        $beneficiaryData = [
            'member_id' => $member['id'],
            'full_name' => 'Test Beneficiary',
            'relationship' => 'Spouse',
            'id_number' => '12345678',
            'phone_number' => '254712345678',
            'percentage' => 100
        ];

        $beneficiaryId = $beneficiaryModel->addBeneficiary($beneficiaryData);
        echo "✅ Created test beneficiary with ID: $beneficiaryId\n";

        $beneficiaries = $beneficiaryModel->getMemberBeneficiaries($member['id']);
    }

    // Test claim data
    $claimData = [
        'member_id' => $member['id'],
        'beneficiary_id' => $beneficiaries[0]['id'],
        'deceased_name' => 'Test Deceased',
        'deceased_id_number' => '87654321',
        'date_of_death' => date('Y-m-d'),
        'place_of_death' => 'Test Hospital',
        'cause_of_death' => 'Natural causes',
        'mortuary_name' => 'Test Mortuary',
        'mortuary_bill_amount' => 15000.00,
        'mortuary_days_count' => 7,
        'service_delivery_type' => 'standard_services'
    ];

    echo "📝 Attempting to submit test claim...\n";

    // Submit claim
    $claimId = $claimModel->submitClaim($claimData);

    if ($claimId) {
        echo "✅ Claim submitted successfully with ID: $claimId\n";

        // Check if claim was created
        $createdClaim = $claimModel->find($claimId);
        if ($createdClaim) {
            echo "✅ Claim verified in database:\n";
            echo "   - Status: " . $createdClaim['status'] . "\n";
            echo "   - Deceased: " . $createdClaim['deceased_name'] . "\n";
            echo "   - Created: " . $createdClaim['created_at'] . "\n";
        } else {
            echo "❌ Claim not found in database after creation\n";
        }
    } else {
        echo "❌ Claim submission failed\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>
