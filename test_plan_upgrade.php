<?php
/**
 * Test Plan Upgrade Feature
 * Tests prorated calculations, upgrade workflow, and payment simulation
 */

// Define ROOT_PATH
define('ROOT_PATH', __DIR__);

require_once 'config/config.php';
require_once 'app/core/Database.php';
require_once 'app/core/BaseModel.php';
require_once 'app/models/Member.php';
require_once 'app/services/PlanUpgradeService.php';
require_once 'app/services/PaymentService.php';
require_once 'app/services/EmailService.php';
require_once 'app/services/SmsService.php';

// Test configuration
define('TEST_MODE', true);

class PlanUpgradeTest {
    private $db;
    private $upgradeService;
    private $testResults = [];
    private $testMemberId = null;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->upgradeService = new PlanUpgradeService();
    }
    
    public function runAllTests() {
        echo "\n" . str_repeat("=", 70) . "\n";
        echo "PLAN UPGRADE FEATURE TEST SUITE\n";
        echo str_repeat("=", 70) . "\n\n";
        
        $this->cleanup(); // Clean up any existing test data first
        $this->testDatabaseSchema();
        $this->createTestMember(); // Create member first
        $this->testProratedCalculations();
        $this->testUpgradeEligibility();
        $this->testUpgradeRequest();
        $this->testUpgradeStatusCheck();
        $this->testUpgradeCancellation();
        $this->testUpgradeCompletion();
        $this->testUpgradeHistory();
        $this->testMultipleUpgradePrevention();
        $this->testPackageFees();
        $this->cleanup();
        
        $this->printSummary();
    }
    
    private function testDatabaseSchema() {
        $this->log("Testing Database Schema");
        
        try {
            // Check plan_upgrade_requests table
            $stmt = $this->db->query("DESCRIBE plan_upgrade_requests");
            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $this->assert(
                in_array('id', $columns) && in_array('member_id', $columns),
                "plan_upgrade_requests table has required columns"
            );
            
            // Check plan_upgrade_history table
            $stmt = $this->db->query("DESCRIBE plan_upgrade_history");
            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $this->assert(
                in_array('id', $columns) && in_array('member_id', $columns),
                "plan_upgrade_history table has required columns"
            );
            
            // Check views
            $stmt = $this->db->query("SHOW FULL TABLES WHERE Table_type = 'VIEW'");
            $views = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $this->assert(
                in_array('vw_pending_upgrades', $views),
                "vw_pending_upgrades view exists"
            );
            $this->assert(
                in_array('vw_upgrade_statistics', $views),
                "vw_upgrade_statistics view exists"
            );
            
            // Check members table has upgrade columns
            $stmt = $this->db->query("DESCRIBE members");
            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $this->assert(
                in_array('last_upgrade_date', $columns) && in_array('upgrade_count', $columns),
                "members table has upgrade tracking columns"
            );
            
        } catch (Exception $e) {
            $this->assert(false, "Database schema check: " . $e->getMessage());
        }
    }
    
    private function testProratedCalculations() {
        $this->log("Testing Prorated Calculations");
        
        if (!$this->testMemberId) {
            $this->assert(false, "No test member available for prorated calculations");
            return;
        }
        
        // Test 1: Beginning of month (30 days remaining in 30-day month)
        $result = $this->upgradeService->calculateUpgradeCost($this->testMemberId, 'premium', date('Y-m-01'));
        $expectedAmount = (1000 - 500) * (30 / 30); // Full month difference
        $this->assert(
            abs($result['prorated_amount'] - $expectedAmount) < 1,
            "Prorated amount for beginning of month: Expected ~{$expectedAmount}, Got {$result['prorated_amount']}"
        );
        $this->assert(
            $result['days_remaining'] == 30,
            "Days remaining for beginning of month: Expected 30, Got {$result['days_remaining']}"
        );
        
        // Test 2: Mid-month (15 days remaining in 30-day month)
        $midMonth = date('Y-m-15');
        $result = $this->upgradeService->calculateUpgradeCost($this->testMemberId, 'premium', $midMonth);
        $expectedAmount = (1000 - 500) * (15 / 30); // Half month difference
        $this->assert(
            abs($result['prorated_amount'] - $expectedAmount) < 1,
            "Prorated amount for mid-month: Expected ~{$expectedAmount}, Got {$result['prorated_amount']}"
        );
        
        // Test 3: End of month (1 day remaining)
        $lastDay = date('Y-m-t'); // Last day of current month
        $result = $this->upgradeService->calculateUpgradeCost($this->testMemberId, 'premium', $lastDay);
        $totalDays = date('t', strtotime($lastDay));
        $expectedAmount = (1000 - 500) * (1 / $totalDays);
        $this->assert(
            abs($result['prorated_amount'] - $expectedAmount) < 1,
            "Prorated amount for last day: Expected ~{$expectedAmount}, Got {$result['prorated_amount']}"
        );
        
        // Test 4: Check all calculation components
        $result = $this->upgradeService->calculateUpgradeCost($this->testMemberId, 'premium');
        $this->assert(
            isset($result['current_monthly_fee']) && $result['current_monthly_fee'] == 500,
            "Current monthly fee is correct"
        );
        $this->assert(
            isset($result['new_monthly_fee']) && $result['new_monthly_fee'] == 1000,
            "New monthly fee is correct"
        );
        $this->assert(
            isset($result['days_remaining']) && $result['days_remaining'] > 0,
            "Days remaining is calculated"
        );
        $this->assert(
            isset($result['total_days_in_month']) && $result['total_days_in_month'] > 0,
            "Total days in month is calculated"
        );
    }
    
    private function createTestMember() {
        $this->log("Creating Test Member");
        
        try {
            // Note: This test uses basic/premium packages for plan upgrades
            // These are simplified package tiers separate from the main individual/couple/family/executive packages
            
            // First, temporarily allow 'basic' and 'premium' enum values for testing
            // In production, integrate with actual package system
            $this->db->exec("
                ALTER TABLE members 
                MODIFY package ENUM('individual', 'couple', 'family', 'executive', 'basic', 'premium') DEFAULT 'individual'
            ");
            
            // Create a test user first
            $stmt = $this->db->prepare("
                INSERT INTO users (
                    first_name, last_name, email, phone, password, role, status
                ) VALUES (
                    'Test', 'Upgrade', 'test.upgrade@example.com', '+254712345678',
                    :password, 'member', 'active'
                )
            ");
            
            $stmt->execute(['password' => password_hash('test123', PASSWORD_DEFAULT)]);
            $userId = $this->db->lastInsertId();
            
            // Create a test member with basic package
            $memberNumber = 'TEST-' . time();
            $stmt = $this->db->prepare("
                INSERT INTO members (
                    user_id, member_number, id_number, date_of_birth, 
                    gender, package, status
                ) VALUES (
                    :user_id, :member_number, 'TEST123456', '1990-01-01',
                    'male', 'basic', 'active'
                )
            ");
            
            $stmt->execute([
                'user_id' => $userId,
                'member_number' => $memberNumber
            ]);
            $this->testMemberId = $this->db->lastInsertId();
            
            $this->assert(
                $this->testMemberId > 0,
                "Test member created with ID: {$this->testMemberId}"
            );
            
        } catch (Exception $e) {
            $this->assert(false, "Failed to create test member: " . $e->getMessage());
        }
    }
    
    private function testUpgradeEligibility() {
        $this->log("Testing Upgrade Eligibility");
        
        if (!$this->testMemberId) {
            $this->assert(false, "No test member available");
            return;
        }
        
        try {
            // Test member with basic package should be eligible
            $stmt = $this->db->prepare("SELECT package FROM members WHERE id = ?");
            $stmt->execute([$this->testMemberId]);
            $package = $stmt->fetchColumn();
            
            $this->assert(
                $package === 'basic',
                "Test member has basic package"
            );
            
            // Test member should not have pending upgrades
            $stmt = $this->db->prepare("
                SELECT COUNT(*) FROM plan_upgrade_requests 
                WHERE member_id = ? AND status IN ('pending', 'payment_initiated')
            ");
            $stmt->execute([$this->testMemberId]);
            $pendingCount = $stmt->fetchColumn();
            
            $this->assert(
                $pendingCount == 0,
                "Test member has no pending upgrades"
            );
            
        } catch (Exception $e) {
            $this->assert(false, "Eligibility check failed: " . $e->getMessage());
        }
    }
    
    private function testUpgradeRequest() {
        $this->log("Testing Upgrade Request Creation");
        
        if (!$this->testMemberId) {
            $this->assert(false, "No test member available");
            return;
        }
        
        try {
            $upgradeRequestId = $this->upgradeService->createUpgradeRequest($this->testMemberId);
            
            $this->assert(
                $upgradeRequestId > 0,
                "Upgrade request created successfully with ID: {$upgradeRequestId}"
            );
            
            // Verify the request was saved to database
            $stmt = $this->db->prepare("
                SELECT * FROM plan_upgrade_requests 
                WHERE id = ?
            ");
            $stmt->execute([$upgradeRequestId]);
            $request = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->assert(
                $request !== false,
                "Upgrade request exists in database"
            );
            
            $this->assert(
                $request['member_id'] == $this->testMemberId,
                "Upgrade request has correct member ID"
            );
            
            $this->assert(
                $request['from_package'] === 'basic' && $request['to_package'] === 'premium',
                "Upgrade request has correct packages"
            );
            
            $this->assert(
                $request['status'] === 'pending',
                "Upgrade request status is pending"
            );
            
            $this->assert(
                $request['prorated_amount'] > 0,
                "Upgrade request has prorated amount: {$request['prorated_amount']}"
            );
            
        } catch (Exception $e) {
            $this->assert(false, "Upgrade request creation failed: " . $e->getMessage());
        }
    }
    
    private function testUpgradeStatusCheck() {
        $this->log("Testing Upgrade Status Check");
        
        if (!$this->testMemberId) {
            $this->assert(false, "No test member available");
            return;
        }
        
        try {
            // Get the pending upgrade
            $stmt = $this->db->prepare("
                SELECT id FROM plan_upgrade_requests 
                WHERE member_id = ? AND status = 'pending'
                ORDER BY id DESC LIMIT 1
            ");
            $stmt->execute([$this->testMemberId]);
            $upgradeRequestId = $stmt->fetchColumn();
            
            if (!$upgradeRequestId) {
                $this->assert(false, "No pending upgrade found for status check");
                return;
            }
            
            $result = $this->upgradeService->getUpgradeRequestStatus($upgradeRequestId);
            
            $this->assert(
                $result['success'] === true,
                "Status check successful"
            );
            
            $this->assert(
                $result['status'] === 'pending',
                "Status is pending"
            );
            
        } catch (Exception $e) {
            $this->assert(false, "Status check failed: " . $e->getMessage());
        }
    }
    
    private function testUpgradeCancellation() {
        $this->log("Testing Upgrade Cancellation");
        
        if (!$this->testMemberId) {
            $this->assert(false, "No test member available");
            return;
        }
        
        try {
            // Get the pending upgrade
            $stmt = $this->db->prepare("
                SELECT id FROM plan_upgrade_requests 
                WHERE member_id = ? AND status = 'pending'
                ORDER BY id DESC LIMIT 1
            ");
            $stmt->execute([$this->testMemberId]);
            $upgradeRequestId = $stmt->fetchColumn();
            
            if (!$upgradeRequestId) {
                $this->assert(false, "No pending upgrade found for cancellation");
                return;
            }
            
            $result = $this->upgradeService->cancelUpgrade($upgradeRequestId, 'Test cancellation');
            
            $this->assert(
                $result['success'] === true,
                "Upgrade cancelled successfully"
            );
            
            // Verify status changed to cancelled
            $stmt = $this->db->prepare("SELECT status FROM plan_upgrade_requests WHERE id = ?");
            $stmt->execute([$upgradeRequestId]);
            $status = $stmt->fetchColumn();
            
            $this->assert(
                $status === 'cancelled',
                "Upgrade status is cancelled"
            );
            
        } catch (Exception $e) {
            $this->assert(false, "Cancellation failed: " . $e->getMessage());
        }
    }
    
    private function testUpgradeCompletion() {
        $this->log("Testing Upgrade Completion");
        
        if (!$this->testMemberId) {
            $this->assert(false, "No test member available");
            return;
        }
        
        try {
            // Create a new upgrade request for completion test
            $upgradeRequestId = $this->upgradeService->createUpgradeRequest($this->testMemberId);
            
            // Simulate payment completion
            $stmt = $this->db->prepare("
                UPDATE plan_upgrade_requests 
                SET status = 'payment_initiated', mpesa_checkout_id = CONCAT('TEST-', ?)
                WHERE id = ?
            ");
            $stmt->execute([time(), $upgradeRequestId]);
            
            // Complete the upgrade
            $completeResult = $this->upgradeService->completeUpgrade($upgradeRequestId);
            
            $this->assert(
                $completeResult['success'] === true,
                "Upgrade completed successfully"
            );
            
            // Verify member package was updated
            $stmt = $this->db->prepare("SELECT package FROM members WHERE id = ?");
            $stmt->execute([$this->testMemberId]);
            $newPackage = $stmt->fetchColumn();
            
            $this->assert(
                $newPackage === 'premium',
                "Member package updated to premium"
            );
            
            // Verify history record was created
            $stmt = $this->db->prepare("
                SELECT COUNT(*) FROM plan_upgrade_history 
                WHERE member_id = ? AND from_package = 'basic' AND to_package = 'premium'
            ");
            $stmt->execute([$this->testMemberId]);
            $historyCount = $stmt->fetchColumn();
            
            $this->assert(
                $historyCount > 0,
                "Upgrade history record created"
            );
            
            // Verify request status is completed
            $stmt = $this->db->prepare("SELECT status FROM plan_upgrade_requests WHERE id = ?");
            $stmt->execute([$upgradeRequestId]);
            $status = $stmt->fetchColumn();
            
            $this->assert(
                $status === 'completed',
                "Upgrade request status is completed"
            );
            
        } catch (Exception $e) {
            $this->assert(false, "Upgrade completion failed: " . $e->getMessage());
        }
    }
    
    private function testUpgradeHistory() {
        $this->log("Testing Upgrade History Retrieval");
        
        if (!$this->testMemberId) {
            $this->assert(false, "No test member available");
            return;
        }
        
        try {
            $history = $this->upgradeService->getMemberUpgradeHistory($this->testMemberId);
            
            $this->assert(
                is_array($history),
                "History is an array"
            );
            
            $this->assert(
                count($history) > 0,
                "History has records: " . count($history)
            );
            
            if (count($history) > 0) {
                $record = $history[0];
                $this->assert(
                    isset($record['from_package']) && isset($record['to_package']),
                    "History record has package information"
                );
            }
            
        } catch (Exception $e) {
            $this->assert(false, "History retrieval failed: " . $e->getMessage());
        }
    }
    
    private function testMultipleUpgradePrevention() {
        $this->log("Testing Multiple Upgrade Prevention");
        
        if (!$this->testMemberId) {
            $this->assert(false, "No test member available");
            return;
        }
        
        try {
            // Member is now premium, try to upgrade again
            $result = $this->upgradeService->createUpgradeRequest($this->testMemberId);
            
            // If we get here, it means the prevention didn't work
            $this->assert(false, "Should not be able to upgrade premium member");
            
        } catch (Exception $e) {
            // Expected to throw an exception
            $this->assert(
                strpos($e->getMessage(), 'already') !== false || strpos($e->getMessage(), 'premium') !== false,
                "Error message mentions already premium: " . $e->getMessage()
            );
        }
    }
    
    private function testPackageFees() {
        $this->log("Testing Package Fees");
        
        $basicFee = PlanUpgradeService::PACKAGE_FEES['basic'];
        $premiumFee = PlanUpgradeService::PACKAGE_FEES['premium'];
        
        $this->assert(
            $basicFee == 500,
            "Basic package fee is KES 500"
        );
        
        $this->assert(
            $premiumFee == 1000,
            "Premium package fee is KES 1000"
        );
        
        $this->assert(
            $premiumFee > $basicFee,
            "Premium fee is higher than basic"
        );
    }
    
    private function cleanup() {
        $this->log("Cleaning Up Test Data");
        
        try {
            // Delete test user and related data by email
            $stmt = $this->db->prepare("SELECT id FROM users WHERE email = 'test.upgrade@example.com'");
            $stmt->execute();
            $userId = $stmt->fetchColumn();
            
            if ($userId) {
                // Get member_id
                $stmt = $this->db->prepare("SELECT id FROM members WHERE user_id = ?");
                $stmt->execute([$userId]);
                $memberId = $stmt->fetchColumn();
                
                if ($memberId) {
                    // Delete upgrade requests
                    $stmt = $this->db->prepare("DELETE FROM plan_upgrade_requests WHERE member_id = ?");
                    $stmt->execute([$memberId]);
                    
                    // Delete upgrade history
                    $stmt = $this->db->prepare("DELETE FROM plan_upgrade_history WHERE member_id = ?");
                    $stmt->execute([$memberId]);
                    
                    // Delete member
                    $stmt = $this->db->prepare("DELETE FROM members WHERE id = ?");
                    $stmt->execute([$memberId]);
                }
                
                // Delete user
                $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$userId]);
            }
            
            $this->assert(true, "Test data cleaned up");
            
        } catch (Exception $e) {
            $this->assert(false, "Cleanup failed: " . $e->getMessage());
        }
    }
    
    private function assert($condition, $message) {
        $result = [
            'passed' => (bool)$condition,
            'message' => $message
        ];
        $this->testResults[] = $result;
        
        $status = $condition ? '✓ PASS' : '✗ FAIL';
        $color = $condition ? "\033[32m" : "\033[31m";
        echo "{$color}{$status}\033[0m - {$message}\n";
    }
    
    private function log($section) {
        echo "\n" . str_repeat("-", 70) . "\n";
        echo "{$section}\n";
        echo str_repeat("-", 70) . "\n";
    }
    
    private function printSummary() {
        $total = count($this->testResults);
        $passed = count(array_filter($this->testResults, function($r) { return $r['passed']; }));
        $failed = $total - $passed;
        $passRate = $total > 0 ? ($passed / $total) * 100 : 0;
        
        echo "\n" . str_repeat("=", 70) . "\n";
        echo "TEST SUMMARY\n";
        echo str_repeat("=", 70) . "\n";
        echo "Total Tests:  {$total}\n";
        echo "\033[32mPassed:       {$passed}\033[0m\n";
        echo "\033[31mFailed:       {$failed}\033[0m\n";
        echo "Pass Rate:    " . number_format($passRate, 2) . "%\n";
        echo str_repeat("=", 70) . "\n\n";
        
        if ($failed > 0) {
            echo "\033[31mFailed Tests:\033[0m\n";
            foreach ($this->testResults as $result) {
                if (!$result['passed']) {
                    echo "  • {$result['message']}\n";
                }
            }
            echo "\n";
        }
    }
}

// Run tests
try {
    $test = new PlanUpgradeTest();
    $test->runAllTests();
} catch (Exception $e) {
    echo "\n\033[31mFatal Error: {$e->getMessage()}\033[0m\n\n";
    exit(1);
}
