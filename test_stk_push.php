<?php
/**
 * M-Pesa STK Push Test Script
 * Tests the STK Push integration with Sandbox Shortcode 174379
 */

// Define ROOT_PATH before including config
define('ROOT_PATH', __DIR__);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/services/PaymentService.php';

class STKPushTest
{
    private $paymentService;
    private $passed = 0;
    private $failed = 0;
    
    public function __construct()
    {
        $this->paymentService = new PaymentService();
    }
    
    public function runAllTests()
    {
        echo "===============================================\n";
        echo "   M-PESA STK PUSH INTEGRATION TEST\n";
        echo "   Sandbox Shortcode: 174379\n";
        echo "===============================================\n\n";
        
        // Configuration tests
        $this->testConfiguration();
        
        // Service tests
        $this->testAccessToken();
        $this->testPhoneNumberFormatting();
        
        // STK Push tests (commented out to avoid real API calls)
        // Uncomment when ready to test with real credentials
        // $this->testSTKPushInitiation();
        
        echo "\n===============================================\n";
        echo "   TEST SUMMARY\n";
        echo "===============================================\n";
        echo "Passed: " . $this->passed . "\n";
        echo "Failed: " . $this->failed . "\n";
        echo "Total:  " . ($this->passed + $this->failed) . "\n";
        echo "===============================================\n\n";
        
        echo "NOTE: STK Push initiation test is commented out.\n";
        echo "      To test with real API, update credentials in .env and uncomment testSTKPushInitiation().\n\n";
        
        return $this->failed === 0;
    }
    
    private function testConfiguration()
    {
        echo "Testing Configuration...\n";
        
        // Check environment
        $this->assert(
            defined('MPESA_ENVIRONMENT'),
            "MPESA_ENVIRONMENT is defined"
        );
        
        echo "  Environment: " . MPESA_ENVIRONMENT . "\n";
        
        // Check sandbox shortcode
        $this->assert(
            defined('MPESA_SANDBOX_SHORTCODE') && MPESA_SANDBOX_SHORTCODE === '174379',
            "Sandbox shortcode is 174379"
        );
        
        // Check production shortcode
        $this->assert(
            defined('MPESA_PRODUCTION_SHORTCODE') && MPESA_PRODUCTION_SHORTCODE === '4163987',
            "Production shortcode is 4163987"
        );
        
        // Check active shortcode
        $expectedShortcode = MPESA_ENVIRONMENT === 'production' ? '4163987' : '174379';
        $this->assert(
            MPESA_BUSINESS_SHORTCODE === $expectedShortcode,
            "Active shortcode matches environment: " . MPESA_BUSINESS_SHORTCODE
        );
        
        // Check passkey
        $this->assert(
            defined('MPESA_PASSKEY') && !empty(MPESA_PASSKEY),
            "M-Pesa passkey is configured"
        );
        
        // Check callback URLs
        $this->assert(
            defined('MPESA_STK_CALLBACK_URL') && !empty(MPESA_STK_CALLBACK_URL),
            "STK callback URL is configured: " . MPESA_STK_CALLBACK_URL
        );
        
        $this->assert(
            defined('MPESA_C2B_CALLBACK_URL') && !empty(MPESA_C2B_CALLBACK_URL),
            "C2B callback URL is configured: " . MPESA_C2B_CALLBACK_URL
        );
        
        // Check credentials
        if (empty(MPESA_CONSUMER_KEY) || empty(MPESA_CONSUMER_SECRET)) {
            echo "  ⚠️  WARNING: M-Pesa credentials not configured in .env\n";
        } else {
            echo "  ✓ M-Pesa credentials are configured\n";
        }
        
        echo "\n";
    }
    
