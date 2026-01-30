<?php
/**
 * Run Phase 2 Migration: Payment Reconciliation
 */

// Define ROOT_PATH before including config
define('ROOT_PATH', __DIR__);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/core/Database.php';

try {
    $db = Database::getInstance();
    
    echo "===============================================\n";
    echo "   Phase 2: Payment Reconciliation Migration\n";
    echo "===============================================\n\n";
    
    // Read migration file
    $migrationFile = __DIR__ . '/database/migrations/005_payment_reconciliation.sql';
    
    if (!file_exists($migrationFile)) {
        throw new Exception("Migration file not found: $migrationFile");
    }
    
    $sql = file_get_contents($migrationFile);
    
    // Remove comments first
    $sql = preg_replace('/^--.*$/m', '', $sql);
    $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
    
    // Split by semicolon but handle CREATE VIEW and other multi-line statements
    $statements = [];
    $buffer = '';
    $lines = explode("\n", $sql);
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;
        
        $buffer .= $line . "\n";
        
        // Check if statement is complete (ends with semicolon)
        if (preg_match('/;$/', $line)) {
            $statement = trim($buffer);
            if (!empty($statement)) {
                $statements[] = $statement;
            }
            $buffer = '';
        }
    }
    
    // Add any remaining buffer
    if (!empty(trim($buffer))) {
        $statements[] = trim($buffer);
    }
    
    echo "Found " . count($statements) . " statements to execute\n\n";
    
    $executed = 0;
    $errors = 0;
    
    foreach ($statements as $statement) {
        try {
            // Clean up statement
            $statement = preg_replace('/^--.*$/m', '', $statement);
            $statement = preg_replace('/\/\*.*?\*\//s', '', $statement);
            $statement = trim($statement);
            
            if (empty($statement)) continue;
            
            // Show what we're executing (truncated)
            $preview = substr($statement, 0, 80) . (strlen($statement) > 80 ? '...' : '');
            echo "→ $preview\n";
            
            $db->execute($statement);
            $executed++;
            echo "  ✓ Success\n";
            
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
            
            // Ignore certain expected errors
            if (strpos($errorMsg, 'Duplicate column') !== false ||
                strpos($errorMsg, 'already exists') !== false ||
                strpos($errorMsg, 'Duplicate key') !== false) {
                echo "  ⚠️  Already exists (skipped)\n";
            } else {
                echo "  ✗ Error: " . $errorMsg . "\n";
                $errors++;
            }
        }
        
        echo "\n";
    }
    
    echo "===============================================\n";
    echo "Migration Summary:\n";
    echo "  Statements executed: $executed\n";
    echo "  Errors: $errors\n";
    echo "===============================================\n\n";
    
    if ($errors > 0) {
        echo "⚠️  Migration completed with errors\n";
        exit(1);
    } else {
        echo "✅ Migration completed successfully!\n";
        exit(0);
    }
    
} catch (Exception $e) {
    echo "\n❌ Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
