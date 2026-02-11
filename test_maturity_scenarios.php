<?php
/**
 * Test different maturity period scenarios
 */
define('ROOT_PATH', __DIR__);
define('CONFIG_PATH', ROOT_PATH . '/config');
require_once CONFIG_PATH . '/config.php';
require_once 'app/core/Database.php';

$db = Database::getInstance()->getConnection();

echo "=== Maturity Period Scenarios ===\n\n";

// Get all members with their maturity status
$stmt = $db->prepare("
    SELECT m.id, m.member_number, m.status, m.maturity_ends, u.email
    FROM members m
    LEFT JOIN users u ON m.user_id = u.id
    ORDER BY m.id
    LIMIT 10
");
$stmt->execute();
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

$today = new DateTime();

foreach ($members as $member) {
    echo "Member #{$member['id']} ({$member['member_number']}) - {$member['email']}\n";
    echo "  Status: {$member['status']}\n";
    
    if (!empty($member['maturity_ends'])) {
        $maturityDate = new DateTime($member['maturity_ends']);
        $maturityFormatted = $maturityDate->format('F j, Y');
        
        if ($today < $maturityDate) {
            $daysRemaining = $today->diff($maturityDate)->days;
            echo "  Maturity: ✗ IN MATURITY PERIOD\n";
            echo "  End Date: {$maturityFormatted}\n";
            echo "  Days Remaining: {$daysRemaining}\n";
            echo "  Can Submit Claims: NO\n";
        } else {
            echo "  Maturity: ✓ COMPLETED\n";
            echo "  Completed On: {$maturityFormatted}\n";
            echo "  Can Submit Claims: YES\n";
        }
    } else {
        echo "  Maturity: ✓ NO MATURITY PERIOD\n";
        echo "  Can Submit Claims: YES\n";
    }
    echo "\n";
}

echo "=== Test Complete ===\n";
