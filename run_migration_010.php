<?php
/**
 * Run Migration 010 - Add Payout Requests Table
 */

define('ROOT_PATH', __DIR__);
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/core/Database.php';

echo "\n========================================\n";
echo "RUNNING MIGRATION 010\n";
echo "Add Payout Requests Table\n";
echo "========================================\n\n";

try {
    $db = Database::getInstance()->getConnection();
    
    // Read and execute migration file
    $sql = file_get_contents(__DIR__ . '/database/migrations/010_add_payout_requests_table.sql');
    
    // Split by semicolons and execute each statement
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (empty($statement)) continue;
        
        try {
            $db->exec($statement);
            echo "✓ Executed: " . substr($statement, 0, 50) . "...\n";
        } catch (PDOException $e) {
            // Check if error is about table/column already existing
            if (strpos($e->getMessage(), 'Duplicate') !== false || 
                strpos($e->getMessage(), 'already exists') !== false) {
                echo "⊘ Skipped (already exists): " . substr($statement, 0, 50) . "...\n";
            } else {
                throw $e;
            }
        }
    }
    
    echo "\n========================================\n";
    echo "✅ MIGRATION 010 COMPLETED SUCCESSFULLY\n";
    echo "========================================\n\n";
    
    echo "Created:\n";
    echo "- payout_requests table\n";
    echo "- Indexes for performance\n";
    echo "- Foreign key constraints\n\n";

} catch (Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n\n";
    exit(1);
}
