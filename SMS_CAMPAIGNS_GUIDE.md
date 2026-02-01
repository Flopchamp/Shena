# SMS Campaign Management System

## Overview
Comprehensive SMS campaign management system for Shena Companion Welfare Association with queuing, scheduling, and automated sending capabilities.

## Features

### ✅ Campaign Management
- **Create Campaigns**: Design SMS campaigns with custom targeting
- **Schedule Sending**: Set specific date/time for automatic sending
- **Draft Mode**: Save campaigns as drafts before sending
- **Queue Management**: Intelligent message queuing with priority levels
- **Progress Tracking**: Real-time monitoring of campaign progress

### ✅ Targeting Options
- **All Members**: Send to entire membership base
- **Active Members**: Target only active members
- **Grace Period**: Members in grace period
- **Defaulted**: Members who have defaulted
- **Custom Filters**: Advanced filtering by:
  - Package type (Individual, Couple, Family, Executive)
  - Member status
  - Join date ranges
  - County/location

### ✅ SMS Queue System
- **Priority Levels**: Urgent, High, Normal, Low
- **Rate Limiting**: Automatic delays to prevent API throttling
- **Retry Logic**: Automatic retry for failed messages (up to 3 attempts)
- **Scheduled Delivery**: Queue messages for future delivery
- **Batch Processing**: Process large campaigns in manageable batches

### ✅ Templates
- Pre-built message templates for common scenarios
- **Payment Reminder**: Monthly contribution reminders
- **Payment Confirmation**: Receipt notifications
- **Welcome Message**: New member onboarding
- **Claim Updates**: Claim status notifications
- **General Announcements**: Flexible template for any message
- Personalization with {placeholders}

### ✅ Monitoring & Analytics
- **Real-time Statistics**:
  - Active campaigns count
  - Messages sent today
  - Queue pending count
  - SMS credits balance
- **Campaign Reports**:
  - Success rate tracking
  - Failed message analysis
  - Recipient-level status
- **History**: Complete audit trail of all campaigns

## Installation

### Step 1: Run Migration
```bash
php run_sms_campaigns_migration.php
```

This creates:
- `bulk_messages` - Campaign storage
- `bulk_message_recipients` - Recipient tracking
- `sms_queue` - Message queue
- `sms_templates` - Reusable templates
- `sms_credits` - Credit management
- Views for statistics and monitoring

### Step 2: Verify Installation
Check that tables were created:
```sql
SHOW TABLES LIKE 'bulk%';
SHOW TABLES LIKE 'sms%';
```

### Step 3: Access System
Navigate to: `/admin/sms-campaigns`

## Usage Guide

### Creating a Campaign

1. **Click "Create Campaign"**
2. **Fill in Details**:
   - Campaign title (e.g., "Monthly Payment Reminder - January 2026")
   - Select target audience
   - Write message (max 160 characters for single SMS)
   - Choose send time (now or scheduled)
3. **Options**:
   - Use queue for large campaigns (recommended)
   - Schedule for specific date/time
   - Apply custom filters for precise targeting
4. **Save & Send**:
   - "Save as Draft" - Review later
   - "Create & Send" - Start immediately

### Using Templates

1. In campaign creation, select a template from dropdown
2. Message field auto-fills with template content
3. Customize as needed
4. Personalization variables:
   - `{name}` - Member name
   - `{member_number}` - Member ID
   - `{amount}` - Amount due/paid
   - `{receipt}` - Receipt number
   - `{paybill}` - M-Pesa paybill number

### Scheduling Campaigns

1. Select "Schedule for later" in campaign creation
2. Pick date and time
3. Campaign auto-sends at specified time
4. View scheduled campaigns in "Scheduled" tab
5. Can send immediately or reschedule anytime

### Queue Management

**Monitor Queue**:
- Queue tab shows all pending messages
- Status: Pending, Processing, Sent, Failed
- Priority levels displayed
- Retry count shown

**Process Queue Manually**:
- Click "Process Queue" button
- Processes up to 100 messages per batch
- Useful for stuck or delayed messages

**Queue Item Actions**:
- Send pending messages individually
- Retry failed messages
- Delete unwanted queue items

### Quick SMS

For urgent individual messages:
1. Click "Quick SMS"
2. Enter phone number (0712345678 format)
3. Type message
4. Select priority (urgent for immediate sending)
5. Send

