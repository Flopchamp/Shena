<?php
/**
 * Set member maturity to completed (for testing claims)
 */
define('ROOT_PATH', __DIR__);
define('CONFIG_PATH', ROOT_PATH . '/config');
require_once CONFIG_PATH . '/config.php';
require_once 'app/core/Database.php';

$db = Database::getInstance()->getConnection();

// Set maturity_ends to yesterday for member 5
$yesterday = date('Y-m-d', strtotime('-1 day'));
$stmt = $db->prepare("UPDATE members SET maturity_ends = ? WHERE id = 5");
$success = $stmt->execute([$yesterday]);

if ($success) {
    echo "✓ Member 5 maturity period set to completed\n";
    echo "  Maturity End Date: $yesterday (yesterday)\n\n";
    echo "You can now submit claims for this member!\n";
} else {
    echo "✗ Failed to update maturity period\n";
}
