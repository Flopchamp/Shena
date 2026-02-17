<?php
require_once 'config/config.php';

$db = Database::getInstance()->getConnection();

echo "=== COMMUNICATIONS TABLE ===\n";
$stmt = $db->query('SELECT COUNT(*) as count FROM communications');
$result = $stmt->fetch(PDO::FETCH_ASSOC);
echo "Total communications: " . $result['count'] . "\n";

echo "\n=== COMMUNICATION_RECIPIENTS TABLE ===\n";
$stmt = $db->query('SELECT COUNT(*) as count FROM communication_recipients');
$result = $stmt->fetch(PDO::FETCH_ASSOC);
echo "Total recipients: " . $result['count'] . "\n";

echo "\n=== SAMPLE COMMUNICATIONS ===\n";
$stmt = $db->query('SELECT id, subject, message, type, created_at FROM communications ORDER BY created_at DESC LIMIT 5');
$comms = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($comms as $comm) {
    echo "ID: " . $comm['id'] . " | Subject: " . $comm['subject'] . " | Type: " . $comm['type'] . " | Created: " . $comm['created_at'] . "\n";
}

echo "\n=== SAMPLE RECIPIENTS ===\n";
$stmt = $db->query('SELECT cr.id, cr.user_id, cr.status, c.subject FROM communication_recipients cr JOIN communications c ON cr.communication_id = c.id ORDER BY cr.id DESC LIMIT 5');
$recipients = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($recipients as $rec) {
    echo "Recipient ID: " . $rec['id'] . " | User ID: " . $rec['user_id'] . " | Status: " . $rec['status'] . " | Subject: " . $rec['subject'] . "\n";
}

echo "\n=== AGENTS TABLE ===\n";
$stmt = $db->query('SELECT id, user_id, agent_number, status FROM agents LIMIT 5');
$agents = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($agents as $agent) {
    echo "Agent ID: " . $agent['id'] . " | User ID: " . $agent['user_id'] . " | Agent Number: " . $agent['agent_number'] . " | Status: " . $agent['status'] . "\n";
}

echo "\n=== USERS WITH ROLE AGENT ===\n";
$stmt = $db->query('SELECT id, first_name, last_name, email, role FROM users WHERE role = "agent" LIMIT 5');
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($users as $user) {
    echo "User ID: " . $user['id'] . " | Name: " . $user['first_name'] . " " . $user['last_name'] . " | Email: " . $user['email'] . " | Role: " . $user['role'] . "\n";
}
