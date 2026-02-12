<?php
/**
 * Test alert messages on claims page
 * This demonstrates both success and error message displays
 */

session_start();

echo "=== Testing Alert Messages ===\n\n";

// Test 1: Set a success message
echo "Test 1: Setting SUCCESS message\n";
$_SESSION['success'] = 'Claim submitted successfully. SHENA Companion will review your claim and contact you within 1-3 business days.';
echo "  ✓ Success message set in session\n";
echo "  Message: " . $_SESSION['success'] . "\n\n";

echo "Now visit: http://localhost:8000/claims\n";
echo "You should see a GREEN banner with success message\n\n";

echo "---\n\n";

// Wait for user input
echo "Press Enter to test ERROR message...";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);

// Test 2: Set an error message
echo "\nTest 2: Setting ERROR message\n";
$_SESSION['error'] = 'Required document missing: ID/Birth Certificate Copy. Please upload all required documents.';
echo "  ✓ Error message set in session\n";
echo "  Message: " . $_SESSION['error'] . "\n\n";

echo "Now visit: http://localhost:8000/claims\n";
echo "You should see a RED banner with error message\n\n";

echo "---\n\n";

// Wait for user input
echo "Press Enter to test MATURITY PERIOD error...";
$line = fgets($handle);

// Test 3: Set a maturity period error
echo "\nTest 3: Setting MATURITY PERIOD error\n";
$_SESSION['error'] = 'Your membership is still in the maturity period. Claims can be submitted after <strong>March 13, 2026</strong> (30 days remaining). The maturity period ensures your membership contributions are up to date before benefit claims can be processed. If you have an urgent situation, please contact SHENA administration for assistance.';
echo "  ✓ Maturity error message set in session\n\n";

echo "Now visit: http://localhost:8000/claims\n";
echo "You should see a RED banner with detailed maturity period message\n\n";

echo "=== Test Complete ===\n";
echo "\nFeatures to verify:\n";
echo "1. ✓ Success banner appears with green gradient and check icon\n";
echo "2. ✓ Error banner appears with red gradient and exclamation icon\n";
echo "3. ✓ Close button (X) works to dismiss alerts\n";
echo "4. ✓ Success alert auto-dismisses after 10 seconds\n";
echo "5. ✓ Smooth slide-down animation on page load\n";
echo "6. ✓ HTML tags in messages render correctly (like <strong>)\n";