    private function testAccessToken()
    {
        echo "Testing Access Token Generation...\n";
        
        if (empty(MPESA_CONSUMER_KEY) || empty(MPESA_CONSUMER_SECRET)) {
            echo "  ⚠️  Skipping - credentials not configured\n\n";
            return;
        }
        
        try {
            $token = $this->paymentService->getAccessToken();
            
            if ($token) {
                $this->assert(true, "Access token obtained successfully");
                echo "  Token (first 20 chars): " . substr($token, 0, 20) . "...\n";
            } else {
                $this->assert(false, "Failed to obtain access token");
                echo "  Check your consumer key and secret in .env\n";
            }
        } catch (Exception $e) {
            $this->assert(false, "Access token error: " . $e->getMessage());
        }
        
        echo "\n";
    }
    
    private function testPhoneNumberFormatting()
    {
        echo "Testing Phone Number Formatting...\n";
        
        $reflection = new ReflectionClass($this->paymentService);
        $method = $reflection->getMethod('formatPhoneNumber');
        $method->setAccessible(true);
        
        $tests = [
            '0712345678' => '254712345678',
            '712345678' => '254712345678',
            '254712345678' => '254712345678',
            '+254712345678' => '254712345678',
            '0722334455' => '254722334455'
        ];
        
        foreach ($tests as $input => $expected) {
            $result = $method->invoke($this->paymentService, $input);
            $this->assert(
                $result === $expected,
                "Format {$input} -> {$expected} (got: {$result})"
            );
        }
        
        // Test invalid formats
        $invalidTests = ['123', '25412345', 'invalid'];
        foreach ($invalidTests as $input) {
            $result = $method->invoke($this->paymentService, $input);
            $this->assert(
                $result === false,
                "Invalid format '{$input}' rejected"
            );
        }
        
        echo "\n";
    }
    
    private function testSTKPushInitiation()
    {
        echo "Testing STK Push Initiation...\n";
        
        if (empty(MPESA_CONSUMER_KEY) || empty(MPESA_CONSUMER_SECRET)) {
            echo "  ⚠️  Skipping - credentials not configured\n\n";
            return;
        }
        
        // Use test phone number from Safaricom sandbox
        // Sandbox test numbers: 254708374149, 254720000001, etc.
        $testPhone = '254708374149';
        $testAmount = 1;
        $testMemberNumber = 'TEST001';
        $testDescription = 'STK Push Test';
        
        echo "  Initiating STK Push to {$testPhone} for KES {$testAmount}\n";
        
        try {
            $response = $this->paymentService->initiateSTKPush(
                $testPhone,
                $testAmount,
                $testMemberNumber,
                $testDescription
            );
            
            if ($response && isset($response['ResponseCode']) && $response['ResponseCode'] == '0') {
                $this->assert(true, "STK Push initiated successfully");
                echo "  CheckoutRequestID: " . ($response['CheckoutRequestID'] ?? 'N/A') . "\n";
                echo "  MerchantRequestID: " . ($response['MerchantRequestID'] ?? 'N/A') . "\n";
                echo "  CustomerMessage: " . ($response['CustomerMessage'] ?? 'N/A') . "\n";
                
                // Wait a bit for callback
                echo "\n  Waiting 10 seconds for callback...\n";
                sleep(10);
                
                // Check callback log
                $logFile = ROOT_PATH . '/storage/logs/mpesa_stk_' . date('Y-m-d') . '.log';
                if (file_exists($logFile)) {
                    echo "  Callback log exists. Check: {$logFile}\n";
                }
            } else {
                $this->assert(false, "STK Push failed");
                echo "  Response: " . json_encode($response) . "\n";
            }
        } catch (Exception $e) {
            $this->assert(false, "STK Push error: " . $e->getMessage());
        }
        
        echo "\n";
    }
    
    private function assert($condition, $message)
    {
        if ($condition) {
            echo "  ✓ {$message}\n";
            $this->passed++;
        } else {
            echo "  ✗ {$message}\n";
            $this->failed++;
        }
    }
}

// Run tests
echo "\n";
$test = new STKPushTest();
$success = $test->runAllTests();

exit($success ? 0 : 1);
