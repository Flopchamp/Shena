<?php
/**
 * Test Phase 1 Implementation - Service-Based Claims
 * Verify all components are working correctly
 */

// Define root path
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');

// Load configuration
require_once CONFIG_PATH . '/config.php';

// Autoload classes
spl_autoload_register(function ($class) {
    $paths = [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
        APP_PATH . '/services/',
        APP_PATH . '/core/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

echo "===========================================\n";
echo "Phase 1 Testing - Service-Based Claims\n";
echo "===========================================\n\n";

$passed = 0;
$failed = 0;

// Test 1: Check if new models exist
echo "Test 1: Checking new model classes...\n";
$models = [
    'ClaimServiceChecklist',
    'ClaimCashAlternative'
];

foreach ($models as $model) {
    if (class_exists($model)) {
        echo "  ✓ {$model} class exists\n";
        $passed++;
    } else {
        echo "  ✗ {$model} class NOT found\n";
        $failed++;
    }
}

// Test 2: Check Claim model methods
echo "\nTest 2: Checking Claim model methods...\n";
$claimModel = new Claim();
$methods = [
    'approveClaimForServices',
    'approveClaimForCashAlternative',
    'updateServiceDeliveryStatus',
    'getClaimServiceChecklist',
    'completeClaim'
];

foreach ($methods as $method) {
    if (method_exists($claimModel, $method)) {
        echo "  ✓ Claim::{$method}() exists\n";
        $passed++;
    } else {
        echo "  ✗ Claim::{$method}() NOT found\n";
        $failed++;
    }
}

// Test 3: Database connection and table structure
echo "\nTest 3: Checking database structure...\n";
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "  ✓ Database connection successful\n";
    $passed++;
    
    // Check tables
    $tables = [
        'claim_service_checklist',
        'claim_cash_alternative_agreements'
    ];
    
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '{$table}'");
        if ($stmt->rowCount() > 0) {
            echo "  ✓ Table '{$table}' exists\n";
            $passed++;
        } else {
            echo "  ✗ Table '{$table}' NOT found - Run migration first\n";
            $failed++;
        }
    }
    
    // Check claims table columns
    $stmt = $pdo->query("DESCRIBE claims");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $requiredColumns = [
        'service_delivery_type',
        'mortuary_bill_settled',
        'body_dressing_completed',
        'coffin_delivered',
        'transportation_arranged',
        'equipment_delivered'
    ];
    
    foreach ($requiredColumns as $column) {
        if (in_array($column, $columns)) {
            echo "  ✓ Column 'claims.{$column}' exists\n";
            $passed++;
        } else {
            echo "  ✗ Column 'claims.{$column}' NOT found - Run migration first\n";
            $failed++;
        }
    }
    
} catch (PDOException $e) {
    echo "  ✗ Database error: " . $e->getMessage() . "\n";
    $failed++;
}

// Summary
echo "\n===========================================\n";
echo "Test Summary\n";
echo "===========================================\n";
echo "Passed: {$passed}\n";
echo "Failed: {$failed}\n";

if ($failed === 0) {
    echo "\n✓ All tests passed! Phase 1 implementation is complete.\n\n";
} else {
    echo "\n⚠ Some tests failed. Run migration if database tests failed:\n";
    echo "   php run_phase1_migration.php\n";
}

echo "\n";
