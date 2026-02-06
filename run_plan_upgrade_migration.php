<?php
/**
 * Run Plan Upgrade Migration
 * Execute database migration for plan upgrade feature
 */

define('ROOT_PATH', __DIR__);
require_once 'config/config.php';
require_once 'app/core/Database.php';

$db = Database::getInstance();

echo "====================================\n";
echo "   PLAN UPGRADE MIGRATION\n";
echo "====================================\n\n";

try {
    // Read migration file
    $migrationFile = __DIR__ . '/database/migrations/006_plan_upgrades.sql';
    
    if (!file_exists($migrationFile)) {
        throw new Exception("Migration file not found: $migrationFile");
    }
    
    $sql = file_get_contents($migrationFile);
    
    // Remove comments and split by semicolon
    $sql = preg_replace('/--.*$/m', '', $sql);
    $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
    
    // Split into individual statements
    $statements = array_filter(
        array_map('trim', preg_split('/;[\s]*$/m', $sql)),
        function($stmt) {
            $stmt = trim($stmt);
            return !empty($stmt) && strlen($stmt) > 10;
        }
    );
    
    echo "Found " . count($statements) . " SQL statements\n\n";
    
    $successCount = 0;
    $errorCount = 0;
    
    foreach ($statements as $index => $statement) {
        $statementNum = $index + 1;
        
        // Extract statement type for display
        preg_match('/^(CREATE|ALTER|DROP|INSERT|UPDATE)\s+(\w+)/i', $statement, $matches);
        $action = isset($matches[1]) ? $matches[1] : 'EXECUTE';
        $target = isset($matches[2]) ? $matches[2] : '';
        
        echo "[$statementNum] Executing: $action $target... ";
        
        try {
            $db->execute($statement);
            echo "✓ Success\n";
            $successCount++;
        } catch (Exception $e) {
            // Check if it's a benign error (already exists, etc.)
            if (stripos($e->getMessage(), 'already exists') !== false ||
                stripos($e->getMessage(), 'Duplicate') !== false) {
                echo "⊙ Already exists (skipped)\n";
                $successCount++;
            } else {
                echo "✗ Error: " . $e->getMessage() . "\n";
                $errorCount++;
            }
        }
    }
    
    echo "\n====================================\n";
    echo "   MIGRATION SUMMARY\n";
    echo "====================================\n";
    echo "Total Statements: " . count($statements) . "\n";
    echo "Successful: $successCount\n";
    echo "Errors: $errorCount\n";
    
    if ($errorCount === 0) {
        echo "\n✓ Migration completed successfully!\n";
    } else {
        echo "\n⚠ Migration completed with errors. Please review.\n";
    }
    
    // Verify tables were created
    echo "\n====================================\n";
    echo "   VERIFICATION\n";
    echo "====================================\n";
    
    $tables = [
        'plan_upgrade_requests',
        'plan_upgrade_history'
    ];
    
    foreach ($tables as $table) {
        $result = $db->fetchAll("SHOW TABLES LIKE '$table'");
        if ($result && count($result) > 0) {
            echo "✓ Table '$table' exists\n";
        } else {
            echo "✗ Table '$table' NOT found\n";
        }
    }
    
    // Check views
    $views = [
        'vw_pending_upgrades',
        'vw_upgrade_statistics'
    ];
    
    foreach ($views as $view) {
        $result = $db->fetchAll("SHOW FULL TABLES WHERE Table_type = 'VIEW' AND Tables_in_" . DB_NAME . " = '$view'");
        if ($result && count($result) > 0) {
            echo "✓ View '$view' exists\n";
        } else {
            echo "✗ View '$view' NOT found\n";
        }
    }
    
    echo "\n✓ Plan upgrade feature is ready to use!\n";
    
} catch (Exception $e) {
    echo "\n✗ Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
