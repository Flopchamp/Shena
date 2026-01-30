# Phase 1: Service-Based Claims Processing

## Overview
This phase converts the claims system from money-based to service-based processing, aligning with the SHENA Companion Policy Booklet (January 2026).

## Key Changes

### 1. **Service Delivery Default**
- Claims now default to **standard service delivery** instead of cash payments
- Services include:
  - Mortuary bill payment (max 14 days)
  - Body dressing
  - Executive coffin
  - Transportation
  - Equipment (lowering gear, trolley, gazebo, 100 chairs)

### 2. **Cash Alternative (KSH 20,000)**
- Only available in **exceptional circumstances** per Policy Section 12
- Requires:
  - Mutual agreement between company and client
  - Detailed reason (minimum 20 characters)
  - Signed agreement form
  - Admin approval

### 3. **Service Delivery Tracking**
- Checklist system for tracking each service delivery
- Admin can mark individual services as completed
- Completion percentage tracking
- Cannot mark claim as complete until all services delivered

## Database Changes

### New Tables

#### `claim_service_checklist`
Tracks individual service deliveries for each claim.

```sql
- claim_id: Links to claims table
- service_type: mortuary_bill, body_dressing, coffin, transportation, equipment
- completed: Boolean
- completed_at: Timestamp
- completed_by: Admin user ID
- service_notes: Text
```

#### `claim_cash_alternative_agreements`
Manages cash alternative requests and payments.

```sql
- claim_id: Links to claims table
- reason_category: security_risk, client_request, logistical_issue, other
- detailed_reason: Text explanation
- requested_by: company or client
- agreement_signed: Boolean
- amount_paid: Fixed at 20000.00
- payment_reference: M-Pesa or bank reference
```

### Modified Tables

#### `claims` table - New columns:
- `service_delivery_type`: ENUM('standard_services', 'cash_alternative')
- `mortuary_bill_settled`: Boolean
- `body_dressing_completed`: Boolean
- `coffin_delivered`: Boolean
- `transportation_arranged`: Boolean
- `equipment_delivered`: Boolean
- `services_delivery_date`: Date
- `mortuary_days_count`: INT (max 14)
- `cash_alternative_reason`: TEXT
- `cash_alternative_agreement_signed`: Boolean
- `cash_alternative_amount`: DECIMAL (20000.00)

#### `claims` table - Updated status ENUM:
- submitted
- under_review
- approved
- services_arranged
- completed
- rejected

## New Models

### `ClaimServiceChecklist`
```php
$checklist->initializeChecklist($claimId);
$checklist->markServiceCompleted($claimId, 'mortuary_bill', $adminId, $notes);
$checklist->getClaimChecklist($claimId);
$checklist->areAllServicesCompleted($claimId);
$checklist->getCompletionPercentage($claimId);
```

### `ClaimCashAlternative`
```php
$cashAlt->createAgreement($data);
$cashAlt->validateRequest($claimId, $reason, $requestedBy);
$cashAlt->markAgreementSigned($claimId, $documentPath);
$cashAlt->recordPayment($claimId, $method, $reference);
```

## Updated Models

### `Claim` model - New methods:
```php
$claim->approveClaimForServices($claimId, $deliveryDate, $notes);
$claim->approveClaimForCashAlternative($claimId, $reason, $requestedBy, $approvedBy);
$claim->updateServiceDeliveryStatus($claimId, $serviceType, $completed);
$claim->getClaimServiceChecklist($claimId);
$claim->completeClaim($claimId, $completionNotes);
```

## Updated Controllers

### `MemberController` - Updated submitClaim():
- Removed `claim_amount` as required field
- Added `mortuary_days_count` field (max 14)
- Added validation for maturity period completion
- Added validation for member not in default
- Added check for required documents (ID, Chief letter, Mortuary invoice)
- Service delivery type defaults to 'standard_services'

### `AdminController` - New methods:

#### `approveClaim()` - Standard Service Approval
- Validates member eligibility per policy
- Checks required documents
- Approves for service delivery
- Sends notification to member

#### `approveClaimCashAlternative()` - Cash Alternative
- Validates exceptional circumstances
- Requires detailed reason (20+ characters)
- Creates cash alternative agreement
- Sets fixed amount of KSH 20,000

