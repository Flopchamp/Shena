<?php
/**
 * Check email configuration
 */

define('ROOT_PATH', __DIR__);
require_once 'config/config.php';

echo "=== EMAIL CONFIGURATION CHECK ===\n";
echo str_repeat('=', 80) . "\n\n";

echo "Email Settings:\n";
echo str_pad("MAIL_ENABLED:", 20) . (MAIL_ENABLED ? "✓ Yes" : "✗ No (DISABLED)") . "\n";
echo str_pad("MAIL_HOST:", 20) . MAIL_HOST . "\n";
echo str_pad("MAIL_PORT:", 20) . MAIL_PORT . "\n";
echo str_pad("MAIL_USERNAME:", 20) . (empty(MAIL_USERNAME) ? "✗ Not set" : "✓ " . MAIL_USERNAME) . "\n";
echo str_pad("MAIL_PASSWORD:", 20) . (empty(MAIL_PASSWORD) ? "✗ Not set" : "✓ Set (hidden)") . "\n";
echo str_pad("MAIL_FROM_EMAIL:", 20) . MAIL_FROM_EMAIL . "\n";
echo str_pad("MAIL_FROM_NAME:", 20) . MAIL_FROM_NAME . "\n";

echo "\n" . str_repeat('=', 80) . "\n";

// Check if credentials are configured
$issues = [];

if (!MAIL_ENABLED) {
    $issues[] = "Email is DISABLED. Set MAIL_ENABLED to true in config.php";
}

if (empty(MAIL_USERNAME)) {
    $issues[] = "MAIL_USERNAME is not set. Set environment variable or update config.php";
}

if (empty(MAIL_PASSWORD)) {
    $issues[] = "MAIL_PASSWORD is not set. Set environment variable or update config.php";
}

if (!empty($issues)) {
    echo "\n⚠️  Issues Found:\n";
    foreach ($issues as $i => $issue) {
        echo ($i + 1) . ". " . $issue . "\n";
    }
    
    echo "\n📝 To fix:\n";
    echo "1. Create/edit .env file or set environment variables:\n";
    echo "   MAIL_USERNAME=your-email@gmail.com\n";
    echo "   MAIL_PASSWORD=your-app-password\n";
    echo "2. Or update config/config.php directly (not recommended for production)\n";
    echo "3. Set MAIL_ENABLED = true in config/config.php\n";
    echo "\n💡 For Gmail, use an App Password (not your regular password):\n";
    echo "   https://myaccount.google.com/apppasswords\n";
} else {
    echo "\n✅ Email configuration looks good!\n";
    echo "\nNote: If emails still fail, check:\n";
    echo "- Gmail: Enable 'Less secure app access' or use App Password\n";
    echo "- Firewall: Allow outbound connections on port " . MAIL_PORT . "\n";
    echo "- SMTP: Verify host and port settings\n";
}
