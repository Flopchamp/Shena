<?php
/**
 * Migration Runner for Resources Table
 * Run this script to create the resources table and related structures
 */

// Define required constants
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('VIEWS_PATH', ROOT_PATH . '/resources/views');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('STORAGE_PATH', ROOT_PATH . '/storage');

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/core/Database.php';


echo "========================================\n";
echo "Running Migration 011: Resources Table\n";
echo "========================================\n\n";

try {
    $db = Database::getInstance();
    
    // Read and execute the migration SQL
    $sql = file_get_contents(__DIR__ . '/database/migrations/011_add_resources_table.sql');
    
    // Split SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (empty($statement)) continue;
        
        echo "Executing: " . substr($statement, 0, 50) . "...\n";
        
        try {
            $db->execute($statement);
            echo "  ✓ Success\n";
        } catch (Exception $e) {
            // Check if error is about table already existing
            if (strpos($e->getMessage(), 'already exists') !== false) {
                echo "  ℹ Table already exists, skipping...\n";
            } else {
                throw $e;
            }
        }
    }
    
    echo "\n========================================\n";
    echo "Migration 011 completed successfully!\n";
    echo "========================================\n";
    echo "\nCreated tables:\n";
    echo "  - resources (stores resource metadata)\n";
    echo "  - resource_downloads (tracks download history)\n";
    echo "\nNext steps:\n";
    echo "  1. Create storage directory: storage/uploads/resources/\n";
    echo "  2. Ensure the directory is writable (chmod 755)\n";
    echo "  3. Access /admin/agents/resources to upload resources\n";
    echo "  4. Agents will be notified automatically when resources are uploaded\n";
    
} catch (Exception $e) {
    echo "\n❌ Migration failed: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
