<?php
session_start();
echo "Current Session Variables:\n";
echo "========================\n";
foreach ($_SESSION as $key => $value) {
    echo "{$key}: " . print_r($value, true) . "\n";
}

if (empty($_SESSION)) {
    echo "No session variables found.\n";
}
?>
