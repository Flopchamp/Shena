# Email Fallback Feature - Complete Implementation

## Overview
The email fallback feature automatically sends emails when SMS delivery fails, ensuring that critical messages always reach members. This provides reliability and redundancy in the notification system.

## Architecture

### Components

1. **NotificationService** (`app/services/NotificationService.php`)
   - Core service that handles SMS with automatic email fallback
   - Tries SMS first, falls back to email if SMS fails
   - Logs all notification attempts
   - Formats SMS messages as HTML emails

2. **BulkSmsService** (Updated: `app/services/BulkSmsService.php`)
   - Integrated with NotificationService for campaign sending
   - Tracks delivery method (SMS vs Email) for each recipient
   - Returns email fallback count in statistics

3. **SettingsController** (`app/controllers/SettingsController.php`)
   - Manages email fallback settings
   - Provides testing functionality
   - Shows notification statistics

4. **Database Tables**
   - `notification_logs`: Tracks all notification attempts
   - `settings`: Stores email_fallback_enabled setting
   - `bulk_message_recipients`: Added columns for delivery tracking

## Database Schema

### notification_logs table
```sql
CREATE TABLE notification_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    phone VARCHAR(20) NULL,
    email VARCHAR(255) NULL,
    recipient_name VARCHAR(255) NULL,
    method ENUM('sms', 'email', 'failed') NOT NULL,
    status ENUM('success', 'failed') NOT NULL,
    message TEXT NULL,
    notes TEXT NULL,
    created_at DATETIME NOT NULL,
    INDEX idx_phone (phone),
    INDEX idx_email (email),
    INDEX idx_method_status (method, status),
    INDEX idx_created_at (created_at)
);
```

### bulk_message_recipients (new columns)
```sql
ALTER TABLE bulk_message_recipients 
ADD COLUMN email_fallback_sent BOOLEAN DEFAULT FALSE,
ADD COLUMN email_sent_at DATETIME NULL,
ADD COLUMN delivery_method ENUM('sms', 'email', 'failed') NULL;
```

### settings table
```sql
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT NULL,
    setting_type ENUM('boolean', 'string', 'integer', 'json') DEFAULT 'string',
    description TEXT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Default setting
INSERT INTO settings (setting_key, setting_value, setting_type, description) 
VALUES ('email_fallback_enabled', '1', 'boolean', 'Enable automatic email fallback when SMS fails');
```

## Usage

### 1. Sending Notification with Fallback

```php
require_once 'app/services/NotificationService.php';

$notificationService = new NotificationService();

$recipient = [
    'phone' => '254712345678',
    'email' => 'member@example.com',
    'name' => 'John Doe'
];

$result = $notificationService->send(
    $recipient,
    'Your message here',
    'Email Subject',
    null, // Optional: custom email body
    true  // Enable fallback
);

if ($result['success']) {
    echo "Sent via: " . $result['method']; // 'sms' or 'email'
    if (isset($result['fallback_used'])) {
        echo "SMS failed, email fallback was used";
    }
}
```

### 2. Bulk Notifications

```php
$recipients = [
    ['phone' => '254712345678', 'email' => 'user1@example.com', 'name' => 'User 1'],
    ['phone' => '254723456789', 'email' => 'user2@example.com', 'name' => 'User 2'],
];

$results = $notificationService->sendBulk(
    $recipients,
    'Bulk message content',
    'Email Subject',
    null,
    true
);

echo "Total: " . $results['total'];
echo "SMS: " . $results['sent_sms'];
echo "Email Fallbacks: " . $results['sent_email'];
echo "Failed: " . $results['failed'];
```

### 3. Campaign Integration

The BulkSmsService automatically uses email fallback when the setting is enabled:

```php
// In BulkSmsService->sendCampaign()
if ($this->emailFallbackEnabled && $userEmail) {
    $result = $this->notificationService->send(...);
} else {
    $result = $this->smsService->sendSms(...);
}
```

## Admin UI

### 1. Notification Settings Page
URL: `/admin/notification-settings`

Features:
- Toggle email fallback on/off
- View notification statistics (Today, Last 7 Days, Last 30 Days)
- Test fallback functionality
- See SMS vs Email delivery breakdown

### 2. SMS Campaigns Page
URL: `/admin/communications`

Added:
- "Notification Settings" button in header
- Campaign recipients show delivery method
- Email fallback count in campaign results

## API Endpoints

### Update Setting
```
POST /admin/settings/update
Parameters:
  - setting_key: email_fallback_enabled
  - setting_value: 1 or 0
```

### Test Fallback
```
POST /admin/settings/test-fallback
Parameters:
  - phone: Phone number to test
  - email: Email address to test
  - name: Recipient name
```

## Routes Added

```php
// In Router.php
$this->addRoute('GET', '/admin/notification-settings', 'SettingsController@index');
$this->addRoute('POST', '/admin/settings/update', 'SettingsController@update');
$this->addRoute('POST', '/admin/settings/test-fallback', 'SettingsController@testFallback');
```

