<?php
/**
 * Cron Job: Check Age Brackets
 * 
 * Checks if members are paying the correct contribution for their current age.
 * 
 * Usage: php cron/check_age_brackets.php
 */

define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/app/helpers/functions.php';

// Autoloader
spl_autoload_register(function ($className) {
    $paths = [
        ROOT_PATH . '/app/core/',
        ROOT_PATH . '/app/controllers/',
        ROOT_PATH . '/app/models/',
        ROOT_PATH . '/app/services/'
    ];
    foreach ($paths as $path) {
        if (file_exists($path . $className . '.php')) {
            require_once $path . $className . '.php';
            return;
        }
    }
});

// Set timezone
date_default_timezone_set('Africa/Nairobi');

echo "--- Starting Age Compliance Check: " . date('Y-m-d H:i:s') . " ---\n";

try {
    $db = Database::getInstance();
    
    // Fetch active members with their current contribution info
    $sql = "SELECT m.id, m.user_id, m.member_number, m.package, m.monthly_contribution, m.date_of_birth,
                   u.first_name, u.last_name, u.email, u.phone 
            FROM members m 
            JOIN users u ON m.user_id = u.id 
            WHERE m.status IN ('active', 'grace_period')";
            
    $members = $db->fetchAll($sql);
    echo "Scanning " . count($members) . " members...\n";
    
    $discrepancies = 0;
    
    foreach ($members as $member) {
        if (empty($member['date_of_birth'])) continue;
        
        $age = calculateAge($member['date_of_birth']);
        $currentAmount = (float)$member['monthly_contribution'];
        $packageType = $member['package']; // individual, couple, family, executive
        
        // Determine Expected Amount based on Policy
        $expectedAmount = 0.0;
        $expectedPackageName = '';
        
        if ($packageType === 'individual') {
            if ($age < 70) {
                $expectedAmount = 100.0;
                $expectedPackageName = 'Individual Below 70';
            } elseif ($age >= 71 && $age <= 80) {
                $expectedAmount = 350.0;
                $expectedPackageName = 'Individual 71-80';
            } elseif ($age >= 81 && $age <= 90) {
                $expectedAmount = 450.0;
                $expectedPackageName = 'Individual 81-90';
            } elseif ($age >= 91) {
                $expectedAmount = 650.0;
                $expectedPackageName = 'Individual 91-100';
            }
        } 
        elseif ($packageType === 'executive') {
            if ($age < 70) {
                $expectedAmount = 400.0;
                $expectedPackageName = 'Executive Below 70';
            } else {
                $expectedAmount = 800.0; // Above 70
                $expectedPackageName = 'Executive Above 70';
            }
        }
        else {
            // Skipping complex couple/family logic for MVP auto-check
            continue; 
        }
        
        // Check for mismatch (allow small float variance)
        if ($expectedAmount > 0 && abs($currentAmount - $expectedAmount) > 0.01) {
            
            $discrepancies++;
            echo "[ALERT] Member {$member['member_number']} ({$member['first_name']} {$member['last_name']})\n";
            echo "   - Age: {$age}\n";
            echo "   - Current Contribution: {$currentAmount}\n";
            echo "   - Expected Contribution: {$expectedAmount} ({$expectedPackageName})\n";
            
            $description = "Policy Compliance Alert: Member #{$member['member_number']} (Age {$age}) pays {$currentAmount} but should pay {$expectedAmount} for '{$expectedPackageName}'.";
             
            // Log to activity_logs
            // Note: Schema uses 'details' for description-like field, and 'action' for type.
            $db->insert('activity_logs', [
                 'user_id' => $member['user_id'],
                 'action' => 'policy_audit',
                 'details' => $description,
                 'ip_address' => '127.0.0.1',
                 'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
    
    echo "--- Check Complete. {$discrepancies} discrepancies found. ---\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
