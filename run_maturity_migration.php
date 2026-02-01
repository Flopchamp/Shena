<?php
/**
 * Run migration to add maturity_ends and coverage_ends columns
 */

define('ROOT_PATH', __DIR__);

require_once 'config/config.php';
require_once 'app/core/Database.php';

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    echo "Adding maturity_ends and coverage_ends columns to members table...\n";
    echo str_repeat('=', 80) . "\n\n";
    
    // Read migration file
    $migrationFile = __DIR__ . '/database/migrations/add_maturity_coverage_dates.sql';
    
    if (!file_exists($migrationFile)) {
        throw new Exception("Migration file not found: $migrationFile");
    }
    
    $sql = file_get_contents($migrationFile);
    
    // Remove comments and split into individual statements
    $lines = explode("\n", $sql);
    $statements = [];
    $currentStatement = '';
    
    foreach ($lines as $line) {
        $line = trim($line);
        
        // Skip comments and empty lines
        if (empty($line) || strpos($line, '--') === 0) {
            continue;
        }
        
        $currentStatement .= $line . ' ';
        
        // If line ends with semicolon, we have a complete statement
        if (substr($line, -1) === ';') {
            $statements[] = trim($currentStatement);
            $currentStatement = '';
        }
    }
    
    // Add any remaining statement
    if (!empty(trim($currentStatement))) {
        $statements[] = trim($currentStatement);
    }
    
    $conn->beginTransaction();
    
    foreach ($statements as $statement) {
        if (empty(trim($statement))) continue;
        
        echo "Executing: " . substr($statement, 0, 100) . "...\n";
        $conn->exec($statement);
        echo "✓ Success\n\n";
    }
    
    $conn->commit();
    
    echo str_repeat('=', 80) . "\n";
    echo "Migration completed successfully!\n\n";
    
    // Verify the columns were added
    echo "Verifying table structure...\n";
    $result = $conn->query('DESCRIBE members');
    $columns = $result->fetchAll(PDO::FETCH_ASSOC);
    
    $foundColumns = [];
    foreach ($columns as $column) {
        if (in_array($column['Field'], ['maturity_ends', 'coverage_ends', 'package_key'])) {
            $foundColumns[] = $column['Field'];
            echo "✓ Found column: {$column['Field']} ({$column['Type']})\n";
        }
    }
    
    if (count($foundColumns) === 3) {
        echo "\n✓ All columns added successfully!\n";
    } else {
        $missing = array_diff(['maturity_ends', 'coverage_ends', 'package_key'], $foundColumns);
        echo "\n✗ Missing columns: " . implode(', ', $missing) . "\n";
    }
    
} catch (Exception $e) {
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollback();
    }
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
