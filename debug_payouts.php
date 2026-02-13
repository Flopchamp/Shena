<?php
define('ROOT_PATH', __DIR__);
require_once 'config/config.php';
require_once 'app/core/Database.php';

$db = Database::getInstance()->getConnection();

// Check payout_requests table
echo "=== Payout Requests ===\n";
$stmt = $db->query('SELECT * FROM payout_requests ORDER BY id DESC');
$payouts = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo 'Total: ' . count($payouts) . "\n";
foreach($payouts as $p) {
    echo "ID: {$p['id']}, AgentID: {$p['agent_id']}, Amount: {$p['amount']}, Status: {$p['status']}\n";
}

// Check agent_commissions table
echo "\n=== Agent Commissions ===\n";
$stmt = $db->query('SELECT * FROM agent_commissions ORDER BY id DESC LIMIT 5');
$comms = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($comms as $c) {
    echo "ID: {$c['id']}, AgentID: {$c['agent_id']}, Amount: {$c['commission_amount']}, Status: {$c['status']}\n";
}

// Get available balance calculation
echo "\n=== Available Balance Calculation ===\n";
$agentId = 9;
$stmt = $db->prepare("SELECT COALESCE(SUM(commission_amount), 0) as total FROM agent_commissions WHERE agent_id = ? AND status = 'paid'");
$stmt->execute([$agentId]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
echo "Total Paid Commissions: {$result['total']}\n";

$stmt = $db->prepare("SELECT COALESCE(SUM(amount), 0) as total FROM payout_requests WHERE agent_id = ? AND status IN ('processing', 'paid')");
$stmt->execute([$agentId]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
echo "Total Paid Out: {$result['total']}\n";

echo "Available Balance: " . ($result['total'] - $result['total']) . "\n";
