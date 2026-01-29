<?php
/**
 * Check Age Brackets - Automatically remove children who turned 18 from coverage
 * This script should run daily via cron job
 * 
 * Schedule: php /path/to/check_age_brackets.php (run daily)
 * Cron: 0 1 * * * /usr/bin/php /path/to/Shena/cron/check_dependent_ages.php
 */

// Setup paths
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');

// Load configuration
require_once CONFIG_PATH . '/config.php';

// Load core classes
require_once APP_PATH . '/core/Database.php';
require_once APP_PATH . '/core/BaseModel.php';

// Load models
require_once APP_PATH . '/models/Dependent.php';
require_once APP_PATH . '/models/Member.php';

// Load helper functions
require_once APP_PATH . '/helpers/functions.php';

// Initialize
$dependentModel = new Dependent();
$memberModel = new Member();

$logFile = ROOT_PATH . '/storage/logs/dependent_age_check_' . date('Y-m-d') . '.log';

/**
 * Log message to file
 */
function logMessage($message) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[{$timestamp}] {$message}" . PHP_EOL;
    
    // Ensure log directory exists
    $logDir = dirname($logFile);
    if (!file_exists($logDir)) {
        mkdir($logDir, 0777, true);
    }
    
    file_put_contents($logFile, $logEntry, FILE_APPEND);
    echo $logEntry;
}

logMessage("=== Starting Dependent Age Check Job ===");

try {
    // Check children who turned 18
    $agedOutChildren = $dependentModel->checkChildrenAgeEligibility();
    
    logMessage("Found " . count($agedOutChildren) . " children who turned 18");
    
    foreach ($agedOutChildren as $child) {
        $member = $memberModel->find($child['member_id']);
        if ($member) {
            logMessage("Child aged out: {$child['full_name']} (Member: {$member['member_number']})");
            
            // TODO: Send notification to member about child aging out
            // Suggest they register the child as an independent member
        }
    }
    
    logMessage("=== Age Check Complete ===");
    logMessage("Children aged out: " . count($agedOutChildren));
    
} catch (Exception $e) {
    logMessage("FATAL ERROR: " . $e->getMessage());
    logMessage("Stack trace: " . $e->getTraceAsString());
    exit(1);
}

logMessage("=== Job Finished Successfully ===");
exit(0);
