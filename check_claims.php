<?php
/**
 * Check claims submitted by John Kamau with ID: SCA20260004
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

try {
    // Initialize database connection
    $memberModel = new Member();
    $claimModel = new Claim();

    // First, find the member by member_number or id_number
    $member = $memberModel->getMemberByNumber('SCA20260004');

    if (!$member) {
        // Try by id_number
        $member = $memberModel->findByNationalId('SCA20260004');
    }

    if (!$member) {
        echo "❌ No member found with ID: SCA20260004\n";
        exit;
    }

    echo "✅ Member found:\n";
    echo "   Name: " . $member['first_name'] . " " . $member['last_name'] . "\n";
    echo "   Member Number: " . $member['member_number'] . "\n";
    echo "   ID Number: " . $member['id_number'] . "\n";
    echo "   Status: " . $member['status'] . "\n";
    echo "   Email: " . $member['email'] . "\n";
    echo "   Phone: " . $member['phone'] . "\n\n";

    // Now get claims for this member
    $claims = $claimModel->getMemberClaims($member['id']);

    if (empty($claims)) {
        echo "❌ No claims found for this member.\n";
        exit;
    }

    echo "📋 Claims submitted by this member:\n";
    echo str_repeat("-", 80) . "\n";
    echo sprintf("%-5s %-15s %-20s %-12s %-10s %-15s\n", "ID", "Claim Number", "Deceased Name", "Status", "Amount", "Submitted");
    echo str_repeat("-", 80) . "\n";

    foreach ($claims as $claim) {
        $claimNumber = isset($claim['claim_number']) ? $claim['claim_number'] : 'N/A';
        $deceasedName = $claim['deceased_name'] ?? 'N/A';
        $status = $claim['status'] ?? 'N/A';
        $amount = isset($claim['claim_amount']) ? number_format($claim['claim_amount'], 2) : 'N/A';
        $submitted = date('Y-m-d', strtotime($claim['created_at']));

        echo sprintf("%-5s %-15s %-20s %-12s %-10s %-15s\n",
            $claim['id'],
            $claimNumber,
            $deceasedName,
            $status,
            $amount,
            $submitted
        );
    }

    echo str_repeat("-", 80) . "\n";
    echo "Total claims: " . count($claims) . "\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
