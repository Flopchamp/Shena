<?php
/**
 * Run STK Push Migration
 * Applies database schema changes for STK push support
 */

// Define ROOT_PATH
define('ROOT_PATH', __DIR__);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/core/Database.php';

echo "\n";
echo "===============================================\n";
echo "   STK Push Database Migration\n";
echo "===============================================\n\n";

try {
    $db = Database::getInstance();
    
    // Read migration file
    $migrationFile = __DIR__ . '/database/migrations/add_stk_push_support.sql';
    
    if (!file_exists($migrationFile)) {
        throw new Exception("Migration file not found: {$migrationFile}");
    }
    
    echo "Reading migration file...\n";
    $sql = file_get_contents($migrationFile);
    
    if (empty($sql)) {
        throw new Exception("Migration file is empty");
    }
    
    echo "Applying database changes...\n\n";
    
    // Split SQL into individual statements
    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        function($stmt) {
            // Ignore empty statements and comments
            $stmt = trim($stmt);
            return !empty($stmt) && 
                   !preg_match('/^--/', $stmt) &&
                   !preg_match('/^\/\*/', $stmt) &&
                   $stmt !== 'DELIMITER //';
        }
    );
    
    $success = 0;
    $failed = 0;
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        
        // Handle DELIMITER changes
        if (stripos($statement, 'DELIMITER') === 0) {
            continue;
        }
        
        // Extract operation type for logging
        preg_match('/^(ALTER|CREATE|INSERT|DROP|DELIMITER)\s+/i', $statement, $matches);
        $operation = $matches[1] ?? 'EXECUTE';
        
        // Get table/object name if possible
        if (preg_match('/(TABLE|VIEW|PROCEDURE)\s+(?:IF\s+(?:NOT\s+)?EXISTS\s+)?`?(\w+)`?/i', $statement, $tableMatches)) {
            $objectType = strtoupper($tableMatches[1]);
            $objectName = $tableMatches[2];
            $description = "{$operation} {$objectType} {$objectName}";
        } else {
            $description = $operation;
        }
        
        try {
            // Execute statement
            $result = $db->execute($statement);
            
            if ($result !== false) {
                echo "✓ {$description}\n";
                $success++;
            } else {
                echo "⚠ {$description} (No rows affected)\n";
                $success++;
            }
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
            
            // Some errors are acceptable (like "already exists")
            if (stripos($errorMsg, 'already exists') !== false ||
                stripos($errorMsg, 'Duplicate') !== false) {
                echo "⚠ {$description} (Already exists)\n";
                $success++;
            } else {
                echo "✗ {$description}\n";
                echo "  Error: {$errorMsg}\n";
                $failed++;
            }
        }
    }
    
    echo "\n===============================================\n";
    echo "   Migration Summary\n";
    echo "===============================================\n";
    echo "Successful: {$success}\n";
    echo "Failed: {$failed}\n";
    echo "Total: " . ($success + $failed) . "\n";
    echo "===============================================\n\n";
    
    if ($failed === 0) {
        echo "✓ Migration completed successfully!\n\n";
        
        echo "Next Steps:\n";
        echo "1. Update your .env file with M-Pesa credentials\n";
        echo "2. Run: php test_stk_push.php\n";
        echo "3. Test STK push in member portal\n\n";
        
        echo "Documentation:\n";
        echo "- Quick Start: STK_PUSH_QUICK_START.md\n";
        echo "- Full Guide: MPESA_STK_PUSH_GUIDE.md\n\n";
        
        exit(0);
    } else {
        echo "⚠ Migration completed with errors.\n";
        echo "Please review the errors above and fix them.\n\n";
        exit(1);
    }
    
} catch (Exception $e) {
    echo "\n❌ Migration Failed!\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n\n";
    exit(1);
}
