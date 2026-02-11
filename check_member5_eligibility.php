<?php
define('ROOT_PATH', __DIR__);
define('CONFIG_PATH', ROOT_PATH . '/config');
require_once CONFIG_PATH . '/config.php';
require_once 'app/core/Database.php';

$db = Database::getInstance()->getConnection();
$stmt = $db->prepare('SELECT id, member_number, status, maturity_ends FROM members WHERE id = 5');
$stmt->execute();
$m = $stmt->fetch(PDO::FETCH_ASSOC);

if ($m) {
    echo "Member 5 Details:\n";
    echo "  Number: {$m['member_number']}\n";
    echo "  Status: {$m['status']}\n";
    echo "  Maturity Ends: " . ($m['maturity_ends'] ?? 'NULL') . "\n\n";
    
    if ($m['maturity_ends']) {
        $mat = new DateTime($m['maturity_ends']);
        $now = new DateTime();
        
        echo "Eligibility Check:\n";
        if ($now < $mat) {
            $days = $now->diff($mat)->days;
            echo "  ✗ BLOCKED - Maturity period not complete\n";
            echo "  Current Date: " . $now->format('Y-m-d') . "\n";
            echo "  Maturity Date: " . $mat->format('Y-m-d') . "\n";
            echo "  Days Remaining: $days\n";
        } else {
            echo "  ✓ ELIGIBLE - Maturity period complete\n";
        }
    } else {
        echo "  ✓ ELIGIBLE - No maturity period set\n";
    }
} else {
    echo "Member 5 not found\n";
}
