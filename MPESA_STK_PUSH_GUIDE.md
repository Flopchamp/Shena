# M-Pesa STK Push Integration Guide

## Overview
This system now supports M-Pesa STK Push payments using:
- **Sandbox Shortcode**: 174379 (for testing)
- **Production Shortcode**: 4163987 (for live payments)

## Features Implemented

### 1. Environment-Based Configuration
- Automatic switching between sandbox and production
- Separate shortcodes and passkeys for each environment
- Environment-specific API URLs

### 2. STK Push Functionality
- Initiate STK push directly from the system
- Automatic payment prompts sent to user's phone
- Real-time payment status tracking
- Automatic reconciliation after successful payment

### 3. User Interface Updates
- **Member Payment Page**: Toggle between STK Push and Manual Paybill
- **Plan Upgrade**: STK push integration for upgrade payments
- **Reactivation**: STK push for account reactivation
- All payment types now support STK push

### 4. Database Schema
- New table: `mpesa_stk_push_logs` for detailed tracking
- New columns in `payments` table for STK data
- Views for pending and failed STK pushes
- Stored procedure for timeout handling

## Setup Instructions

### Step 1: Configure Environment Variables

Create/update `.env` file in the project root:

```env
# M-Pesa Configuration
MPESA_ENVIRONMENT=sandbox
# Use 'production' when ready for live payments

# Get these from Safaricom Developer Portal
MPESA_CONSUMER_KEY=your_consumer_key_here
MPESA_CONSUMER_SECRET=your_consumer_secret_here

# Sandbox credentials (for testing)
MPESA_SANDBOX_PASSKEY=bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919

# Production credentials (when ready)
MPESA_PRODUCTION_PASSKEY=your_production_passkey_here

# Callback URLs (update with your domain)
MPESA_STK_CALLBACK_URL=https://yourdomain.com/public/mpesa-stk-callback.php
MPESA_C2B_CALLBACK_URL=https://yourdomain.com/public/mpesa-c2b-callback.php

APP_URL=https://yourdomain.com
```

### Step 2: Run Database Migration

```bash
mysql -u root -p shena_welfare_db < database/migrations/add_stk_push_support.sql
```

Or via PHP:

```bash
php run_migration.php add_stk_push_support
```

### Step 3: Configure Safaricom Developer Portal

1. **Login** to https://developer.safaricom.co.ke
2. **Create App** or use existing app
3. **Get Credentials**: Consumer Key & Consumer Secret
4. **Register URLs**:
   - STK Push Callback: `https://yourdomain.com/public/mpesa-stk-callback.php`
   - C2B Callback: `https://yourdomain.com/public/mpesa-c2b-callback.php`
5. **Test in Sandbox** first before production

### Step 4: Test Integration

Run the test script:

```bash
php test_stk_push.php
```

This will verify:
- Configuration is correct
- Access tokens can be generated
- Phone number formatting works
- (Optional) Real STK push initiation

### Step 5: Configure Webhook URLs

Ensure your server can receive callbacks:

1. **Make URLs publicly accessible**
2. **Use HTTPS** (required by Safaricom)
3. **Test callback reception**:
   ```bash
   curl -X POST https://yourdomain.com/public/mpesa-stk-callback.php \
   -H "Content-Type: application/json" \
   -d '{"Body":{"stkCallback":{"ResultCode":"0"}}}'
   ```

4. **Check logs**:
   - STK callbacks: `storage/logs/mpesa_stk_YYYY-MM-DD.log`
   - C2B callbacks: `storage/logs/mpesa_c2b_YYYY-MM-DD.log`

## Usage

### For Members

1. **Make Payment**:
   - Go to "Payments" page
   - Click "Make Payment"
   - Choose "STK Push" option
   - Enter phone number
   - Submit - payment prompt sent to phone
   - Enter M-Pesa PIN to complete

2. **Plan Upgrade**:
   - Go to "Upgrade Package"
   - Select new package
   - Enter phone number
   - STK push will be sent automatically

3. **Reactivation**:
   - If account is defaulted
   - Click "Reactivate Account"
   - Choose STK Push option
   - Complete payment on phone

