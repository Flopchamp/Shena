<?php
/**
 * Test HostPinnacle SMS Integration
 * Run this script to test if SMS sending works with HostPinnacle
 */

define('ROOT_PATH', __DIR__);
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/app/core/Database.php';
require_once ROOT_PATH . '/app/services/SmsService.php';

echo "=== HostPinnacle SMS Integration Test ===\n\n";

// Check if credentials are configured
echo "1. Checking HostPinnacle credentials...\n";
if (empty(HOSTPINNACLE_USER_ID) || empty(HOSTPINNACLE_API_KEY)) {
    echo "   ❌ ERROR: HostPinnacle credentials not configured!\n";
    echo "   Please update your .env file with:\n";
    echo "   - HOSTPINNACLE_USER_ID\n";
    echo "   - HOSTPINNACLE_API_KEY\n";
    echo "   - HOSTPINNACLE_SENDER_ID (optional, defaults to SHENA)\n\n";
    exit(1);
}

echo "   ✓ User ID: " . substr(HOSTPINNACLE_USER_ID, 0, 4) . "****\n";
echo "   ✓ API Key: " . substr(HOSTPINNACLE_API_KEY, 0, 4) . "****\n";
echo "   ✓ Sender ID: " . HOSTPINNACLE_SENDER_ID . "\n\n";

// Test phone number formatting
echo "2. Testing phone number formatting...\n";
$smsService = new SmsService();

$testNumbers = [
    '0712345678',
    '+254712345678',
    '254712345678',
    '712345678'
];

foreach ($testNumbers as $number) {
    $reflection = new ReflectionClass($smsService);
    $method = $reflection->getMethod('formatPhoneNumber');
    $method->setAccessible(true);
    $formatted = $method->invoke($smsService, $number);
    echo "   {$number} → {$formatted}\n";
}
echo "\n";

// Prompt for test SMS
echo "3. Would you like to send a test SMS? (yes/no): ";
$handle = fopen("php://stdin", "r");
$response = trim(fgets($handle));

if (strtolower($response) === 'yes') {
    echo "   Enter phone number (e.g., 0712345678): ";
    $phoneNumber = trim(fgets($handle));
    
    if (empty($phoneNumber)) {
        echo "   ❌ No phone number provided. Test cancelled.\n";
        exit(0);
    }
    
    // Validate phone number
    if (!$smsService->validatePhoneNumber($phoneNumber)) {
        echo "   ⚠ Warning: Phone number format may be invalid.\n";
        echo "   Continuing anyway...\n";
    }
    
    // Send test SMS
    echo "\n   Sending test SMS...\n";
    $message = "This is a test message from Shena Companion Welfare Association. HostPinnacle SMS integration is working!";
    
    $result = $smsService->sendSms($phoneNumber, $message);
    
    if ($result) {
        echo "   ✓ SMS sent successfully!\n";
        echo "   Response: " . json_encode($result, JSON_PRETTY_PRINT) . "\n";
    } else {
        echo "   ❌ Failed to send SMS. Check error logs for details.\n";
        echo "   Common issues:\n";
        echo "   - Invalid credentials\n";
        echo "   - Insufficient SMS credits\n";
        echo "   - Invalid phone number\n";
        echo "   - Network connectivity issues\n";
    }
} else {
    echo "   Test SMS cancelled.\n";
}

fclose($handle);

echo "\n=== Test Complete ===\n";
