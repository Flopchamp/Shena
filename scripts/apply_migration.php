<?php
/**
 * Apply a SQL migration file via PDO using app config
 * Usage: php scripts/apply_migration.php database/migrations/012_update_plan_upgrade_requests_enum.sql
 */

define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/config.php';

$sqlFile = $argv[1] ?? 'database/migrations/012_update_plan_upgrade_requests_enum.sql';
$sqlPath = ROOT_PATH . '/' . ltrim($sqlFile, '/\\');

echo "Applying migration: {$sqlPath}\n";

if (!file_exists($sqlPath)) {
    echo "ERROR: SQL file not found: {$sqlPath}\n";
    exit(1);
}

$sql = file_get_contents($sqlPath);
if ($sql === false) {
    echo "ERROR: Failed to read SQL file\n";
    exit(1);
}

// Strip SQL comments that start with -- and empty lines
$lines = preg_split('/\r?\n/', $sql);
$clean = [];
foreach ($lines as $line) {
    $trim = trim($line);
    if ($trim === '' || strpos($trim, '--') === 0) continue;
    $clean[] = $line;
}
$cleanSql = implode("\n", $clean);

// Remove /* ... */ comments
$cleanSql = preg_replace('#/\*.*?\*/#s', '', $cleanSql);

// Split statements by semicolon; keep semicolons inside strings untouched is hard so we assume simple migrations
$statements = preg_split('/;\s*\n/', $cleanSql);

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $applied = 0;
    foreach ($statements as $stmt) {
        $stmt = trim($stmt);
        if ($stmt === '') continue;
        // Ensure the statement ends with a semicolon; PDO exec does not need it
        try {
            $pdo->exec($stmt);
            echo "✅ Executed statement\n";
            $applied++;
        } catch (PDOException $e) {
            echo "❌ Statement failed: " . $e->getMessage() . "\n";
        }
    }

    echo "\nFinished. Statements applied: {$applied}\n";
    exit(0);

} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
