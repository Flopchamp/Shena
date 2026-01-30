# HostPinnacle SMS Integration

The application now uses HostPinnacle for SMS services instead of Twilio.

## Setup Instructions

1. **Get HostPinnacle Credentials**
   - Visit: https://www.hostpinnacle.co.ke
   - Contact: support@hostpinnacle.co.ke or +254 709 943 000
   - Register for an account and get your:
     - User ID
     - API Key
     - Sender ID (default: SHENA)

2. **Update .env File**
   ```env
   HOSTPINNACLE_USER_ID=your_user_id
   HOSTPINNACLE_API_KEY=your_api_key
   HOSTPINNACLE_SENDER_ID=SHENA
   ```

3. **Test SMS Functionality**
   The SMS service will automatically use HostPinnacle for all SMS operations.

## Features

- **SMS Notifications**: Welcome, activation, payment confirmation, claim status
- **Bulk SMS Campaigns**: Send messages to targeted member groups
- **Payment Reminders**: Automated monthly contribution reminders
- **Grace Period Warnings**: Alert members about upcoming account expiration

## API Details

**HostPinnacle API Endpoint:**
```
POST https://sms.hostpinnacle.co.ke/api/services/sendsms/
```

**Request Parameters:**
- `userid`: Your HostPinnacle User ID
- `password`: Your API Key
- `mobile`: Phone number (254XXXXXXXXX format)
- `msg`: Message content
- `senderid`: Sender ID (SHENA)
- `sendMethod`: quick
- `msgType`: text
- `duplicatecheck`: true
- `output`: json

**Response Format:**
```json
{
  "status": "200",
  "message": "SMS sent successfully",
  "messageid": "unique_message_id"
}
```

## Phone Number Format

The system accepts phone numbers in various formats and automatically converts them:
- `0712345678` → `254712345678`
- `+254712345678` → `254712345678`
- `712345678` → `254712345678`
- `254712345678` → `254712345678`

## Error Handling

All SMS errors are logged to the error log. Check your PHP error log for details if SMS sending fails.

## Cost Considerations

- SMS rates vary based on your HostPinnacle package
- Bulk SMS campaigns respect rate limits with 1-second delays between messages
- Consider SMS credit balance before running large campaigns

## Support

For HostPinnacle technical support:
- Email: support@hostpinnacle.co.ke
- Phone: +254 709 943 000
- Website: https://www.hostpinnacle.co.ke
