# QUICK START GUIDE - Phase 1 Implementation
## For Developers

### Installation (5 Minutes)

```bash
# 1. Run database migration
cd c:\xampp\htdocs\Shena
c:\xampp\mysql\bin\mysql.exe -u root -p4885 shena_welfare_db < database\migrations\001_add_dependents_support.sql

# 2. Create log directory
mkdir storage\logs

# 3. Test cron jobs
php cron\check_payment_status.php
php cron\check_dependent_ages.php

# 4. Verify
php -r "require 'app/core/Database.php'; require 'config/config.php'; $db = Database::getInstance(); echo 'DB Connected!';"
```

### Quick Test Scenarios

#### Add Dependent
```php
<?php
require 'app/models/Dependent.php';
$dep = new Dependent();

$id = $dep->addDependent([
    'member_id' => 1,
    'full_name' => 'Test Child',
    'relationship' => 'child',
    'date_of_birth' => '2010-01-01',
    'gender' => 'male'
]);
echo "Dependent added: {$id}";
```

#### Submit Dependent Claim
```php
<?php
require 'app/models/Claim.php';
$claim = new Claim();

$claimId = $claim->submitClaim([
    'member_id' => 1,
    'dependent_id' => 1,
    'deceased_type' => 'dependent',
    'deceased_name' => 'Test Child',
    'deceased_id_number' => 'BIRTH123',
    'date_of_death' => '2026-01-20',
    'place_of_death' => 'Hospital',
    'claim_amount' => 50000
]);
echo "Claim submitted: {$claimId}";
```

#### Check Payment Status (Manual Run)
```bash
php cron/check_payment_status.php
cat storage/logs/payment_monitoring_*.log
```

### File Locations

**New Files:**
- `app/models/Dependent.php` - Dependent management
- `cron/check_payment_status.php` - Payment monitoring
- `cron/check_dependent_ages.php` - Age verification
- `database/migrations/001_add_dependents_support.sql` - Migration

**Modified Files:**
- `app/models/Member.php` - Added dependent methods
- `app/models/Claim.php` - Added dependent support
- `database/schema.sql` - Added dependents table

### Common Issues

**Issue:** Cron job fails  
**Fix:** Check database connection in logs

**Issue:** Can't add dependent  
**Fix:** Verify member has family package (not individual)

**Issue:** Migration fails  
**Fix:** Ensure database exists and credentials correct

### Cron Job Setup (Windows)

```powershell
# Open Task Scheduler
taskschd.msc

# Create Task: Payment Check
# Trigger: Daily 12:00 AM
# Action: c:\xampp\php\php.exe
# Arguments: c:\xampp\htdocs\Shena\cron\check_payment_status.php

# Create Task: Age Check
# Trigger: Daily 1:00 AM
# Action: c:\xampp\php\php.exe
# Arguments: c:\xampp\htdocs\Shena\cron\check_dependent_ages.php
```

### Next Steps

1. Run migration ✅
2. Test dependents add/remove ✅
3. Test claim with dependent ✅
4. Set up cron jobs ✅
5. Monitor logs ✅
6. Proceed to Phase 2

Done! System ready for testing.