## Email Template

When SMS fails, the system automatically converts the SMS message to a formatted HTML email:

```html
<html>
<body>
    <div class="container">
        <div class="header">Shena Companion Welfare Association</div>
        <div class="content">
            <p>Dear [Member Name],</p>
            <div class="note">
                📧 Email Delivery Notice: We attempted to send you an SMS 
                but were unable to deliver it. This message has been sent 
                to your email instead.
            </div>
            <div class="message">
                [Original SMS Message]
            </div>
        </div>
        <div class="footer">© 2024 Shena Companion</div>
    </div>
</body>
</html>
```

## Statistics & Monitoring

### Get Notification Stats
```php
$notificationService = new NotificationService();
$stats = $notificationService->getStats($dateFrom, $dateTo);

// Returns:
// [
//     ['method' => 'sms', 'status' => 'success', 'count' => 150],
//     ['method' => 'email', 'status' => 'success', 'count' => 25],
//     ['method' => 'sms', 'status' => 'failed', 'count' => 10]
// ]
```

### Fallback Rate Calculation
```php
$fallbackRate = ($emailSuccess / ($smsSuccess + $emailSuccess)) * 100;
```

## Error Handling

The system gracefully handles various failure scenarios:

1. **SMS API Unavailable**: Falls back to email
2. **Invalid Phone Number**: Falls back to email
3. **Email Delivery Fails**: Logs error, returns failure
4. **Both Methods Fail**: Logs comprehensive error with details

## Logging

All notification attempts are logged to `notification_logs` table:

```php
// Example log entry
[
    'phone' => '254712345678',
    'email' => 'user@example.com',
    'recipient_name' => 'John Doe',
    'method' => 'email',
    'status' => 'success',
    'message' => 'Your payment reminder...',
    'notes' => 'SMS failed, email fallback used',
    'created_at' => '2024-01-15 10:30:00'
]
```

## Configuration

### Enable/Disable Fallback

**Via Database:**
```sql
UPDATE settings SET setting_value = '1' WHERE setting_key = 'email_fallback_enabled';
```

**Via Admin UI:**
1. Go to `/admin/notification-settings`
2. Toggle "Enable Email Fallback" switch

### Per-Message Control

```php
// Disable fallback for specific message
$result = $notificationService->send($recipient, $message, null, null, false);
```

## Testing

### 1. Test Email Fallback
- Go to `/admin/notification-settings`
- Click "Test Fallback" tab
- Enter an invalid phone number (e.g., 254000000000)
- Enter a valid email address
- Click "Send Test"
- Result will show "Email (Fallback)" method

### 2. Test Campaign with Fallback
1. Create a campaign targeting test members
2. Ensure test members have valid emails
3. Send campaign
4. Check `notification_logs` table for delivery methods
5. Review statistics showing SMS vs Email counts

## Performance Considerations

1. **Rate Limiting**: 100ms delay between messages (adjustable)
2. **Batch Processing**: Campaigns processed in batches (default 50)
3. **Email Template**: Pre-formatted to minimize processing
4. **Database Indexes**: Optimized for fast queries

## Troubleshooting

### SMS Not Failing to Test Fallback
- Use an invalid phone format: `254000000000`
- Temporarily disable SMS API credentials
- Use test endpoint: `/admin/settings/test-fallback`

### Emails Not Sending
- Check EmailService configuration
- Verify SMTP settings in config.php
- Check server mail() function availability
- Review error logs

### Statistics Not Showing
- Run migration: `php database/migrations/create_notification_logs.php`
- Check notification_logs table exists
- Verify date range parameters

## Future Enhancements

Potential improvements:
1. Email template customization
2. WhatsApp as tertiary fallback
3. Priority-based delivery (critical messages use email first)
4. Recipient preference management
5. Detailed delivery reports/exports
6. Real-time notification dashboard
7. Webhook notifications for failures

## Security Considerations

1. **Data Protection**: Personal data (phone, email) logged securely
2. **Access Control**: Settings page requires admin/manager role
3. **Input Validation**: All inputs sanitized and validated
4. **Rate Limiting**: Prevents abuse of notification system
5. **CSRF Protection**: All forms include CSRF tokens

## Migration Instructions

To enable email fallback on existing installation:

```bash
# 1. Run migration
php database/migrations/create_notification_logs.php

# 2. Update composer dependencies (if needed)
composer update

# 3. Verify tables created
mysql -u root -p shena_db -e "SHOW TABLES LIKE 'notification%'"

# 4. Test the feature
# Go to /admin/notification-settings and run a test
```

## Support

For issues or questions:
1. Check error logs: `error_log()`
2. Review notification_logs table
3. Test with `/admin/settings/test-fallback`
4. Verify settings table has email_fallback_enabled entry

---

**Version**: 1.0  
**Last Updated**: January 2024  
**Status**: Production Ready ✅
