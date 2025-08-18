<?php
/**
 * Test Database Connection
 */

// Define ROOT_PATH constant
define('ROOT_PATH', __DIR__);

// Include configuration
require_once 'config/config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Successfully connected to MySQL server\n";
    
    // Check if database exists
    $stmt = $pdo->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?");
    $stmt->execute([DB_NAME]);
    $database = $stmt->fetch();
    
    if ($database) {
        echo "✅ Database '" . DB_NAME . "' exists\n";
        
        // Connect to the specific database
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        
        // Check if tables exist
        $stmt = $pdo->prepare("SHOW TABLES");
        $stmt->execute();
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($tables) > 0) {
            echo "✅ Database has " . count($tables) . " tables:\n";
            foreach ($tables as $table) {
                echo "   - $table\n";
            }
        } else {
            echo "⚠️  Database exists but has no tables\n";
        }
        
    } else {
        echo "⚠️  Database '" . DB_NAME . "' does not exist\n";
        echo "You need to create the database and import the schema\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    echo "Check your database configuration in config/config.php\n";
}
?>
