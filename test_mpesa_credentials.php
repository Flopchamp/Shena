<?php
// Test M-Pesa access token generation
define('ROOT_PATH', __DIR__);
require_once 'config/config.php';
require_once 'app/services/PaymentService.php';

echo "Testing M-Pesa Configuration...\n\n";

echo "Environment: " . MPESA_ENVIRONMENT . "\n";
echo "Shortcode: " . MPESA_BUSINESS_SHORTCODE . "\n";
echo "Consumer Key: " . substr(MPESA_CONSUMER_KEY, 0, 10) . "...\n";
echo "Consumer Secret: " . substr(MPESA_CONSUMER_SECRET, 0, 10) . "...\n";
echo "Passkey: " . substr(MPESA_PASSKEY, 0, 10) . "...\n\n";

echo "Attempting to get access token...\n";

$paymentService = new PaymentService();
$token = $paymentService->getAccessToken();

if ($token) {
    echo "✅ SUCCESS! Access token obtained: " . substr($token, 0, 20) . "...\n";
    echo "\n✅ M-Pesa configuration is correct!\n";
    echo "You can now use STK Push for payments.\n";
} else {
    echo "❌ FAILED to get access token!\n";
    echo "\nPossible issues:\n";
    echo "1. Consumer Key or Secret is incorrect\n";
    echo "2. Credentials are not for sandbox environment\n";
    echo "3. Network/firewall blocking Safaricom API\n";
    echo "\nTo fix:\n";
    echo "- Verify credentials at https://developer.safaricom.co.ke\n";
    echo "- Make sure you're using SANDBOX credentials\n";
    echo "- Check error_log for detailed error messages\n";
}
