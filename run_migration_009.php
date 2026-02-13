<?php
/**
 * Migration Script: Add Agent Commission System
 * Adds agent_id to members table, creates agents table, and agent_commissions table
 */

// Define ROOT_PATH if not already defined
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', __DIR__ . '/');
}

require_once 'config/config.php';
require_once 'app/core/Database.php';

echo "Starting Migration 009: Add Agent Commission System\n";
echo "==================================================\n";

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();

    // Check if migration has already been run
    $migrationCheck = $pdo->query("SHOW TABLES LIKE 'agents'")->fetch();
    if ($migrationCheck) {
        echo "Migration 009 has already been applied. Skipping...\n";
        exit(0);
    }

    echo "Applying database schema changes...\n";

    // Read and execute the migration SQL
    $migrationSQL = file_get_contents('database/migrations/009_add_agent_commission_system.sql');

    // Split SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $migrationSQL)));

    foreach ($statements as $statement) {
        if (!empty($statement) && !preg_match('/^--/', $statement)) {
            echo "Executing: " . substr($statement, 0, 50) . "...\n";
            $pdo->exec($statement);
        }
    }

    echo "\n✅ Migration 009 completed successfully!\n";
    echo "Added tables: agents, agent_commissions\n";
    echo "Modified tables: members (added agent_id column)\n";

} catch (Exception $e) {
    echo "\n❌ Migration 009 failed: " . $e->getMessage() . "\n";
    exit(1);
}
