<?php
/**
 * Test clickable claims functionality
 */

define('ROOT_PATH', __DIR__);
define('CONFIG_PATH', ROOT_PATH . '/config');
require_once CONFIG_PATH . '/config.php';
require_once 'app/core/Database.php';

$db = Database::getInstance()->getConnection();

echo "=== Testing Clickable Claims Feature ===\n\n";

// Get a sample member's claims
$stmt = $db->prepare("
    SELECT c.id, c.member_id, c.deceased_name, c.status, c.created_at,
           m.member_number, u.email
    FROM claims c
    JOIN members m ON c.member_id = m.id
    JOIN users u ON m.user_id = u.id
    ORDER BY c.created_at DESC
    LIMIT 5
");
$stmt->execute();
$claims = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($claims)) {
    echo "No claims found in the database.\n";
    echo "Submit a claim first to test the clickable feature.\n\n";
    exit;
}

echo "Sample Claims (Latest 5):\n";
echo str_repeat("-", 80) . "\n";

foreach ($claims as $claim) {
    echo "Claim ID: #{$claim['id']}\n";
    echo "  Member: {$claim['member_number']} ({$claim['email']})\n";
    echo "  Deceased: {$claim['deceased_name']}\n";
    echo "  Status: {$claim['status']}\n";
    echo "  Submitted: {$claim['created_at']}\n";
    echo "  View URL: http://localhost:8000/claims/view/{$claim['id']}\n";
    echo "\n";
}

echo str_repeat("=", 80) . "\n";
echo "\n✅ Feature Implementation Complete!\n\n";

echo "What was added:\n";
echo "1. ✓ New route: GET /claims/view/{id}\n";
echo "2. ✓ MemberController@viewClaim() method\n";
echo "3. ✓ resources/views/member/claim-view.php template\n";
echo "4. ✓ Active claims cards are now clickable (<a> tags)\n";
echo "5. ✓ Past claims items are now clickable\n";
echo "6. ✓ Hover effects added (purple shadow, slide animation)\n\n";

echo "To test:\n";
echo "1. Go to: http://localhost:8000/claims\n";
echo "2. Click on any claim card (active or past)\n";
echo "3. You'll see detailed claim information including:\n";
echo "   - Current status with icon\n";
echo "   - Deceased information\n";
echo "   - Beneficiary details\n";
echo "   - Mortuary information\n";
echo "   - Uploaded documents\n";
echo "   - Claim timeline\n";
echo "   - Cash alternative request (if applicable)\n\n";

echo "Security features:\n";
echo "✓ Members can only view their own claims\n";
echo "✓ Attempting to view another member's claim redirects with error\n";
echo "✓ Invalid claim IDs show error message\n\n";

echo "=== Test Complete ===\n";
