<?php
// Run this script from the project root with: php clear_test_agent_data.php

$root = __DIR__;
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $root);
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
    // Delete agent commissions
    $db->prepare("DELETE FROM agent_commissions WHERE agent_id = ?")->execute([$agentId]);
    // Set agent total_commission to 0
    $db->prepare("UPDATE agents SET total_commission = 0 WHERE id = ?")->execute([$agentId]);
    // Find all members registered by this agent
    $stmt2 = $db->prepare("SELECT id FROM members WHERE agent_id = ?");
    $stmt2->execute([$agentId]);
    $memberIds = $stmt2->fetchAll(PDO::FETCH_COLUMN);
    if ($memberIds) {
        // Delete all payments, commissions, and the members themselves
        $in = str_repeat('?,', count($memberIds) - 1) . '?';
        $db->prepare("DELETE FROM payments WHERE member_id IN ($in)")->execute($memberIds);
        $db->prepare("DELETE FROM agent_commissions WHERE member_id IN ($in)")->execute($memberIds);
        $db->prepare("DELETE FROM members WHERE id IN ($in)")->execute($memberIds);
    }
    echo "Cleared all data for agent $agentNumber ($agentEmail) and their registered members.\n";
} else {
    echo "Agent not found.\n";
}
