# 📧 Email Fallback Feature - Quick Reference

## What It Does
Automatically sends emails when SMS delivery fails, ensuring messages always reach members.

## Quick Access
- **Admin Settings**: [/admin/notification-settings](/admin/notification-settings)
- **SMS Campaigns**: [/admin/communications](/admin/communications)

## Installation Status
✅ **INSTALLED & READY**
- Database tables created
- Services configured  
- Admin UI available
- Currently **ENABLED** by default

## How to Use

### For Admins

**Enable/Disable:**
1. Go to `/admin/notification-settings`
2. Toggle "Enable Email Fallback" switch
3. Done!

**Test It:**
1. Go to Notification Settings → Test Fallback tab
2. Enter:
   - Phone: `254000000000` (invalid to force failure)
   - Email: your email address
3. Click "Send Test"
4. Check your email inbox

**Create Campaigns:**
- Just create SMS campaigns normally
- Email fallback happens automatically when SMS fails
- No extra steps needed!

### For Developers

**Send notification with fallback:**
```php
require_once 'app/services/NotificationService.php';

$service = new NotificationService();
$result = $service->send(
    ['phone' => '254712345678', 'email' => 'user@example.com', 'name' => 'John'],
    'Your message here',
    'Email Subject',
    null,
    true  // Enable fallback
);

if ($result['success']) {
    echo "Sent via: " . $result['method']; // 'sms' or 'email'
}
```

**Check statistics:**
```php
$stats = $service->getStats(date('Y-m-d 00:00:00'));
// Returns delivery counts by method and status
```

## Statistics Available

### Dashboard Shows:
- **Today**: SMS count, Email count, Failed count
- **Last 7 Days**: Total delivered with breakdown
- **Last 30 Days**: Includes fallback rate percentage

### Access Stats:
- Admin UI: `/admin/notification-settings`
- Database: `notification_logs` table

## Files Created/Modified

### New Files:
- `app/services/NotificationService.php`
- `app/controllers/SettingsController.php`
- `resources/views/admin/notification-settings.php`
- `database/migrations/create_notification_logs.php`
- `EMAIL_FALLBACK_FEATURE.md` (full documentation)
- `EMAIL_FALLBACK_IMPLEMENTATION_SUMMARY.md`
- `test_email_fallback.php` (testing script)

### Modified Files:
- `app/services/BulkSmsService.php` (integrated fallback)
- `app/core/Router.php` (added routes)
- `resources/views/admin/sms-campaigns.php` (added button)

## Database Tables

### Created:
- `notification_logs` - Tracks all notification attempts
- `settings` - Stores email_fallback_enabled setting

### Updated:
- `bulk_message_recipients` - Added delivery tracking columns

## Testing

**Run automated tests:**
```bash
php test_email_fallback.php
```

**Expected output:**
```
✅ ALL TESTS PASSED!
Email fallback feature is fully configured and ready to use.
```

## Troubleshooting

### Email fallback not working?
1. Check setting is enabled: `/admin/notification-settings`
2. Verify users have email addresses in database
3. Check EmailService configuration
4. Review `notification_logs` table for errors

### SMS not failing to test fallback?
- Use invalid phone: `254000000000`
- Or use test endpoint: `/admin/settings/test-fallback`

### Statistics not showing?
1. Ensure migration ran: `php database/migrations/create_notification_logs.php`
2. Check `notification_logs` table exists
3. Send some test messages first

## Configuration

### Database Setting:
```sql
-- Enable
UPDATE settings SET setting_value = '1' 
WHERE setting_key = 'email_fallback_enabled';

-- Disable
UPDATE settings SET setting_value = '0' 
WHERE setting_key = 'email_fallback_enabled';
```

### View Current Setting:
```sql
SELECT * FROM settings WHERE setting_key = 'email_fallback_enabled';
```

## Routes

| Method | Route | Purpose |
|--------|-------|---------|
| GET | `/admin/notification-settings` | Settings page |
| POST | `/admin/settings/update` | Toggle fallback |
| POST | `/admin/settings/test-fallback` | Test functionality |

## Key Benefits

✅ **Reliability** - Messages always reach members  
✅ **Automatic** - No manual intervention needed  
✅ **Tracked** - Full logging and statistics  
✅ **Flexible** - Enable/disable anytime  
✅ **Smart** - SMS first (cheaper), email backup  

## Need Help?

1. Read full documentation: `EMAIL_FALLBACK_FEATURE.md`
2. Read implementation summary: `EMAIL_FALLBACK_IMPLEMENTATION_SUMMARY.md`
3. Run test script: `php test_email_fallback.php`
4. Check notification logs: `SELECT * FROM notification_logs ORDER BY created_at DESC LIMIT 20;`

---

**Status**: ✅ Production Ready  
**Version**: 1.0.0  
**Last Updated**: January 31, 2024
