<?php
/**
 * Run Migration Script via PHP
 */

define('ROOT_PATH', __DIR__);
define('CONFIG_PATH', ROOT_PATH . '/config');
require_once CONFIG_PATH . '/config.php';

echo "=== Running Migration: Add Dependents Support ===\n\n";

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "Step 1: Creating dependents table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS dependents (
            id INT AUTO_INCREMENT PRIMARY KEY,
            member_id INT NOT NULL,
            full_name VARCHAR(200) NOT NULL,
            relationship ENUM('spouse', 'child', 'parent', 'father_in_law', 'mother_in_law') NOT NULL,
            id_number VARCHAR(20),
            birth_certificate VARCHAR(50),
            date_of_birth DATE NOT NULL,
            gender ENUM('male', 'female') NOT NULL,
            phone_number VARCHAR(20),
            is_covered BOOLEAN DEFAULT TRUE,
            coverage_start_date DATE NOT NULL,
            coverage_end_date DATE NULL,
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
            INDEX idx_dependents_member_id (member_id),
            INDEX idx_dependents_relationship (relationship),
            INDEX idx_dependents_is_covered (is_covered)
        )
    ");
    echo "✅ Dependents table created\n\n";
    
    echo "Step 2: Adding columns to claims table...\n";
    
    // Check if columns exist before adding
    $stmt = $pdo->query("SHOW COLUMNS FROM claims LIKE 'dependent_id'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE claims ADD COLUMN dependent_id INT NULL AFTER beneficiary_id");
        echo "✅ Added dependent_id column\n";
    } else {
        echo "⏭️  dependent_id column already exists\n";
    }
    
    $stmt = $pdo->query("SHOW COLUMNS FROM claims LIKE 'deceased_type'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE claims ADD COLUMN deceased_type ENUM('member', 'dependent') DEFAULT 'member' AFTER dependent_id");
        echo "✅ Added deceased_type column\n";
    } else {
        echo "⏭️  deceased_type column already exists\n";
    }
    
    $stmt = $pdo->query("SHOW COLUMNS FROM claims LIKE 'date_of_birth'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE claims ADD COLUMN date_of_birth DATE NULL AFTER deceased_id_number");
        echo "✅ Added date_of_birth column\n";
    } else {
        echo "⏭️  date_of_birth column already exists\n";
    }
    
    echo "\nStep 3: Adding foreign key constraint...\n";
    // Check if foreign key exists
    $stmt = $pdo->query("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS 
                         WHERE TABLE_SCHEMA = '" . DB_NAME . "' 
                         AND TABLE_NAME = 'claims' 
                         AND CONSTRAINT_NAME = 'claims_ibfk_3'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE claims ADD CONSTRAINT claims_ibfk_3 
                    FOREIGN KEY (dependent_id) REFERENCES dependents(id) ON DELETE SET NULL");
        echo "✅ Foreign key constraint added\n";
    } else {
        echo "⏭️  Foreign key constraint already exists\n";
    }
    
    echo "\nStep 4: Updating existing claims...\n";
    $pdo->exec("UPDATE claims SET deceased_type = 'member' WHERE deceased_type IS NULL");
    echo "✅ Existing claims updated\n";
    
    echo "\n=== Migration Complete! ===\n";
    echo "✅ All database changes applied successfully\n";
    
} catch (PDOException $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
