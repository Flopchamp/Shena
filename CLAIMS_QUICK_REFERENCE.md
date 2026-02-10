# Claims Management - Quick Reference Guide

## Page Structure

### URL
`/admin/claims`

### Access Control
- Required Role: Admin (Manager or Super Admin)
- Method: `AdminController::requireAdminAccess()`

## Tab Navigation

| Tab # | Tab Name | Status Filter | Data Source | Badge Count |
|-------|----------|---------------|-------------|-------------|
| 1 | All Claims | All statuses | `$all_claims` | Total claims |
| 2 | Submitted | `submitted` | Filtered from `$all_claims` | `$submittedCount` |
| 3 | Under Review | `under_review` | Filtered from `$all_claims` | `$underReviewCount` |
| 4 | Approved/Service | `approved` | Filtered from `$all_claims` | `$inServiceCount` |
| 5 | Completed | `completed`, `paid` | `$completed_claims` | Count |
| 6 | Rejected | `rejected` | Filtered from `$all_claims` | `$rejectedClaims` |

## Statistics Cards

### Card 1: Submitted
- Icon: File Alt (Blue)
- Metric: Count of claims with status `submitted`
- Format: "XX Claims"

### Card 2: Under Review
- Icon: Search (Orange)
- Metric: Count of claims with status `under_review`
- Format: "XX Claims"

### Card 3: In Service
- Icon: Check Circle (Green)
- Metric: Count of claims with status `approved`
- Format: "XX Active"

### Card 4: Completed
- Icon: Check Double (Green)
- Metric: Count of completed and paid claims
- Format: "XX Total"

## Claim Display Fields

### Table Columns
1. **Claim #** - Format: `CLM-YYYY-0001` (4-digit ID padded)
2. **Member** - Full name + Member number
3. **Deceased** - Name of deceased person
4. **Date of Death** - Formatted date
5. **Service Type** - Badge (Standard Services / Cash Alternative)
6. **Status** - Colored badge
7. **Actions** - Context-specific buttons

### Claim Row Data
```php
[
    'id' => 1,
    'member_number' => 'MEM-2024-0001',
    'first_name' => 'John',
    'last_name' => 'Doe',
    'deceased_name' => 'Mary Doe',
    'date_of_death' => '2024-01-15',
    'status' => 'submitted',
    'settlement_type' => 'services', // or 'cash_alternative'
    'claim_amount' => 80000
]
```

## Status Colors

| Status | Background | Text Color | Badge Style |
|--------|-----------|------------|-------------|
| submitted | #DBEAFE | #1E40AF | Blue |
| under_review | #FED7AA | #92400E | Orange |
| approved | #D1FAE5 | #065F46 | Green |
| paid | #D1FAE5 | #065F46 | Green |
| rejected | #FEE2E2 | #991B1B | Red |
| completed | #D1FAE5 | #065F46 | Green |

## Action Buttons

### View Button (All Statuses)
- Icon: Eye
- Action: Navigate to `/admin/claims/view/{id}`
- Style: White background, gray border
- Always visible

### Review & Approve Button (Submitted, Under Review)
- Icon: Check
- Action: Call `reviewClaim(id)` → Navigate to view page
- Style: Green background, white text
- Visible only for: `submitted`, `under_review`

### Track Services Button (Approved)
- Icon: Truck
- Action: Navigate to `/admin/claims/track/{id}`
- Style: White background, gray border
- Visible only for: `approved`

## JavaScript Functions

### switchClaimTab(tabName)
```javascript
// Switches between tabs
// Parameters: 'all', 'submitted', 'review', 'approved', 'completed', 'rejected'
// Updates active states and triggers filtering
```

### filterClaimsByStatus(status)
```javascript
// Filters claims array by status
// Uses statusMap to match tab names to database statuses
// Calls renderClaimsTable() with filtered results
```

### renderClaimsTable(claims, status)
```javascript
// Generates HTML table from claims array
// Returns complete table markup or empty state
// Handles service type badges and action buttons
```

### reviewClaim(claimId)
```javascript
// Navigates to claim review page
// Redirects to: /admin/claims/view/{claimId}
```

## Empty States

### No Claims Message
```
Icon: Inbox (64px, opacity 0.3)
Title: "No Claims Found"
Subtitle: "Claims will appear here when members submit them"
```

### No Filtered Claims
```
Icon: Inbox (64px, opacity 0.3)
Title: "No {status} claims"
```

## Policy Requirements (Reference)

### Required Documents
1. ✓ ID Copy
2. ✓ Chief's Letter
3. ✓ Mortuary Invoice
4. ○ Death Certificate (Optional but recommended)

### Business Rules
- **Mortuary Days Limit:** Maximum 14 days
- **Deceased Validation:** Must be registered dependent
- **Settlement Options:**
  - Standard Services: Full funeral coordination
  - Cash Alternative: KES 20,000 payout

### Document Verification
- Completeness check before approval
- Format validation (PDF/Image)
- File size limit: 2MB per document

## Controller Data Contract

### Required Variables
```php
$all_claims = [];           // Array of all claims with details
$pending_claims = [];       // Array of pending claims
$completed_claims = [];     // Array of completed claims
$pendingClaims = 0;        // Count of pending claims
$approvedClaims = 0;       // Count of approved claims
$rejectedClaims = 0;       // Count of rejected claims
$totalClaimAmount = 0;     // Sum of all claim amounts
$submittedCount = 0;       // Calculated in view
$underReviewCount = 0;     // Calculated in view
$inServiceCount = 0;       // Calculated in view
```

## Responsive Breakpoints

### Desktop (> 1200px)
- 4-column stats grid
- Full table layout
- All tabs visible

### Tablet (768px - 1200px)
- 2-column stats grid
- Compact table
- Tabs scroll horizontally

### Mobile (< 768px)
- 1-column stats grid
- Vertical tabs
- Simplified table layout
- Smaller font sizes

## Common Tasks

### Adding New Status
1. Add to statusMap in `filterClaimsByStatus()`
2. Add color mapping in status badge section
3. Add conditional action buttons if needed
4. Update tab navigation if new tab required

### Modifying Table Columns
1. Update thead in main table (line ~719)
2. Update tbody columns (line ~733)
3. Update renderClaimsTable() function (line ~872)

### Changing Statistics
1. Update calculation logic in PHP header (lines 11-17)
2. Update stat card display (lines 640-694)

---

**Last Updated:** 2024
**View File:** `resources/views/admin/claims.php`
**Controller:** `app/controllers/AdminController.php` → `claims()`
**Models:** `Claim.php`, `ClaimDocument.php`, `ClaimServiceChecklist.php`
