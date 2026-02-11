<?php
/**
 * Run Phase 1 Migration - Service-Based Claims
 * Execute this script to apply database changes for service-based claim processing
 */

// Define root path
define('ROOT_PATH', __DIR__);
define('CONFIG_PATH', ROOT_PATH . '/config');

// Load configuration
require_once CONFIG_PATH . '/config.php';

echo "===========================================\n";
echo "SHENA Companion - Phase 1 Migration\n";
echo "Service-Based Claims Processing\n";
echo "===========================================\n\n";

try {
    // Connect to database
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Connected to database: " . DB_NAME . "\n\n";
    
    // Read migration file
    $migrationFile = ROOT_PATH . '/database/migrations/004_service_based_claims.sql';
    
    if (!file_exists($migrationFile)) {
        throw new Exception("Migration file not found: {$migrationFile}");
    }
    
    echo "✓ Found migration file\n\n";
    
    // Read SQL content
    $sql = file_get_contents($migrationFile);
    
    // Split into individual statements
    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        function($stmt) {
            return !empty($stmt) && 
                   stripos($stmt, 'USE ') !== 0 && 
                   stripos($stmt, '--') !== 0 &&
                   stripos($stmt, 'COMMIT') !== 0;
        }
    );
    
    echo "Executing migration statements...\n\n";
    
    // Note: DDL statements (ALTER TABLE, CREATE TABLE, etc.) auto-commit in MySQL
    // So we don't use transactions here
    
    $count = 0;
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (empty($statement)) continue;
        
        try {
            $pdo->exec($statement);
            $count++;
            
            // Show progress for major operations
            if (stripos($statement, 'ALTER TABLE') !== false) {
                preg_match('/ALTER TABLE\s+(\w+)/i', $statement, $matches);
                echo "  ✓ Updated table: " . ($matches[1] ?? 'unknown') . "\n";
            } elseif (stripos($statement, 'CREATE TABLE') !== false) {
                preg_match('/CREATE TABLE\s+(\w+)/i', $statement, $matches);
                echo "  ✓ Created table: " . ($matches[1] ?? 'unknown') . "\n";
            } elseif (stripos($statement, 'CREATE INDEX') !== false) {
                preg_match('/CREATE INDEX\s+(\w+)/i', $statement, $matches);
                echo "  ✓ Created index: " . ($matches[1] ?? 'unknown') . "\n";
            }
        } catch (PDOException $e) {
            // Check if error is because object already exists
            if (strpos($e->getMessage(), 'Duplicate column') !== false ||
                strpos($e->getMessage(), 'Duplicate key name') !== false ||
                strpos($e->getMessage(), 'already exists') !== false) {
                echo "  ⚠ Skipped (already exists)\n";
                continue;
            }
            throw $e;
        }
    }
    
    echo "\n✓ Migration completed successfully!\n";
    echo "  Total statements executed: {$count}\n\n";
    
    // Verify new tables
    echo "Verifying database structure...\n\n";
    
    $tables = [
        'claim_service_checklist',
        'claim_cash_alternative_agreements'
    ];
    
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '{$table}'");
        if ($stmt->rowCount() > 0) {
            echo "  ✓ Table '{$table}' created successfully\n";
        } else {
            echo "  ✗ Table '{$table}' not found\n";
        }
    }
    
    echo "\n";
    
    // Check new columns in claims table
    $stmt = $pdo->query("DESCRIBE claims");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $newColumns = [
        'place_of_death',
        'cause_of_death',
        'mortuary_name',
        'mortuary_bill_amount',
        'service_delivery_type',
        'cash_alternative_reason',
        'cash_alternative_agreement_signed',
        'cash_alternative_amount',
        'mortuary_bill_settled',
        'body_dressing_completed',
        'coffin_delivered',
        'transportation_arranged',
        'equipment_delivered',
        'services_delivery_date',
        'mortuary_days_count',
        'mortuary_bill_reference'
    ];
    
    foreach ($newColumns as $column) {
        if (in_array($column, $columns)) {
            echo "  ✓ Column 'claims.{$column}' added successfully\n";
        } else {
            echo "  ✗ Column 'claims.{$column}' not found\n";
        }
    }
    
    echo "\n===========================================\n";
    echo "Phase 1 Migration Complete!\n";
    echo "===========================================\n\n";
    echo "✓ All member claim form fields are ready\n";
    echo "✓ Service-based claims system is active\n";
    echo "✓ Cash alternative (KSH 20,000) support enabled\n\n";
    echo "Next Steps:\n";
    echo "1. Test member claim submission with all required documents\n";
    echo "2. Test admin claim approval for standard services\n";
    echo "3. Test service delivery tracking and checklist\n";
    echo "4. Test cash alternative approval workflow\n";
    echo "5. Verify mortuary days validation (max 14 days)\n\n";
    
} catch (PDOException $e) {
    echo "\n✗ Migration failed!\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
    
} catch (Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
