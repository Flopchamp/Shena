<?php
/**
 * Run Phase 4 Enhancements Migration
 * Executes 007_phase4_enhancements.sql
 */

define('ROOT_PATH', __DIR__);
require_once 'config/config.php';
require_once 'app/core/Database.php';

echo "========================================\n";
echo "Phase 4 Enhancements Migration\n";
echo "========================================\n\n";

try {
    $db = Database::getInstance()->getConnection();
    
    // Read migration file
    $sql = file_get_contents(__DIR__ . '/database/migrations/007_phase4_enhancements.sql');
    
    // Remove comments and split into statements
    $sql = preg_replace('/--.*$/m', '', $sql);
    $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
    
    // Split by semicolon but handle CREATE VIEW which may have multiple semicolons
    $statements = preg_split('/;(?=(?:[^\'"]|[\'"][^\'"]*[\'"])*$)/', $sql);
    $statements = array_filter(array_map('trim', $statements));
    
    $executed = 0;
    $errors = 0;
    
    foreach ($statements as $statement) {
        if (empty($statement)) continue;
        
        try {
            $db->exec($statement);
            $executed++;
            
            // Extract table/view name for better output
            if (preg_match('/CREATE\s+(?:TABLE|VIEW|OR\s+REPLACE\s+VIEW)\s+(?:IF\s+NOT\s+EXISTS\s+)?`?(\w+)`?/i', $statement, $matches)) {
                echo "✓ Created: {$matches[1]}\n";
            } else {
                echo "✓ Executed statement\n";
            }
        } catch (PDOException $e) {
            // Skip if already exists
            if (strpos($e->getMessage(), 'already exists') === false) {
                echo "✗ Error: " . $e->getMessage() . "\n";
                $errors++;
            } else {
                echo "- Skipped (already exists)\n";
            }
        }
    }
    
    echo "\n========================================\n";
    echo "Migration Summary\n";
    echo "========================================\n";
    echo "Statements executed: $executed\n";
    echo "Errors: $errors\n";
    
    // Verify tables exist
    echo "\n========================================\n";
    echo "Verification\n";
    echo "========================================\n";
    
    $tables = ['mpesa_config', 'scheduled_campaigns', 'financial_transactions', 'payment_reminders'];
    foreach ($tables as $table) {
        $stmt = $db->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "✓ Table '$table' exists\n";
        } else {
            echo "✗ Table '$table' NOT FOUND\n";
        }
    }
    
    $views = ['vw_financial_summary', 'vw_agent_leaderboard', 'vw_scheduled_campaigns_summary'];
    foreach ($views as $view) {
        $stmt = $db->query("SHOW FULL TABLES WHERE Table_type = 'VIEW' AND Tables_in_" . DB_NAME . " = '$view'");
        if ($stmt->rowCount() > 0) {
            echo "✓ View '$view' exists\n";
        } else {
            echo "✗ View '$view' NOT FOUND\n";
        }
    }
    
    echo "\n✓ Phase 4 migration completed!\n\n";
    
} catch (Exception $e) {
    echo "\n✗ Migration failed: " . $e->getMessage() . "\n\n";
    exit(1);
}
