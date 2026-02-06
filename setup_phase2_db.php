<?php
/**
 * Check and add payment_deadline column if needed
 */

define('ROOT_PATH', __DIR__);
define('CONFIG_PATH', ROOT_PATH . '/config');
define('APP_PATH', ROOT_PATH . '/app');

require_once CONFIG_PATH . '/config.php';
require_once APP_PATH . '/core/Database.php';

$db = Database::getInstance();
$conn = $db->getConnection();

echo "Checking members table structure...\n";

// Check if payment_deadline exists
$result = $conn->query("SHOW COLUMNS FROM members LIKE 'payment_deadline'");
$hasPaymentDeadline = $result->rowCount() > 0;

if (!$hasPaymentDeadline) {
    echo "Adding payment_deadline column...\n";
    $conn->exec("ALTER TABLE members ADD COLUMN payment_deadline DATE NULL");
    echo "✅ payment_deadline column added\n";
} else {
    echo "✅ payment_deadline column already exists\n";
}

// Check if pending_payment_type exists
$result = $conn->query("SHOW COLUMNS FROM members LIKE 'pending_payment_type'");
$hasPendingType = $result->rowCount() > 0;

if (!$hasPendingType) {
    echo "Adding pending_payment_type column...\n";
    $conn->exec("ALTER TABLE members ADD COLUMN pending_payment_type VARCHAR(20) NULL");
    echo "✅ pending_payment_type column added\n";
} else {
    echo "✅ pending_payment_type column already exists\n";
}

echo "\n=== Database Ready for Phase 2 Testing ===\n";