### For Administrators

1. **Monitor Payments**:
   - View all payments in admin dashboard
   - Check STK push logs in database
   - View failed/pending pushes

2. **Manual Reconciliation**:
   - If STK push fails, use manual reconciliation
   - Match payments by phone or member number

## Sandbox Testing

### Test Phone Numbers
Safaricom provides test numbers for sandbox:
- `254708374149`
- `254720000001`
- `254722000001`

### Test Credentials
- Shortcode: `174379`
- Passkey: `bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919`

### Testing Flow
1. Use test phone numbers
2. Enter M-Pesa PIN: `1234` (sandbox default)
3. Check callback logs for results
4. Verify payment status updates

## Going Live (Production)

### Checklist
- [ ] Get production credentials from Safaricom
- [ ] Update `.env` with production keys
- [ ] Set `MPESA_ENVIRONMENT=production`
- [ ] Configure production callback URLs
- [ ] Test with small amounts first
- [ ] Monitor logs for issues
- [ ] Enable SSL/HTTPS (required)

### Production Shortcode
- Paybill: `4163987`
- Get production passkey from Safaricom

## Troubleshooting

### Common Issues

1. **"Failed to get access token"**
   - Check consumer key/secret in `.env`
   - Verify credentials are for correct environment
   - Check internet connection

2. **"Invalid phone number"**
   - Use format: `0712345678` or `254712345678`
   - Remove spaces and special characters
   - Must be valid Kenyan number

3. **"STK push not received"**
   - Check phone number is M-Pesa registered
   - Phone must be on and have network
   - Check M-Pesa message inbox
   - Verify shortcode is correct

4. **"Callback not received"**
   - Check callback URL is publicly accessible
   - Must use HTTPS (not HTTP)
   - Check server firewall settings
   - Review callback logs

### Debug Mode

Enable detailed logging:
```php
define('DEBUG_MODE', true);
```

Check logs at:
- `storage/logs/mpesa_stk_YYYY-MM-DD.log`
- `storage/logs/mpesa_c2b_YYYY-MM-DD.log`

## API Endpoints

### Initiate STK Push
```
POST /payment/initiate
Content-Type: application/json

{
  "member_id": 123,
  "phone_number": "0712345678",
  "amount": 500,
  "payment_type": "monthly"
}
```

### Query Payment Status
```
GET /payment/status?checkout_request_id=ws_CO_XXXXXX
```

### STK Push Callback (M-Pesa)
```
POST /public/mpesa-stk-callback.php
```

### C2B Callback (M-Pesa)
```
POST /public/mpesa-c2b-callback.php
```

## Security Notes

1. **Never commit credentials** to version control
2. **Use environment variables** for all sensitive data
3. **Enable HTTPS** for all callback URLs
4. **Validate all callbacks** before processing
5. **Log all transactions** for audit trail
6. **Use production credentials carefully**

## Support

For issues or questions:
- Check logs first
- Review Safaricom API documentation
- Contact Safaricom developer support
- Review this guide thoroughly

## Files Modified/Created

### Configuration
- `config/config.php` - Added environment-based M-Pesa config

### Services
- `app/services/PaymentService.php` - Enhanced with STK push

### Callbacks
- `public/mpesa-stk-callback.php` - STK push callback handler
- `public/mpesa-c2b-callback.php` - C2B callback handler (existing)

### Views
- `resources/views/member/payments.php` - STK push UI
- `resources/views/member/upgrade.php` - Upgrade with STK
- `resources/views/member/dashboard.php` - Reactivation with STK

### Database
- `database/migrations/add_stk_push_support.sql` - Schema changes

### Tests
- `test_stk_push.php` - Integration test script

## Summary

✅ Sandbox shortcode 174379 implemented
✅ Production shortcode 4163987 configured
✅ STK Push available on all payment pages
✅ Automatic payment reconciliation
✅ Comprehensive logging and tracking
✅ Test scripts included
✅ Production-ready

The system is now ready for M-Pesa STK Push testing in sandbox, and can be switched to production when ready.
