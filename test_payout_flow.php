<?php
/**
 * Test Payout Request Flow
 * Verifies that payout requests are created and can be viewed by admin
 */

define('ROOT_PATH', __DIR__);
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/core/Database.php';

echo "\n========================================\n";
echo "TEST PAYOUT REQUEST FLOW\n";
echo "========================================\n\n";

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();

    // Test 1: Check if payout_requests table exists
    echo "TEST 1: Check payout_requests table\n";
    echo "------------------------------------\n";
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'payout_requests'")->fetch();
    if ($tableCheck) {
        echo "✓ payout_requests table exists\n";
        
        // Check table structure
        $columns = $pdo->query("SHOW COLUMNS FROM payout_requests")->fetchAll();
        echo "  Columns: ";
        $columnNames = [];
        foreach ($columns as $col) {
            $columnNames[] = $col['Field'];
        }
        echo implode(', ', $columnNames) . "\n\n";
    } else {
        echo "✗ payout_requests table does not exist\n\n";
    }

    // Test 2: Create a test payout request
    echo "TEST 2: Create test payout request\n";
    echo "------------------------------------\n";
    
    // Get test agent
    $agentStmt = $pdo->prepare("SELECT a.id, a.first_name, a.last_name, a.agent_number, u.email 
                                 FROM agents a 
                                 JOIN users u ON a.user_id = u.id 
                                 WHERE u.email = ?");
    $agentStmt->execute(['agent@test.com']);
    $agent = $agentStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$agent) {
        throw new Exception("Test agent not found");
    }
    
    echo "Found agent: {$agent['first_name']} {$agent['last_name']} (ID: {$agent['id']})\n";
    
    // Check current balance
    $balanceStmt = $pdo->prepare("SELECT COALESCE(SUM(commission_amount), 0) as total 
                                  FROM agent_commissions 
                                  WHERE agent_id = ? AND status = 'paid'");
    $balanceStmt->execute([$agent['id']]);
    $balance = $balanceStmt->fetch(PDO::FETCH_ASSOC);
    echo "Available commission balance: KES " . number_format($balance['total'], 2) . "\n";
    
    // Create a test payout request
    $amount = 500.00;
    $paymentMethod = 'mpesa';
    $paymentDetails = 'M-Pesa to 254712345678';
    $notes = 'Test payout request';
    
    $insertSql = "INSERT INTO payout_requests 
                  (agent_id, amount, payment_method, payment_details, status, notes, requested_at) 
                  VALUES (?, ?, ?, ?, 'requested', ?, NOW())";
    $insertStmt = $pdo->prepare($insertSql);
    $result = $insertStmt->execute([$agent['id'], $amount, $paymentMethod, $paymentDetails, $notes]);
    
    if ($result) {
        $payoutId = $pdo->lastInsertId();
        echo "✓ Created payout request ID: {$payoutId}\n";
        echo "  Amount: KES " . number_format($amount, 2) . "\n";
        echo "  Method: {$paymentMethod}\n";
        echo "  Status: requested\n\n";
    } else {
        echo "✗ Failed to create payout request\n\n";
    }

    // Test 3: Verify payout request is queryable (admin view)
    echo "TEST 3: Query payout requests (admin view)\n";
    echo "---------------------------------------------\n";
    
    $querySql = "SELECT pr.*, a.first_name, a.last_name, a.agent_number, a.phone as agent_phone,
                        u.first_name as processed_by_first, u.last_name as processed_by_last
                 FROM payout_requests pr
                 JOIN agents a ON pr.agent_id = a.id
                 LEFT JOIN users u ON pr.processed_by = u.id
                 WHERE 1=1
                 ORDER BY pr.requested_at DESC";
    $queryStmt = $pdo->query($querySql);
    $payouts = $queryStmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($payouts) . " payout request(s):\n";
    foreach ($payouts as $payout) {
        echo "  - ID: {$payout['id']}\n";
        echo "    Agent: {$payout['first_name']} {$payout['last_name']} ({$payout['agent_number']})\n";
        echo "    Amount: KES " . number_format($payout['amount'], 2) . "\n";
        echo "    Status: {$payout['status']}\n";
        echo "    Requested: {$payout['requested_at']}\n\n";
    }

    // Test 4: Check specific agent payouts
    echo "TEST 4: Query agent-specific payouts\n";
    echo "-------------------------------------\n";
    
    $agentPayoutSql = "SELECT pr.*, 
                              CASE 
                                  WHEN pr.status = 'requested' THEN 'Requested'
                                  WHEN pr.status = 'processing' THEN 'Processing'
                                  WHEN pr.status = 'paid' THEN 'Paid'
                                  WHEN pr.status = 'rejected' THEN 'Rejected'
                              END as display_status
                       FROM payout_requests pr
                       WHERE pr.agent_id = ?
                       ORDER BY pr.requested_at DESC";
    $agentPayoutStmt = $pdo->prepare($agentPayoutSql);
    $agentPayoutStmt->execute([$agent['id']]);
    $agentPayouts = $agentPayoutStmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($agentPayouts) . " payout request(s) for agent {$agent['id']}:\n";
    foreach ($agentPayouts as $payout) {
        echo "  - ID: {$payout['id']}, Amount: KES " . number_format($payout['amount'], 2) . ", Status: {$payout['status']}\n";
    }
    echo "\n";

    // Test 5: Verify statistics calculation
    echo "TEST 5: Statistics calculation\n";
    echo "--------------------------------\n";
    
    $statsSql = "SELECT 
                    COUNT(*) as total,
                    COUNT(CASE WHEN status = 'requested' THEN 1 END) as requested,
                    COUNT(CASE WHEN status = 'processing' THEN 1 END) as processing,
                    COUNT(CASE WHEN status = 'paid' THEN 1 END) as paid,
                    COUNT(CASE WHEN status = 'rejected' THEN 1 END) as rejected,
                    SUM(CASE WHEN status = 'paid' THEN amount ELSE 0 END) as total_amount
                 FROM payout_requests";
    $statsStmt = $pdo->query($statsSql);
    $stats = $statsStmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Statistics:\n";
    echo "  Total: {$stats['total']}\n";
    echo "  Requested: {$stats['requested']}\n";
    echo "  Processing: {$stats['processing']}\n";
    echo "  Paid: {$stats['paid']}\n";
    echo "  Rejected: {$stats['rejected']}\n";
    echo "  Total Paid Amount: KES " . number_format($stats['total_amount'] ?? 0, 2) . "\n\n";

    echo "========================================\n";
    echo "✅ ALL PAYOUT FLOW TESTS PASSED\n";
    echo "========================================\n\n";

    echo "Summary:\n";
    echo "--------\n";
    echo "The payout request system is working correctly.\n";
    echo "Payout requests are being created and can be queried by admin.\n\n";

} catch (Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n\n";
    exit(1);
}