#### `trackServiceDelivery($claimId)` - Service Tracking
- View service delivery checklist
- Mark individual services as completed
- Track completion percentage
- View service notes

#### `completeClaim()` - Final Completion
- Validates all services completed (or cash paid)
- Marks claim as completed
- Records completion notes

## New Routes

```php
POST   /admin/claims/approve              // Approve for services
POST   /admin/claims/approve-cash         // Approve cash alternative
GET    /admin/claims/track/{id}          // View service tracking
POST   /admin/claims/track/{id}          // Update service status
POST   /admin/claims/complete            // Complete claim
```

## Installation

### Step 1: Run Migration
```bash
php run_phase1_migration.php
```

This will:
- Add new columns to `claims` table
- Create `claim_service_checklist` table
- Create `claim_cash_alternative_agreements` table
- Add necessary indexes

### Step 2: Test Implementation
```bash
php test_phase1_service_claims.php
```

This verifies:
- New model classes exist
- New methods are available
- Database structure is correct
- All components are properly integrated

## Usage Guide

### For Members (Claim Submission)

1. **Submit Claim**
   - Fill in deceased details
   - Specify mortuary name and days (max 14)
   - Upload required documents:
     - ID/Birth Certificate copy
     - Chief letter
     - Mortuary invoice
   - NO claim amount required (service-based)

2. **Eligibility Requirements** (Policy Section 9)
   - Must be active member
   - Maturity period completed (4-5 months)
   - Not in default status (payments up to date)
   - All required documents submitted

### For Admins (Claim Processing)

#### Option 1: Standard Service Delivery (Default)
1. Review claim submission
2. Verify documents and eligibility
3. Click "Approve for Services"
4. Set service delivery date
5. Track service delivery:
   - Mark "Mortuary bill settled" ✓
   - Mark "Body dressing completed" ✓
   - Mark "Coffin delivered" ✓
   - Mark "Transportation arranged" ✓
   - Mark "Equipment delivered" ✓
6. Once all services completed, mark claim as "Completed"

#### Option 2: Cash Alternative (KSH 20,000)
1. Review claim submission
2. Determine if exceptional circumstances exist:
   - Security risks to company
   - Client request (with mutual agreement)
   - Logistical issues
3. Click "Approve Cash Alternative"
4. Provide detailed reason (20+ characters)
5. Specify who requested (company/client)
6. System creates agreement
7. Get agreement signed by family
8. Process KSH 20,000 payment
9. Record payment reference
10. Mark claim as "Completed"

## Policy Compliance

### Per Policy Section 8 - Claims Process
- ✓ Required documents: ID, Chief letter, Mortuary invoice
- ✓ Verification within 1-3 business days
- ✓ Service coordination with mortuary and family

### Per Policy Section 9 - Conditions
- ✓ Registered member check
- ✓ Monthly contributions check
- ✓ Maturity period validation
- ✓ Default status check
- ✓ Document completeness verification

### Per Policy Section 10 - Exclusions
- ✓ System validates cause of death against exclusions
- ✓ Admin can reject if excluded cause detected

### Per Policy Section 12 - Cash Alternative
- ✓ KSH 20,000 fixed amount
- ✓ Exceptional circumstances only
- ✓ Mutual agreement required
- ✓ Signed agreement mandatory

## Testing Checklist

- [ ] Run database migration successfully
- [ ] Run test script - all tests pass
- [ ] Member can submit claim (service-based)
- [ ] Admin can approve for services
- [ ] Admin can track service delivery
- [ ] Admin can mark services as completed
- [ ] Admin can complete claim after all services
- [ ] Admin can approve cash alternative
- [ ] Cash alternative validates reason length
- [ ] System prevents completion without all services done
- [ ] Mortuary days limited to 14
- [ ] Required documents validation works
- [ ] Eligibility checks work (maturity, default status)

## Next Steps

After successful Phase 1 implementation:
- **Phase 2**: Payment auto-reconciliation (Paybill integration)
- **Phase 3**: Plan upgrade feature
- **Phase 4**: System logs and data recovery

## Support

For issues or questions:
1. Check test script output: `php test_phase1_service_claims.php`
2. Review migration logs
3. Check error logs: `storage/logs/error.log`
4. Verify database structure manually

---

**Implementation Date**: January 30, 2026  
**Policy Version**: SHENA Companion Policy Booklet v1.0 (January 2026)
