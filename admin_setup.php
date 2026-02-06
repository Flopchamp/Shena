<?php
define('ROOT_PATH', __DIR__);

try {
    require_once 'config/config.php';
} catch (Exception $e) {
    die("Configuration error: Unable to load config file.\n");
}

if (!defined('DB_HOST') || !defined('DB_NAME') || !defined('DB_USER') || !defined('DB_PASS')) {
    die("Database configuration missing. Please check config.php\n");
}

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if admin user exists
    $stmt = $pdo->prepare('SELECT id, email, role FROM users WHERE role IN (?, ?)');
    $stmt->execute(['super_admin', 'manager']);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($users)) {
        echo "No admin users found. Creating default admin user...\n";
        
        // Create admin user
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (first_name, last_name, email, phone, password, role, status, email_verified_at, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), NOW())');
        $stmt->execute(['System', 'Administrator', 'admin@shenacompanion.org', '+254700000000', $hashedPassword, 'super_admin', 'active']);
        
        echo "Admin user created successfully!\n";
        echo "Email: admin@shenacompanion.org\n";
        echo "Password: admin123\n";
        echo "Role: super_admin\n";
    } else {
        echo "Found " . count($users) . " admin user(s):\n";
        foreach ($users as $user) {
            echo "- ID: " . $user['id'] . ", Email: " . $user['email'] . ", Role: " . $user['role'] . "\n";
        }
    }
} catch (PDOException $e) {
    error_log("Admin setup database error: " . $e->getMessage());
    die("Database connection failed. Check error logs for details.\n");
} catch (Exception $e) {
    error_log("Admin setup error: " . $e->getMessage());
    die("Setup failed. Check error logs for details.\n");
}
