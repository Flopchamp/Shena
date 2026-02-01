<?php
/**
 * Clear members table for fresh testing
 * WARNING: This will delete all members and related data (payments, claims, etc.)
 */

define('ROOT_PATH', __DIR__);

require_once 'config/config.php';
require_once 'app/core/Database.php';

echo "=== CLEAR MEMBERS TABLE ===\n";
echo "WARNING: This will delete ALL members and related data!\n";
echo str_repeat('=', 80) . "\n\n";

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    // Start transaction
    $conn->beginTransaction();
    
    // Count existing data
    $memberCount = $conn->query("SELECT COUNT(*) FROM members")->fetchColumn();
    $userCount = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'member'")->fetchColumn();
    $paymentCount = $conn->query("SELECT COUNT(*) FROM payments")->fetchColumn();
    $claimCount = $conn->query("SELECT COUNT(*) FROM claims")->fetchColumn();
    
    echo "Current data:\n";
    echo "- Members: {$memberCount}\n";
    echo "- Member Users: {$userCount}\n";
    echo "- Payments: {$paymentCount}\n";
    echo "- Claims: {$claimCount}\n\n";
    
    if ($memberCount > 0) {
        echo "Deleting all members...\n";
        
        // Delete members (this will cascade delete payments, claims, beneficiaries, dependents)
        $conn->exec("DELETE FROM members");
        echo "✓ Deleted {$memberCount} members\n";
        
        // Delete member users
        $conn->exec("DELETE FROM users WHERE role = 'member'");
        echo "✓ Deleted {$userCount} member users\n";
        
        // Commit transaction before ALTER TABLE (DDL statements auto-commit)
        $conn->commit();
        
        // Reset auto-increment (DDL statement)
        $conn->exec("ALTER TABLE members AUTO_INCREMENT = 1");
        $conn->exec("ALTER TABLE users AUTO_INCREMENT = 1");
        echo "✓ Reset auto-increment counters\n";
    } else {
        echo "No members to delete.\n";
        $conn->commit();
    }
    
    echo "\n" . str_repeat('=', 80) . "\n";
    echo "✅ Members table cleared successfully!\n";
    echo "You can now start fresh testing.\n";
    
} catch (Exception $e) {
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollback();
    }
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
