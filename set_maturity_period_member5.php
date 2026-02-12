<?php
/**
 * Set member 5 to maturity period (for testing the UI warning)
 */
define('ROOT_PATH', __DIR__);
define('CONFIG_PATH', ROOT_PATH . '/config');
require_once CONFIG_PATH . '/config.php';
require_once 'app/core/Database.php';

$db = Database::getInstance()->getConnection();

// Set maturity to 30 days from now
$futureDate = date('Y-m-d', strtotime('+30 days'));
$stmt = $db->prepare("UPDATE members SET maturity_ends = ? WHERE id = 5");
$success = $stmt->execute([$futureDate]);

if ($success) {
    echo "✓ Member 5 maturity period set to IN PROGRESS\n";
    echo "  Maturity End Date: $futureDate (30 days from now)\n\n";
    echo "Now login as member 5 and visit /claims to see the warning message!\n";
    echo "\nTo restore eligibility, run: php complete_maturity_member5.php\n";
} else {
    echo "✗ Failed to update maturity period\n";
}
