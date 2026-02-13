<?php
define('ROOT_PATH', __DIR__);
require_once 'config/config.php';
require_once 'app/core/Database.php';

$db = Database::getInstance()->getConnection();

// Check total paid commission for agent ID 9
$stmt = $db->prepare('SELECT SUM(commission_amount) as total FROM agent_commissions WHERE agent_id = 9 AND status = ?');
$stmt->execute(['paid']);
$result = $stmt->fetch();

echo "========================================\n";
echo "AGENT COMMISSION BALANCE CHECK\n";
echo "========================================\n\n";
echo "Agent ID: 9 (agent@test.com)\n";
echo "Total Paid Commission: KES " . number_format($result['total'] ?? 0, 2) . "\n\n";

// Also check all commissions
$stmt = $db->prepare('SELECT * FROM agent_commissions WHERE agent_id = 9');
$stmt->execute();
$commissions = $stmt->fetchAll();

echo "All Commissions:\n";
echo "----------------\n";
foreach ($commissions as $comm) {
    echo "ID: {$comm['id']}, Type: {$comm['commission_type']}, Amount: KES " . number_format($comm['commission_amount'], 2) . ", Status: {$comm['status']}\n";
}
echo "\n";