Bypasses queue for instant delivery.

## Automated Scheduling

### Setting Up Cron Jobs

For automated sending of scheduled campaigns:

```bash
# Process scheduled campaigns every 5 minutes
*/5 * * * * cd /path/to/Shena && php cron/send_scheduled_campaigns.php

# Process SMS queue every minute
* * * * * cd /path/to/Shena && php cron/process_sms_queue.php
```

### Cron Scripts

**send_scheduled_campaigns.php**:
- Checks for campaigns scheduled to send now
- Starts sending process
- Updates campaign status

**process_sms_queue.php**:
- Processes pending queue items
- Handles retries for failed messages
- Respects rate limits

## Best Practices

### Message Content
- **Keep it concise**: 160 characters for single SMS
- **Clear call-to-action**: Tell members what to do
- **Personalize**: Use {name} and other variables
- **Include contact**: Add support number/email
- **Professional tone**: Maintain brand voice

### Campaign Timing
- **Avoid late hours**: Don't send 10pm-8am
- **Business hours**: 9am-6pm optimal
- **Consider timezone**: Kenya time (EAT)
- **Special days**: Avoid public holidays

### Targeting
- **Segment wisely**: Don't spam entire membership
- **Test first**: Send to small group before mass send
- **Respect preferences**: Honor opt-out requests
- **Relevant content**: Target appropriate members

### Queue Management
- **Use queues**: For campaigns >100 recipients
- **Set priority**: Urgent for critical alerts only
- **Monitor failures**: Check failed messages regularly
- **Batch processing**: 50-100 messages per batch

## Monitoring & Troubleshooting

### Check Campaign Status
1. Navigate to campaign in Campaigns tab
2. View progress bar and statistics
3. Click "View Report" for detailed breakdown
4. Check failed messages for error patterns

### Common Issues

**Campaign stuck "Sending"**:
- Check SMS service credentials
- Verify API limits not exceeded
- Process queue manually
- Check error logs

**High failure rate**:
- Invalid phone numbers in database
- SMS service outage
- Insufficient credits
- API authentication issues

**Queue not processing**:
- Verify cron jobs running
- Check system date/time
- Review queue status in Queue tab
- Check for stuck "processing" items

### Error Logs
Check logs at:
- `storage/logs/sms_*.log` - SMS service logs
- PHP error log - Application errors
- Database error log - Query issues

## SMS Credits Management

### Checking Balance
Dashboard shows current SMS credit balance

### Low Balance Alert
System alerts when credits below threshold (default: 100)

### Purchasing Credits
Contact your SMS provider to top up

### Credit Tracking
- Last purchase amount
- Last purchase date
- Usage statistics

## Security & Permissions

### Access Control
- **Super Admin**: Full access
- **Manager**: Create and send campaigns
- **Member**: No access

### Rate Limiting
- Automatic delays between messages
- API throttling protection
- Batch processing limits

### Data Privacy
- Member phone numbers encrypted
- Campaign content logged securely
- Audit trail maintained
- GDPR compliance ready

## API Integration

### SMS Provider
Currently integrated with:
- **HostPinnacle SMS** (Primary)
- **Twilio** (Fallback)

### Configuration
Set in `.env`:
```
SMS_PROVIDER=hostpinnacle
SMS_API_KEY=your_api_key
SMS_SENDER_ID=SHENA
```

## Support & Documentation

### Getting Help
- Check this README first
- Review error logs
- Test with Quick SMS
- Contact system administrator

### Further Reading
- [HostPinnacle SMS Documentation](docs/HOSTPINNACLE_SMS_SETUP.md)
- [BulkSmsService API](app/services/BulkSmsService.php)
- [SMS Queue Management](database/migrations/add_sms_campaigns.sql)

## Changelog

### Version 1.0 (January 2026)
- ✅ Campaign management system
- ✅ Queue with priority levels
- ✅ Scheduling functionality
- ✅ Template system
- ✅ Real-time monitoring
- ✅ Custom filtering
- ✅ Batch processing
- ✅ Automated sending

## Future Enhancements
- [ ] A/B testing for campaigns
- [ ] Response tracking (opt-out links)
- [ ] Multi-language support
- [ ] SMS conversation threads
- [ ] Advanced analytics dashboard
- [ ] Integration with email campaigns
- [ ] Member segmentation AI

---

**Questions?** Contact the development team or refer to inline code documentation.
