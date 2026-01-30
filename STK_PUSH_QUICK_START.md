# STK Push Implementation - Quick Start Guide

## What Was Implemented

✅ **Sandbox Business Shortcode 174379** for testing
✅ **Production Business Shortcode 4163987** for live payments  
✅ **STK Push integration** across all payment points
✅ **Automatic environment switching** (sandbox/production)
✅ **Enhanced callback handling** for payment confirmations
✅ **Database schema updates** for STK tracking
✅ **User-friendly payment UI** with STK push option

## Quick Setup (5 Minutes)

### 1. Run Database Migration
```bash
cd c:\xampp\htdocs\Shena
mysql -u root -p4885 shena_welfare_db < database/migrations/add_stk_push_support.sql
```

### 2. Create .env File (if not exists)
```bash
copy .env.example .env
```

### 3. Update .env with M-Pesa Credentials
```env
MPESA_ENVIRONMENT=sandbox
MPESA_CONSUMER_KEY=your_key_from_safaricom
MPESA_CONSUMER_SECRET=your_secret_from_safaricom
MPESA_STK_CALLBACK_URL=https://yourdomain.com/public/mpesa-stk-callback.php
```

### 4. Test Configuration
```bash
php test_stk_push.php
```

### 5. Test in Browser
1. Login as a member
2. Go to "Payments" page
3. Click "Make Payment"
4. Select "STK Push" option
5. Enter phone number
6. Submit (use sandbox test number: 254708374149)

## Where STK Push is Available

### Member Portal
1. **Monthly Contributions** (`/member/payments`)
   - Toggle between STK Push and Manual Paybill
   - Instant payment prompt to phone

2. **Plan Upgrades** (`/member/upgrade`)
   - Automatic STK push for upgrade fees
   - Real-time status tracking

3. **Account Reactivation** (`/member/dashboard`)
   - STK push for reactivation payments
   - Includes arrears calculation

4. **Registration Fees** (Auth flow)
   - New members can pay via STK push
   - Auto-activation on payment completion

## Testing in Sandbox

### Sandbox Credentials
- **Shortcode**: 174379
- **Passkey**: Already configured
- **Test Phone**: 254708374149
- **Test PIN**: 1234 (sandbox default)

### Test Flow
1. Set `MPESA_ENVIRONMENT=sandbox` in `.env`
2. Use test phone number above
3. Initiate payment from any payment page
4. Check phone for prompt (simulated in sandbox)
5. View logs in `storage/logs/mpesa_stk_YYYY-MM-DD.log`

## Going to Production

When ready for live payments:

1. **Get Production Credentials**
   - Login to Safaricom Daraja Portal
   - Get production consumer key/secret
   - Get production passkey

2. **Update .env**
   ```env
   MPESA_ENVIRONMENT=production
   MPESA_CONSUMER_KEY=production_key
   MPESA_CONSUMER_SECRET=production_secret
   MPESA_PRODUCTION_PASSKEY=production_passkey
   ```

3. **Configure Callback URLs**
   - Must be HTTPS (SSL certificate required)
   - Register in Safaricom portal

4. **Test with Small Amounts**
   - Start with KES 1 payments
   - Verify callback reception
   - Check payment reconciliation

## Troubleshooting

### Common Issues

**"Failed to get access token"**
- Check consumer key/secret in `.env`
- Verify credentials match environment

**"STK push not received"**
- Ensure phone is M-Pesa registered
- Check phone has network
- Verify shortcode is correct

**"Callback not received"**
- Callback URL must be publicly accessible
- Must use HTTPS (not HTTP)
- Check firewall settings

### Check Logs
```bash
# STK Push logs
tail -f storage/logs/mpesa_stk_2026-01-30.log

# C2B Payment logs
tail -f storage/logs/mpesa_c2b_2026-01-30.log
```

## Key Files

### Configuration
- `config/config.php` - M-Pesa settings
- `.env` - Your credentials (never commit!)

### Services
- `app/services/PaymentService.php` - STK push logic

### Callbacks
- `public/mpesa-stk-callback.php` - Handles STK responses
- `public/mpesa-c2b-callback.php` - Handles C2B payments

### Views (Updated)
- `resources/views/member/payments.php` - Payment page
- `resources/views/member/upgrade.php` - Upgrade page
- `resources/views/member/dashboard.php` - Dashboard

### Database
- `database/migrations/add_stk_push_support.sql` - Schema

### Tests
- `test_stk_push.php` - Integration tests

## API Endpoints

### Initiate Payment
```http
POST /payment/initiate
Content-Type: application/json

{
  "member_id": 123,
  "phone_number": "0712345678",
  "amount": 500,
  "payment_type": "monthly"
}
```

### Check Status
```http
GET /payment/status?checkout_request_id=ws_CO_XXXXXX
```

## Support Resources

- **Full Guide**: See `MPESA_STK_PUSH_GUIDE.md`
- **Safaricom Docs**: https://developer.safaricom.co.ke
- **Test Environment**: https://sandbox.safaricom.co.ke

## Summary

✅ System is ready for testing in sandbox  
✅ Can switch to production anytime  
✅ All payment flows support STK push  
✅ Automatic reconciliation configured  
✅ Comprehensive logging enabled  

**Next Steps:**
1. Run database migration
2. Configure `.env` with credentials
3. Test in sandbox
4. When ready, switch to production

---

**Questions?** Check the full guide: `MPESA_STK_PUSH_GUIDE.md`
