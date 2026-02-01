<?php
define('ROOT_PATH', __DIR__);
require_once 'config/config.php';
require_once 'app/core/Database.php';

$db = Database::getInstance()->getConnection();

$stmt = $db->query("
    SELECT 
        u.id as user_id,
        u.email,
        u.phone,
        u.first_name,
        u.last_name,
        u.role,
        u.status as user_status,
        m.id as member_id,
        m.member_number,
        m.id_number,
        m.date_of_birth,
        m.gender,
        m.package,
        m.monthly_contribution,
        m.status as member_status,
        m.created_at
    FROM users u
    LEFT JOIN members m ON u.id = m.user_id
    WHERE u.role = 'member'
    ORDER BY m.created_at DESC
    LIMIT 20
");

$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($members)) {
    echo "No members found.\n";
} else {
    echo "Found " . count($members) . " member(s):\n";
    echo str_repeat('=', 120) . "\n";
    
    foreach ($members as $member) {
        echo "User ID: " . $member['user_id'] . "\n";
        echo "Email: " . $member['email'] . "\n";
        echo "Phone: " . $member['phone'] . "\n";
        echo "Name: " . $member['first_name'] . ' ' . $member['last_name'] . "\n";
        echo "Member Number: " . ($member['member_number'] ?? 'N/A') . "\n";
        echo "ID Number: " . ($member['id_number'] ?? 'N/A') . "\n";
        echo "Package: " . ($member['package'] ?? 'N/A') . "\n";
        echo "Monthly Contribution: KES " . ($member['monthly_contribution'] ?? '0') . "\n";
        echo "Member Status: " . ($member['member_status'] ?? 'N/A') . "\n";
        echo "User Status: " . $member['user_status'] . "\n";
        echo "Registered: " . ($member['created_at'] ?? 'N/A') . "\n";
        echo str_repeat('-', 120) . "\n";
    }
}
