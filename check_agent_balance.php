<?php
/**
 * Check Agent Balance and Commission Data
 */

define('ROOT_PATH', __DIR__);
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/core/Database.php';

echo "\n========================================\n";
echo "CHECK AGENT BALANCE AND COMMISSIONS\n";
echo "========================================\n\n";

try {
    $db = Database::getInstance()->getConnection();

    // Check all agents
    $agents = $db->query('SELECT a.*, u.email FROM agents a JOIN users u ON a.user_id = u.id')->fetchAll();

    if (empty($agents)) {
        echo "No agents found in database.\n\n";
        exit;
    }

    echo "Found " . count($agents) . " agents:\n\n";

    foreach ($agents as $agent) {
        echo "Agent: {$agent['first_name']} {$agent['last_name']} (ID: {$agent['id']})\n";
        echo "Email: {$agent['email']}\n";
        echo "Commission Rate: {$agent['commission_rate']}%\n";

        // Check commissions
        $stmt = $db->prepare('SELECT * FROM agent_commissions WHERE agent_id = ?');
        $stmt->execute([$agent['id']]);
        $commissions = $stmt->fetchAll();

        echo "Commissions: " . count($commissions) . "\n";

        $totalPaid = 0;
        $totalPending = 0;
        $totalApproved = 0;

        foreach ($commissions as $comm) {
            if ($comm['status'] === 'paid') {
                $totalPaid += $comm['commission_amount'];
            } elseif ($comm['status'] === 'pending') {
                $totalPending += $comm['commission_amount'];
            } elseif ($comm['status'] === 'approved') {
                $totalApproved += $comm['commission_amount'];
            }
        }

        echo "  - Paid: KES " . number_format($totalPaid, 2) . "\n";
        echo "  - Approved: KES " . number_format($totalApproved, 2) . "\n";
        echo "  - Pending: KES " . number_format($totalPending, 2) . "\n";
        echo "  - Available Balance: KES " . number_format($totalPaid, 2) . "\n\n";
    }

    // Check if test agent exists
    $testUser = $db->query('SELECT * FROM users WHERE email = ?', ['agent@test.com'])->fetch();
    if ($testUser) {
        echo "Test Agent User Found:\n";
        echo "  - User ID: {$testUser['id']}\n";
        echo "  - Name: {$testUser['first_name']} {$testUser['last_name']}\n";
        echo "  - Email: {$testUser['email']}\n";
        echo "  - Role: {$testUser['role']}\n\n";

        $testAgent = $db->query('SELECT * FROM agents WHERE user_id = ?', [$testUser['id']])->fetch();
        if ($testAgent) {
            echo "Test Agent Profile Found:\n";
            echo "  - Agent ID: {$testAgent['id']}\n";
            echo "  - Commission Rate: {$testAgent['commission_rate']}%\n\n";
        } else {
            echo "No agent profile found for test user.\n\n";
        }
    } else {
        echo "Test agent user not found.\n\n";
    }

} catch (Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n\n";
    exit(1);
}
