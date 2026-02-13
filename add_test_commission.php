<?php
/**
 * Add Test Commission Data for Agent
 * Creates test commission records for the test agent to enable payout testing
 */

define('ROOT_PATH', __DIR__);
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/core/BaseModel.php';
require_once __DIR__ . '/app/models/User.php';
require_once __DIR__ . '/app/models/Agent.php';
require_once __DIR__ . '/app/models/Member.php';

echo "\n========================================\n";
echo "ADD TEST COMMISSION DATA FOR AGENT\n";
echo "========================================\n\n";

try {
    $db = Database::getInstance()->getConnection();
    $userModel = new User();
    $agentModel = new Agent();
    $memberModel = new Member();

    // Find test agent
    $testEmail = 'agent@test.com';
    $user = $userModel->findByEmail($testEmail);

    if (!$user) {
        throw new Exception("Test agent user not found. Please run create_test_agent.php first.");
    }

    $agent = $agentModel->getAgentByUserId($user['id']);

    if (!$agent) {
        throw new Exception("Test agent profile not found.");
    }

    echo "Found test agent: {$agent['first_name']} {$agent['last_name']} (ID: {$agent['id']})\n\n";

    // Get existing members for the agent
    $existingMembers = $memberModel->getMembersByAgent($agent['id']);

    if (empty($existingMembers)) {
        throw new Exception("No members found for this agent. Please create some members first.");
    }

    $createdMembers = $existingMembers;
    echo "✓ Found " . count($existingMembers) . " existing members for the agent\n";

    // Show which members we found
    foreach ($existingMembers as $member) {
        echo "  - {$member['member_number']}: {$member['first_name']} {$member['last_name']}\n";
    }
    echo "\n";

    echo "\n";

    // Create test commission records
    $commissionData = [
        [
            'agent_id' => $agent['id'],
            'member_id' => $createdMembers[0]['id'],
            'payment_id' => null,
            'commission_type' => 'registration_fee',
            'amount' => 5000.00,
            'commission_rate' => $agent['commission_rate'],
            'commission_amount' => 5000.00 * ($agent['commission_rate'] / 100),
            'status' => 'paid'
        ],
        [
            'agent_id' => $agent['id'],
            'member_id' => $createdMembers[1]['id'],
            'payment_id' => null,
            'commission_type' => 'registration_fee',
            'amount' => 10000.00,
            'commission_rate' => $agent['commission_rate'],
            'commission_amount' => 10000.00 * ($agent['commission_rate'] / 100),
            'status' => 'paid'
        ],
        [
            'agent_id' => $agent['id'],
            'member_id' => $createdMembers[2]['id'],
            'payment_id' => null,
            'commission_type' => 'registration_fee',
            'amount' => 7500.00,
            'commission_rate' => $agent['commission_rate'],
            'commission_amount' => 7500.00 * ($agent['commission_rate'] / 100),
            'status' => 'paid'
        ],
        [
            'agent_id' => $agent['id'],
            'member_id' => $createdMembers[0]['id'],
            'payment_id' => null,
            'commission_type' => 'monthly_contribution',
            'amount' => 2000.00,
            'commission_rate' => $agent['commission_rate'],
            'commission_amount' => 2000.00 * ($agent['commission_rate'] / 100),
            'status' => 'approved'
        ],
        [
            'agent_id' => $agent['id'],
            'member_id' => $createdMembers[1]['id'],
            'payment_id' => null,
            'commission_type' => 'monthly_contribution',
            'amount' => 3000.00,
            'commission_rate' => $agent['commission_rate'],
            'commission_amount' => 3000.00 * ($agent['commission_rate'] / 100),
            'status' => 'pending'
        ]
    ];

    // First, update existing commissions to 'paid' status to ensure agent has balance
    echo "Updating existing commissions to 'paid' status...\n";
    $updateSql = "UPDATE agent_commissions SET status = 'paid', paid_at = NOW() WHERE agent_id = ? AND status IN ('pending', 'approved')";
    $stmt = $db->prepare($updateSql);
    $stmt->execute([$agent['id']]);
    echo "✓ Updated " . $stmt->rowCount() . " existing commissions to 'paid' status\n\n";

    $totalCommission = 0;
    foreach ($commissionData as $commission) {
        // Check if commission already exists (avoid duplicates)
        $existingCommission = $db->query(
            "SELECT id FROM agent_commissions WHERE agent_id = ? AND member_id = ? AND commission_type = ? AND amount = ?",
            [$commission['agent_id'], $commission['member_id'], $commission['commission_type'], $commission['amount']]
        )->fetch();

        if ($existingCommission) {
            echo "✓ Commission already exists for member ID {$commission['member_id']}\n";
            continue;
        }

        // Insert commission
        $commissionId = $agentModel->recordCommission($commission);
        if ($commissionId) {
            echo "✓ Created commission: KES " . number_format($commission['commission_amount'], 2) . " ({$commission['status']})\n";
            if ($commission['status'] === 'paid') {
                $totalCommission += $commission['commission_amount'];
            }
        } else {
            echo "✗ Failed to create commission for member ID {$commission['member_id']}\n";
        }
    }

    echo "\n========================================\n";
    echo "✅ TEST COMMISSION DATA ADDED\n";
    echo "========================================\n\n";

    echo "Commission Summary:\n";
    echo "===================\n";
    echo "Total Paid Commissions: KES " . number_format($totalCommission, 2) . "\n";
    echo "Available Balance: KES " . number_format($totalCommission, 2) . "\n\n";

    echo "Test Agent Login:\n";
    echo "=================\n";
    echo "Email: agent@test.com\n";
    echo "Password: Agent@123\n";
    echo "URL: " . APP_URL . "/login\n\n";

    echo "Payouts Page: " . APP_URL . "/agent/payouts\n\n";

    echo "========================================\n";
    echo "Next Steps:\n";
    echo "1. Login as agent\n";
    echo "2. Visit payouts page\n";
    echo "3. Test payout request functionality\n";
    echo "========================================\n\n";

} catch (Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n\n";
    exit(1);
}
