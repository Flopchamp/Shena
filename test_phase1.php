<?php
/**
 * Phase 1 Validation Test Script
 * Run this to verify all Phase 1 implementations are working
 */

// Setup
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');

require_once CONFIG_PATH . '/config.php';
require_once APP_PATH . '/core/Database.php';
require_once APP_PATH . '/core/BaseModel.php';
require_once APP_PATH . '/models/Member.php';
require_once APP_PATH . '/models/Dependent.php';
require_once APP_PATH . '/models/Claim.php';
require_once APP_PATH . '/helpers/functions.php';

echo "=== PHASE 1 VALIDATION TEST ===\n\n";

$passed = 0;
$failed = 0;

// Test 1: Database Connection
echo "Test 1: Database Connection... ";
try {
    $db = Database::getInstance();
    echo "✅ PASS\n";
    $passed++;
} catch (Exception $e) {
    echo "❌ FAIL: " . $e->getMessage() . "\n";
    $failed++;
}

// Test 2: Dependents Table Exists
echo "Test 2: Dependents Table Exists... ";
try {
    $db = Database::getInstance();
    $result = $db->getConnection()->query("SHOW TABLES LIKE 'dependents'");
    if ($result->rowCount() > 0) {
        echo "✅ PASS\n";
        $passed++;
    } else {
        echo "❌ FAIL: Table not found\n";
        $failed++;
    }
} catch (Exception $e) {
    echo "❌ FAIL: " . $e->getMessage() . "\n";
    $failed++;
}

// Test 3: Claims Table Has dependent_id
echo "Test 3: Claims Table Updated... ";
try {
    $db = Database::getInstance();
    $result = $db->getConnection()->query("SHOW COLUMNS FROM claims LIKE 'dependent_id'");
    if ($result->rowCount() > 0) {
        echo "✅ PASS\n";
        $passed++;
    } else {
        echo "❌ FAIL: Column not found\n";
        $failed++;
    }
} catch (Exception $e) {
    echo "❌ FAIL: " . $e->getMessage() . "\n";
    $failed++;
}

// Test 4: Dependent Model Loads
echo "Test 4: Dependent Model Loads... ";
try {
    $dep = new Dependent();
    if (method_exists($dep, 'addDependent')) {
        echo "✅ PASS\n";
        $passed++;
    } else {
        echo "❌ FAIL: Method not found\n";
        $failed++;
    }
} catch (Exception $e) {
    echo "❌ FAIL: " . $e->getMessage() . "\n";
    $failed++;
}

// Test 5: Member Model Has Dependent Methods
echo "Test 5: Member Model Enhanced... ";
try {
    $member = new Member();
    if (method_exists($member, 'getDependents') && 
        method_exists($member, 'canAddDependent')) {
        echo "✅ PASS\n";
        $passed++;
    } else {
        echo "❌ FAIL: Methods not found\n";
        $failed++;
    }
} catch (Exception $e) {
    echo "❌ FAIL: " . $e->getMessage() . "\n";
    $failed++;
}

// Test 6: Claim Model Has Dependent Methods
echo "Test 6: Claim Model Enhanced... ";
try {
    $claim = new Claim();
    if (method_exists($claim, 'getClaimWithDependent') && 
        method_exists($claim, 'getAllClaimsWithDependents')) {
        echo "✅ PASS\n";
        $passed++;
    } else {
        echo "❌ FAIL: Methods not found\n";
        $failed++;
    }
} catch (Exception $e) {
    echo "❌ FAIL: " . $e->getMessage() . "\n";
    $failed++;
}

// Test 7: Cron Job Files Exist
echo "Test 7: Cron Job Files... ";
$cronFiles = [
    'cron/check_payment_status.php',
    'cron/check_dependent_ages.php'
];
$allExist = true;
foreach ($cronFiles as $file) {
    if (!file_exists(ROOT_PATH . '/' . $file)) {
        $allExist = false;
        break;
    }
}
if ($allExist) {
    echo "✅ PASS\n";
    $passed++;
} else {
    echo "❌ FAIL: Cron files missing\n";
    $failed++;
}

// Test 8: Migration File Exists
echo "Test 8: Migration File... ";
if (file_exists(ROOT_PATH . '/database/migrations/001_add_dependents_support.sql')) {
    echo "✅ PASS\n";
    $passed++;
} else {
    echo "❌ FAIL: Migration file missing\n";
    $failed++;
}

// Test 9: Package Configuration
echo "Test 9: Package Limits Configured... ";
global $membership_packages;
$hasLimits = false;
foreach ($membership_packages as $pkg) {
    if (isset($pkg['max_children']) || isset($pkg['max_parents'])) {
        $hasLimits = true;
        break;
    }
}
if ($hasLimits) {
    echo "✅ PASS\n";
    $passed++;
} else {
    echo "❌ FAIL: Package limits not found\n";
    $failed++;
}

// Test 10: Helper Functions
echo "Test 10: Helper Functions... ";
if (function_exists('calculateAge') && 
    function_exists('getMaturityPeriodMonths') && 
    function_exists('validateClaimEligibility')) {
    echo "✅ PASS\n";
    $passed++;
} else {
    echo "❌ FAIL: Helper functions missing\n";
    $failed++;
}

// Summary
echo "\n=== TEST SUMMARY ===\n";
echo "Passed: {$passed}/10\n";
echo "Failed: {$failed}/10\n";

if ($failed === 0) {
    echo "\n✅ ALL TESTS PASSED - Phase 1 is ready!\n";
    echo "\nNext Steps:\n";
    echo "1. Run migration: mysql -u root -p shena_welfare_db < database/migrations/001_add_dependents_support.sql\n";
    echo "2. Set up cron jobs in Task Scheduler\n";
    echo "3. Create storage/logs directory\n";
    echo "4. Begin user acceptance testing\n";
    exit(0);
} else {
    echo "\n❌ SOME TESTS FAILED - Review errors above\n";
    exit(1);
}
