<?php
define('ROOT_PATH', __DIR__);
require_once 'config/config.php';
require_once 'app/core/Database.php';

$db = Database::getInstance()->getConnection();

// Get member details
echo "=== MEMBER LOGIN CREDENTIALS ===\n\n";
$stmt = $db->query("
    SELECT 
        u.email,
        u.phone,
        u.first_name,
        u.last_name,
        u.password as password_hash,
        m.member_number,
        m.status
    FROM users u
    JOIN members m ON u.id = m.user_id
    WHERE u.role = 'member'
    LIMIT 1
");

$member = $stmt->fetch(PDO::FETCH_ASSOC);

if ($member) {
    echo "Name: " . $member['first_name'] . ' ' . $member['last_name'] . "\n";
    echo "Member Number: " . $member['member_number'] . "\n";
    echo "Email: " . $member['email'] . "\n";
    echo "Phone: " . $member['phone'] . "\n";
    echo "Status: " . $member['status'] . "\n";
    echo "Password Hash: " . substr($member['password_hash'], 0, 50) . "...\n";
    echo "\nNote: Password is hashed for security. Use password reset if needed.\n";
} else {
    echo "No members found.\n";
}

// Get agent details
echo "\n\n=== AGENT LOGIN CREDENTIALS ===\n\n";
$stmt = $db->query("
    SELECT 
        u.email,
        u.phone,
        u.first_name,
        u.last_name,
        u.password as password_hash,
        a.agent_number,
        a.status,
        a.commission_rate
    FROM users u
    JOIN agents a ON u.id = a.user_id
    LIMIT 1
");

$agent = $stmt->fetch(PDO::FETCH_ASSOC);

if ($agent) {
    echo "Name: " . $agent['first_name'] . ' ' . $agent['last_name'] . "\n";
    echo "Agent Number: " . $agent['agent_number'] . "\n";
    echo "Email: " . $agent['email'] . "\n";
    echo "Phone: " . $agent['phone'] . "\n";
    echo "Status: " . $agent['status'] . "\n";
    echo "Commission Rate: " . $agent['commission_rate'] . "%\n";
    echo "Password Hash: " . substr($agent['password_hash'], 0, 50) . "...\n";
    echo "\nNote: Password is hashed for security. Use password reset if needed.\n";
} else {
    echo "No agents found.\n";
}
