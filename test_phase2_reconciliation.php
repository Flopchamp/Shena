<?php
/**
 * Phase 2: Payment Auto-Reconciliation Test Script
 * Tests all reconciliation functionality
 */

// Define ROOT_PATH before including config
define('ROOT_PATH', __DIR__);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/core/BaseModel.php';
require_once __DIR__ . '/app/models/Member.php';
require_once __DIR__ . '/app/models/Payment.php';
require_once __DIR__ . '/app/services/PaymentReconciliationService.php';

class Phase2Test
{
    private $db;
    private $reconciliationService;
    private $passed = 0;
    private $failed = 0;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->reconciliationService = new PaymentReconciliationService();
    }
    
    public function runAllTests()
    {
        echo "===============================================\n";
        echo "   PHASE 2: PAYMENT RECONCILIATION TESTS\n";
        echo "===============================================\n\n";
        
        // Database structure tests
        $this->testDatabaseStructure();
        
        // Service class tests
        $this->testServiceClassExists();
        $this->testServiceMethods();
        
        // Reconciliation logic tests
        $this->testAutoReconciliationByIdNumber();
        $this->testAutoReconciliationByPhone();
        $this->testUnmatchedPaymentCreation();
        $this->testManualReconciliation();
        $this->testPotentialMatches();
        
        // C2B callback tests
        $this->testC2BCallbackProcessing();
        $this->testDuplicateCallbackHandling();
        
        // Statistics tests
        $this->testReconciliationStats();
        
        // Utility function tests
        $this->testPhoneNumberFormatting();
        $this->testTransTimeParser();
        
        echo "\n===============================================\n";
        echo "   TEST SUMMARY\n";
        echo "===============================================\n";
        echo "Passed: " . $this->passed . "\n";
        echo "Failed: " . $this->failed . "\n";
        echo "Total:  " . ($this->passed + $this->failed) . "\n";
        echo "===============================================\n";
        
        return $this->failed === 0;
    }
    
    private function testDatabaseStructure()
    {
        echo "Testing Database Structure...\n";
        
        // Test payments table columns
        $columns = [
            'reconciliation_status',
            'mpesa_receipt_number',
            'transaction_date',
            'sender_phone',
            'sender_name',
            'paybill_account',
            'reconciled_at',
            'reconciled_by',
            'reconciliation_notes',
            'auto_matched'
        ];
        
        foreach ($columns as $column) {
            $query = "SHOW COLUMNS FROM payments LIKE '$column'";
            $result = $this->db->fetch($query);
            $this->assert($result !== false, "payments.$column column exists");
        }
        
        // Test mpesa_c2b_callbacks table
        $query = "SHOW TABLES LIKE 'mpesa_c2b_callbacks'";
        $result = $this->db->fetch($query);
        $this->assert($result !== false, "mpesa_c2b_callbacks table exists");
        
        // Test payment_reconciliation_log table
        $query = "SHOW TABLES LIKE 'payment_reconciliation_log'";
        $result = $this->db->fetch($query);
        $this->assert($result !== false, "payment_reconciliation_log table exists");
        
        // Test views
        $query = "SHOW TABLES LIKE 'vw_unmatched_payments'";
        $result = $this->db->fetch($query);
        $this->assert($result !== false, "vw_unmatched_payments view exists");
        
        $query = "SHOW TABLES LIKE 'vw_pending_reconciliation'";
        $result = $this->db->fetch($query);
        $this->assert($result !== false, "vw_pending_reconciliation view exists");
        
        echo "\n";
    }
    
    private function testServiceClassExists()
    {
        echo "Testing Service Class...\n";
        
        $this->assert(
            class_exists('PaymentReconciliationService'),
            "PaymentReconciliationService class exists"
        );
        
        $this->assert(
            is_object($this->reconciliationService),
            "PaymentReconciliationService can be instantiated"
        );
        
        echo "\n";
    }
    
    private function testServiceMethods()
    {
        echo "Testing Service Methods...\n";
        
        $methods = [
            'processC2BCallback',
            'autoReconcilePayment',
            'manualReconciliation',
            'getUnmatchedPayments',
            'findPotentialMatches',
            'getReconciliationStats'
        ];
        
        foreach ($methods as $method) {
            $this->assert(
                method_exists($this->reconciliationService, $method),
                "Method $method exists"
            );
        }
        
        echo "\n";
    }
    
    private function testAutoReconciliationByIdNumber()
    {
        echo "Testing Auto-Reconciliation by ID Number...\n";
        
        try {
            // Get a test member
            $member = $this->db->fetch("SELECT * FROM members LIMIT 1");
            
            if (!$member) {
                echo "  ⚠️  No test members available, skipping...\n\n";
                return;
            }
            
            // Simulate payment with member's ID number
            $paymentData = [
                'trans_id' => 'TEST_' . time(),
                'amount' => 500,
                'transaction_date' => date('Y-m-d H:i:s'),
                'sender_phone' => '+254712345678',
                'sender_name' => 'Test Sender',
                'bill_ref_number' => $member['id_number']
            ];
            
            $result = $this->reconciliationService->autoReconcilePayment($paymentData);
            
            $this->assert($result['success'] === true, "Auto-reconciliation executed");
            $this->assert($result['matched'] === true, "Payment matched by ID number");
            $this->assert($result['member_id'] == $member['id'], "Matched correct member");
            $this->assert($result['match_method'] === 'auto_id_number', "Match method is auto_id_number");
            $this->assert($result['confidence'] >= 90, "High confidence match");
            
            // Clean up
            if (isset($result['payment_id'])) {
                $this->db->execute("DELETE FROM payments WHERE id = :id", ['id' => $result['payment_id']]);
            }
            
        } catch (Exception $e) {
            $this->assert(false, "Auto-reconciliation by ID: " . $e->getMessage());
        }
        
        echo "\n";
    }
    
    private function testAutoReconciliationByPhone()
    {
        echo "Testing Auto-Reconciliation by Phone...\n";
        
        try {
            // Get a test member with phone
            $member = $this->db->fetch("SELECT m.*, u.phone FROM members m JOIN users u ON m.user_id = u.id WHERE u.phone IS NOT NULL LIMIT 1");
            
            if (!$member) {
                echo "  ⚠️  No test members with phone available, skipping...\n\n";
                return;
            }
            
            // Simulate payment with member's phone (no ID number)
            $paymentData = [
                'trans_id' => 'TEST_' . time() . '_PHONE',
                'amount' => 500,
                'transaction_date' => date('Y-m-d H:i:s'),
                'sender_phone' => $member['phone'],
                'sender_name' => 'Test Sender',
                'bill_ref_number' => 'UNKNOWN'
            ];
            
            $result = $this->reconciliationService->autoReconcilePayment($paymentData);
            
            $this->assert($result['success'] === true, "Auto-reconciliation executed");
            $this->assert($result['matched'] === true, "Payment matched by phone");
            $this->assert($result['member_id'] == $member['id'], "Matched correct member");
            $this->assert($result['match_method'] === 'auto_phone', "Match method is auto_phone");
            
            // Clean up
            if (isset($result['payment_id'])) {
                $this->db->execute("DELETE FROM payments WHERE id = :id", ['id' => $result['payment_id']]);
            }
            
        } catch (Exception $e) {
            $this->assert(false, "Auto-reconciliation by phone: " . $e->getMessage());
        }
        
        echo "\n";
    }
    
    private function testUnmatchedPaymentCreation()
    {
        echo "Testing Unmatched Payment Creation...\n";
        
        try {
            // Simulate payment with no matching member
            $paymentData = [
                'trans_id' => 'TEST_UNMATCHED_' . time(),
                'amount' => 500,
                'transaction_date' => date('Y-m-d H:i:s'),
                'sender_phone' => '+254799999999',
                'sender_name' => 'Unknown Person',
                'bill_ref_number' => '99999999'
            ];
            
            $result = $this->reconciliationService->autoReconcilePayment($paymentData);
            
            $this->assert($result['success'] === true, "Unmatched payment processing succeeded");
            $this->assert($result['matched'] === false, "Payment marked as unmatched");
            $this->assert(isset($result['payment_id']), "Payment ID returned");
            
            // Verify payment was created as unmatched
            if (isset($result['payment_id'])) {
                $payment = $this->db->fetch(
                    "SELECT * FROM payments WHERE id = :id",
                    ['id' => $result['payment_id']]
                );
                
                $this->assert($payment !== false, "Payment record created");
                $this->assert($payment['reconciliation_status'] === 'unmatched', "Status is unmatched");
                $this->assert($payment['member_id'] === null, "No member linked");
                
                // Clean up
                $this->db->execute("DELETE FROM payments WHERE id = :id", ['id' => $result['payment_id']]);
            }
            
        } catch (Exception $e) {
            $this->assert(false, "Unmatched payment creation: " . $e->getMessage());
        }
        
        echo "\n";
    }
    
    private function testManualReconciliation()
    {
        echo "Testing Manual Reconciliation...\n";
        
        try {
            // Create an unmatched payment
            $paymentData = [
                'trans_id' => 'TEST_MANUAL_' . time(),
                'amount' => 500,
                'transaction_date' => date('Y-m-d H:i:s'),
                'sender_phone' => '+254799999998',
                'sender_name' => 'Manual Test',
                'bill_ref_number' => '88888888'
            ];
            
            $result = $this->reconciliationService->autoReconcilePayment($paymentData);
            $paymentId = $result['payment_id'] ?? 0;
            
            // Get a test member
            $member = $this->db->fetch("SELECT * FROM members LIMIT 1");
            
            if ($member && $paymentId) {
                // Manually reconcile
                $success = $this->reconciliationService->manualReconciliation(
                    $paymentId,
                    $member['id'],
                    1, // User ID
                    'Manual test reconciliation'
                );
                
                $this->assert($success === true, "Manual reconciliation succeeded");
                
                // Verify payment was updated
                $payment = $this->db->fetch(
                    "SELECT * FROM payments WHERE id = :id",
                    ['id' => $paymentId]
                );
                
                $this->assert($payment['member_id'] == $member['id'], "Member linked");
                $this->assert($payment['reconciliation_status'] === 'manual', "Status is manual");
                $this->assert($payment['reconciled_at'] !== null, "Reconciled timestamp set");
                $this->assert($payment['reconciled_by'] == 1, "Reconciled by user recorded");
                
                // Verify log entry
                $log = $this->db->fetch(
                    "SELECT * FROM payment_reconciliation_log WHERE payment_id = :id",
                    ['id' => $paymentId]
                );
                
                $this->assert($log !== false, "Reconciliation log entry created");
                $this->assert($log['action'] === 'manual_match', "Log action is manual_match");
                
                // Clean up
                $this->db->execute("DELETE FROM payment_reconciliation_log WHERE payment_id = :id", ['id' => $paymentId]);
                $this->db->execute("DELETE FROM payments WHERE id = :id", ['id' => $paymentId]);
            }
            
        } catch (Exception $e) {
            $this->assert(false, "Manual reconciliation: " . $e->getMessage());
        }
        
        echo "\n";
    }
    
    private function testPotentialMatches()
    {
        echo "Testing Potential Matches Finding...\n";
        
        try {
            // Create unmatched payment with member's phone
            $member = $this->db->fetch("SELECT m.*, u.phone FROM members m JOIN users u ON m.user_id = u.id WHERE u.phone IS NOT NULL LIMIT 1");
            
            if (!$member) {
                echo "  ⚠️  No test members available, skipping...\n\n";
                return;
            }
            
            $paymentData = [
                'trans_id' => 'TEST_MATCH_' . time(),
                'amount' => 500,
                'transaction_date' => date('Y-m-d H:i:s'),
                'sender_phone' => $member['phone'],
                'sender_name' => $member['first_name'] . ' ' . $member['last_name'],
                'bill_ref_number' => 'WRONG_NUMBER'
            ];
            
            $result = $this->reconciliationService->autoReconcilePayment($paymentData);
            $paymentId = $result['payment_id'] ?? 0;
            
            if ($paymentId) {
                // Find potential matches
                $matches = $this->reconciliationService->findPotentialMatches($paymentId);
                
                $this->assert(is_array($matches), "Potential matches returned as array");
                $this->assert(count($matches) > 0, "At least one potential match found");
                
                // Verify member is in matches
                $foundMember = false;
                foreach ($matches as $match) {
                    if ($match['id'] == $member['id']) {
                        $foundMember = true;
                        $this->assert($match['confidence'] > 0, "Match has confidence score");
                        break;
                    }
                }
                
                $this->assert($foundMember, "Correct member found in potential matches");
                
                // Clean up
                $this->db->execute("DELETE FROM payments WHERE id = :id", ['id' => $paymentId]);
            }
            
        } catch (Exception $e) {
            $this->assert(false, "Potential matches finding: " . $e->getMessage());
        }
        
        echo "\n";
    }
    
    private function testC2BCallbackProcessing()
    {
        echo "Testing C2B Callback Processing...\n";
        
        try {
            $member = $this->db->fetch("SELECT * FROM members LIMIT 1");
            
            if (!$member) {
                echo "  ⚠️  No test members available, skipping...\n\n";
                return;
            }
            
            // Simulate M-Pesa C2B callback data
            $callbackData = [
                'TransactionType' => 'Pay Bill',
                'TransID' => 'TEST_C2B_' . time(),
                'TransTime' => date('YmdHis'),
                'TransAmount' => '500.00',
                'BusinessShortCode' => '4163987',
                'BillRefNumber' => $member['id_number'],
                'InvoiceNumber' => '',
                'OrgAccountBalance' => '50000.00',
                'ThirdPartyTransID' => '',
                'MSISDN' => '254712345678',
                'FirstName' => 'John',
                'MiddleName' => 'Doe',
                'LastName' => 'Smith'
            ];
            
            $result = $this->reconciliationService->processC2BCallback($callbackData);
            
            $this->assert($result['success'] === true, "C2B callback processed successfully");
            $this->assert($result['matched'] === true, "Callback payment matched");
            
            // Verify callback was stored
            $callback = $this->db->fetch(
                "SELECT * FROM mpesa_c2b_callbacks WHERE trans_id = :trans_id",
                ['trans_id' => $callbackData['TransID']]
            );
            
            $this->assert($callback !== false, "Callback stored in database");
            $this->assert($callback['processed'] == 1, "Callback marked as processed");
            
            // Clean up
            if (isset($result['payment_id'])) {
                $this->db->execute("DELETE FROM payments WHERE id = :id", ['id' => $result['payment_id']]);
            }
            if ($callback) {
                $this->db->execute("DELETE FROM mpesa_c2b_callbacks WHERE id = :id", ['id' => $callback['id']]);
            }
            
        } catch (Exception $e) {
            $this->assert(false, "C2B callback processing: " . $e->getMessage());
        }
        
        echo "\n";
    }
    
    private function testDuplicateCallbackHandling()
    {
        echo "Testing Duplicate Callback Handling...\n";
        
        try {
            $transId = 'TEST_DUP_' . time();
            
            $callbackData = [
                'TransactionType' => 'Pay Bill',
                'TransID' => $transId,
                'TransTime' => date('YmdHis'),
                'TransAmount' => '500.00',
                'BusinessShortCode' => '4163987',
                'BillRefNumber' => '12345678',
                'MSISDN' => '254712345678',
                'FirstName' => 'Test',
                'MiddleName' => '',
                'LastName' => 'User'
            ];
            
            // Process first time
            $result1 = $this->reconciliationService->processC2BCallback($callbackData);
            $this->assert($result1['success'] === true, "First callback processed");
            
            // Process duplicate
            $result2 = $this->reconciliationService->processC2BCallback($callbackData);
            $this->assert($result2['success'] === false, "Duplicate callback rejected");
            $this->assert(isset($result2['duplicate']), "Duplicate flag set");
            
            // Clean up
            if (isset($result1['payment_id'])) {
                $this->db->execute("DELETE FROM payments WHERE id = :id", ['id' => $result1['payment_id']]);
            }
            $this->db->execute("DELETE FROM mpesa_c2b_callbacks WHERE trans_id = :trans_id", ['trans_id' => $transId]);
            
        } catch (Exception $e) {
            $this->assert(false, "Duplicate callback handling: " . $e->getMessage());
        }
        
        echo "\n";
    }
    
    private function testReconciliationStats()
    {
        echo "Testing Reconciliation Statistics...\n";
        
        try {
            $stats = $this->reconciliationService->getReconciliationStats();
            
            $this->assert(is_array($stats), "Stats returned as array");
            $this->assert(isset($stats['total_payments']), "total_payments stat exists");
            $this->assert(isset($stats['matched']), "matched stat exists");
            $this->assert(isset($stats['unmatched']), "unmatched stat exists");
            $this->assert(isset($stats['manual']), "manual stat exists");
            $this->assert(isset($stats['auto_matched']), "auto_matched stat exists");
            $this->assert(isset($stats['total_amount']), "total_amount stat exists");
            
        } catch (Exception $e) {
            $this->assert(false, "Reconciliation stats: " . $e->getMessage());
        }
        
        echo "\n";
    }
    
    private function testPhoneNumberFormatting()
    {
        echo "Testing Phone Number Formatting...\n";
        
        $reflection = new ReflectionClass($this->reconciliationService);
        $method = $reflection->getMethod('formatPhoneNumber');
        $method->setAccessible(true);
        
        $tests = [
            '0712345678' => '+254712345678',
            '254712345678' => '+254712345678',
            '712345678' => '+254712345678'
        ];
        
        foreach ($tests as $input => $expected) {
            $result = $method->invoke($this->reconciliationService, $input);
            $this->assert($result === $expected, "Phone format: $input -> $expected");
        }
        
        echo "\n";
    }
    
    private function testTransTimeParser()
    {
        echo "Testing Transaction Time Parser...\n";
        
        $reflection = new ReflectionClass($this->reconciliationService);
        $method = $reflection->getMethod('parseTransTime');
        $method->setAccessible(true);
        
        $transTime = '20260130143025';
        $expected = '2026-01-30 14:30:25';
        
        $result = $method->invoke($this->reconciliationService, $transTime);
        $this->assert($result === $expected, "TransTime parsed correctly");
        
        echo "\n";
    }
    
    private function assert($condition, $message)
    {
        if ($condition) {
            echo "  ✓ $message\n";
            $this->passed++;
        } else {
            echo "  ✗ $message\n";
            $this->failed++;
        }
    }
}

// Run tests
$test = new Phase2Test();
$success = $test->runAllTests();

exit($success ? 0 : 1);
