<?php
// Run this script from the project root with: php clear_test_agent_payouts.php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', __DIR__);
}
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/app/core/Database.php';

$db = Database::getInstance()->getConnection();

$agentNumber = 'AG20260007';
$agentEmail = 'agent@test.com';

// Find agent ID
$stmt = $db->prepare("SELECT id FROM agents WHERE agent_number = ? OR email = ? LIMIT 1");
$stmt->execute([$agentNumber, $agentEmail]);
$agent = $stmt->fetch();

if ($agent) {
    $agentId = $agent['id'];
    // Delete payout requests for this agent
    $db->prepare("DELETE FROM payout_requests WHERE agent_id = ?")->execute([$agentId]);
    echo "Cleared all payout records for agent $agentNumber ($agentEmail).\n";
} else {
    echo "Agent not found.\n";
}
