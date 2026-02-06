<?php
/**
 * Debug script to check what's causing blank pages
 */

// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<h1>Debug Information</h1>";

// Check if constants are defined
echo "<h2>1. Constants Check</h2>";
echo "ROOT_PATH defined: " . (defined('ROOT_PATH') ? 'YES' : 'NO') . "<br>";
echo "VIEWS_PATH defined: " . (defined('VIEWS_PATH') ? 'YES' : 'NO') . "<br>";

// Define them if not
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', __DIR__);
    echo "ROOT_PATH set to: " . ROOT_PATH . "<br>";
}

if (!defined('VIEWS_PATH')) {
    define('VIEWS_PATH', ROOT_PATH . '/resources/views');
    echo "VIEWS_PATH set to: " . VIEWS_PATH . "<br>";
}

// Check if view files exist
echo "<h2>2. View Files Check</h2>";
$viewFiles = [
    'header' => VIEWS_PATH . '/layouts/header.php',
    'footer' => VIEWS_PATH . '/layouts/footer.php',
    'about' => VIEWS_PATH . '/public/about.php',
    'membership' => VIEWS_PATH . '/public/membership.php',
    'services' => VIEWS_PATH . '/public/services.php',
    'contact' => VIEWS_PATH . '/public/contact.php'
];

foreach ($viewFiles as $name => $path) {
    echo "$name: " . (file_exists($path) ? '✓ EXISTS' : '✗ MISSING') . " - $path<br>";
}

// Check if helper functions file exists
echo "<h2>3. Helper Functions Check</h2>";
$helpersPath = ROOT_PATH . '/app/helpers/functions.php';
echo "Helpers file: " . (file_exists($helpersPath) ? '✓ EXISTS' : '✗ MISSING') . " - $helpersPath<br>";

if (file_exists($helpersPath)) {
    require_once $helpersPath;
    echo "Helpers loaded successfully<br>";
    
    // Check if functions exist
    $functions = ['isLoggedIn', 'isAdmin', 'getFlashMessage', 'e'];
    foreach ($functions as $func) {
        echo "Function $func: " . (function_exists($func) ? '✓ EXISTS' : '✗ MISSING') . "<br>";
    }
}

// Try to load a view
echo "<h2>4. Test Loading About View</h2>";
session_start();

try {
    $title = 'About Us - Test';
    $page = 'about';
    
    echo "Attempting to include header...<br>";
    ob_start();
    include VIEWS_PATH . '/layouts/header.php';
    $headerOutput = ob_get_clean();
    echo "Header loaded: " . strlen($headerOutput) . " bytes<br>";
    
    echo "Attempting to include about page...<br>";
    ob_start();
    include VIEWS_PATH . '/public/about.php';
    $aboutOutput = ob_get_clean();
    echo "About page loaded: " . strlen($aboutOutput) . " bytes<br>";
    
    echo "Attempting to include footer...<br>";
    ob_start();
    include VIEWS_PATH . '/layouts/footer.php';
    $footerOutput = ob_get_clean();
    echo "Footer loaded: " . strlen($footerOutput) . " bytes<br>";
    
    echo "<h3>✓ All views loaded successfully!</h3>";
    
    echo "<h2>5. Full Page Output</h2>";
    echo "<hr>";
    echo $headerOutput . $aboutOutput . $footerOutput;
    
} catch (Exception $e) {
    echo "<h3 style='color: red;'>✗ Error: " . $e->getMessage() . "</h3>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
