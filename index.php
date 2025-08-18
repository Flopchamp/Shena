<?php
/**
 * Shena Companion Welfare Association - Main Entry Point
 * Front Controller Pattern
 */

// Start session
session_start();

// Define constants
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('VIEWS_PATH', ROOT_PATH . '/resources/views');
define('UPLOADS_PATH', ROOT_PATH . '/storage/uploads');

// Auto-load classes
spl_autoload_register(function ($class) {
    $paths = [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
        APP_PATH . '/middleware/',
        APP_PATH . '/services/',
        APP_PATH . '/core/',
        APP_PATH . '/helpers/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Load configuration
require_once CONFIG_PATH . '/config.php';

// Load helper functions
require_once APP_PATH . '/helpers/functions.php';

// Initialize the application
try {
    $router = new Router();
    $router->dispatch();
} catch (Exception $e) {
    error_log($e->getMessage());
    if (DEBUG_MODE) {
        echo '<h1>Error</h1><p>' . $e->getMessage() . '</p>';
        echo '<pre>' . $e->getTraceAsString() . '</pre>';
    } else {
        header('Location: /error/500');
    }
}
