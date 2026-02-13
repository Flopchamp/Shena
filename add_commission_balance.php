<?php
/**
 * Add Commission Balance for Test Agent
 * Creates test commission records for agent@test.com to enable payout testing
 */

define('ROOT_PATH', __DIR__);
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/core/Database.php';

echo "\n========================================\n";
echo "ADD COMMISSION BALANCE FOR TEST AGENT\n";
echo "========================================\n\n";

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();

    // Find test agent
    $testEmail = 'agent@test.com';
    
    // Get user
    $userSql = "SELECT * FROM users WHERE email = ?";
    $userStmt = $pdo->prepare($userSql);
    $userStmt->execute([$testEmail]);
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception("Test agent user not found with email: {$testEmail}");
    }

    echo "Found test user: {$user['first_name']} {$user['last_name']} (ID: {$user['id']})\n";

    // Get agent profile
    $agentSql = "SELECT * FROM agents WHERE user_id = ?";
    $agentStmt = $pdo->prepare($agentSql);
    $agentStmt->execute([$user['id']]);
    $agent = $agentStmt->fetch(PDO::FETCH_ASSOC);

    if (!$agent) {
        throw new Exception("Test agent profile not found for user ID: {$user['id']}");
    }

    echo "Found test agent: {$agent['first_name']} {$agent['last_name']} (ID: {$agent['id']})\n";
    echo "Commission Rate: {$agent['commission_rate']}%\n\n";

    // Get members for this agent
    $membersSql = "SELECT * FROM members WHERE agent_id = ? LIMIT 5";
    $membersStmt = $pdo->prepare($membersSql);
    $membersStmt->execute([$agent['id']]);
    $members = $membersStmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($members)) {
        throw new Exception("No members found for this agent. Please create members first.");
    }

    echo "Found " . count($members) . " members for the agent:\n";
    foreach ($members as $member) {
        echo "  - {$member['member_number']}: {$member['first_name']} {$member['last_name']} (ID: {$member['id']})\n";
    }
    echo "\n";

    // Create test commission records with 'paid' status
    $commissionAmounts = [1500.00, 2000.00, 2500.00, 1800.00, 2200.00];
    $totalAdded = 0;
    $createdCount = 0;

    foreach ($members as $index => $member) {
        $amount = $commissionAmounts[$index % count($commissionAmounts)];
        $commissionRate = $agent['commission_rate'];
        $commissionAmount = $amount * ($commissionRate / 100);

        // Check if commission already exists for this member and type
        $checkSql = "SELECT id FROM agent_commissions 
                     WHERE agent_id = ? AND member_id = ? AND commission_type = 'registration_fee' 
                     AND amount = ?";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute([$agent['id'], $member['id'], $amount]);
        $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // Update existing commission to paid status
            $updateSql = "UPDATE agent_commissions 
                          SET status = 'paid', commission_amount = ?, paid_at = NOW() 
                          WHERE id = ?";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute([$commissionAmount, $existing['id']]);
            echo "  ✓ Updated existing commission for member {$member['member_number']} to 'paid' (KES " . number_format($commissionAmount, 2) . ")\n";
            $totalAdded += $commissionAmount;
            $createdCount++;
        } else {
            // Insert new commission
            $insertSql = "INSERT INTO agent_commissions 
                          (agent_id, member_id, commission_type, amount, commission_rate, commission_amount, status, created_at, paid_at) 
                          VALUES (?, ?, 'registration_fee', ?, ?, ?, 'paid', NOW(), NOW())";
            $insertStmt = $pdo->prepare($insertSql);
            $insertStmt->execute([
                $agent['id'],
                $member['id'],
                $amount,
                $commissionRate,
                $commissionAmount
            ]);
            echo "  ✓ Created commission for member {$member['member_number']}: KES " . number_format($commissionAmount, 2) . " (paid)\n";
            $totalAdded += $commissionAmount;
            $createdCount++;
        }
    }

    echo "\n========================================\n";
    echo "✅ COMMISSION BALANCE ADDED SUCCESSFULLY\n";
    echo "========================================\n\n";

    echo "Summary:\n";
    echo "--------\n";
    echo "Commissions Created/Updated: {$createdCount}\n";
    echo "Total Commission Balance: KES " . number_format($totalAdded, 2) . "\n\n";

    // Verify the balance
    $balanceSql = "SELECT COALESCE(SUM(commission_amount), 0) as total 
                   FROM agent_commissions 
                   WHERE agent_id = ? AND status = 'paid'";
    $balanceStmt = $pdo->prepare($balanceSql);
    $balanceStmt->execute([$agent['id']]);
    $balanceResult = $balanceStmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Verified Available Balance: KES " . number_format($balanceResult['total'], 2) . "\n\n";

    echo "Test Agent Login:\n";
    echo "=================\n";
    echo "Email: agent@test.com\n";
    echo "Password: Agent@123\n";
    echo "URL: http://localhost:8000/login\n\n";

    echo "Payouts Page: http://localhost:8000/agent/payouts\n\n";

    echo "========================================\n";
    echo "Next Steps:\n";
    echo "1. Login as agent at http://localhost:8000/login\n";
    echo "2. Visit payouts page at http://localhost:8000/agent/payouts\n";
    echo "3. Test payout request functionality\n";
    echo "========================================\n\n";

} catch (Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n\n";
    exit(1);
}
