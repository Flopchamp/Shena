<?php
define('ROOT_PATH', __DIR__);
require_once 'config/config.php';
require_once 'app/core/Database.php';

$db = Database::getInstance()->getConnection();

// Update the agent's total_commission field
$updateSql = "UPDATE agents SET total_commission = (
    SELECT COALESCE(SUM(commission_amount), 0) 
    FROM agent_commissions 
    WHERE agent_id = ? AND status = ?
) WHERE id = ?";
$stmt = $db->prepare($updateSql);
$stmt->execute([9, 'paid', 9]);

echo "Updated agent total_commission\n";

// Verify the update
$stmt = $db->prepare("SELECT total_commission FROM agents WHERE id = 9");
$stmt->execute();
$agent = $stmt->fetch();
echo "New Total Commission: KES " . number_format($agent['total_commission'], 2) . "\n";
