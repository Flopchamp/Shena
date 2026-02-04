# Admin Navigation Quick Reference

## Sidebar Menu Structure

### 🏠 Dashboard
**Route:** `/admin` or `/admin/dashboard`
**Purpose:** Comprehensive analytics overview
- Member stats with trends
- Financial summaries
- Claim alerts
- Agent commissions
- Communication metrics
- Quick action buttons

---

### 👥 Member Management
**Route:** `/admin/members`
**Tabs:**
- **Statistics** - Active contributors, growth trends
- **Member List** - Directory with search/filter
- **Approvals** - Reg/Payment/Plan upgrade approvals
- **Contributions** - Payment history & analysis

**Actions:**
- Register Member (modal)
- Export PDF

---

### 👔 Agent Management
**Route:** `/admin/agents`
**Tabs:**
- **Analytics** - Performance metrics
- **All Agents** - Agent directory
- **Process Commission** - Commission management
- **Agent Resources** - Post resources for agents

**Actions:**
- Add Agent (modal)
- Export Data

---

### 📋 Claims
**Route:** `/admin/claims`
**Tabs:**
- **Pending Claims** - Unprocessed (with alert badge)
- **Approved Claims** - Awaiting disbursement
- **Completed Claims** - Historical records
- **All Claims** - Complete archive

**Removed:**
- ❌ Track Services (removed completely)

---

### 💰 Payments (Dropdown)
**Routes:**
- `/admin/payments` - All Payments tab
- `/admin/payments/reconciliation` - Reconciliation
- `/admin/payments/pending` - Pending
- `/admin/payments/failed` - Failed

**Tabs in main view:**
1. All Payments
2. Pending Payments (badge)
3. Successful
4. Failed (badge)
5. Reconciliation
6. M-Pesa Transactions

**Actions:**
- Record Payment (modal)
- Export Data

**Removed:**
- ❌ Plan Upgrades (moved to Member Management)

---

### 💬 Communications
**Route:** `/admin/communications`
**Tabs:**
- **Quick Messages** - Individual/group messaging
- **Email Campaigns** - Bulk email operations
- **SMS Campaigns** - Bulk SMS operations
- **System Notifications** - Inter-system alerts
- **Communication History** - All sent communications

---

### 📊 Reports & Analytics
**Route:** `/admin/reports`
**Tabs:**
- **Overview** - Executive summary
- **Financial Analytics** - Revenue/expense analysis
- **Member Analytics** - Growth & demographics
- **Claims Analytics** - Claim patterns
- **Agent Performance** - Agent metrics
- **Contributions Analysis** - Payment patterns

**Actions:**
- Export PDF
- Export Excel

---

### ⚙️ System Settings
**Route:** `/admin/settings`
**Tabs:**
- **General** - App name, email, timeouts
- **Email Configuration** - SMTP settings
- **SMS Configuration** - SMS gateway settings
- **Payment Settings** - M-Pesa & payment methods
- **Notifications** - Alert preferences
- **Security** - Password policies, sessions

**Removed:**
- ❌ M-Pesa Config (moved to Payment Settings tab)

---

## Key Navigation Changes

### Before → After

| Old Navigation | New Location |
|---------------|--------------|
| Users | Split into Member Management & Agent Management |
| Transactions | Renamed to Payments with dropdown |
| Claims > Track Services | ❌ Removed |
| Claims > Completed | Claims > Completed Claims tab |
| Payments > Plan Upgrades | Member Management > Approvals tab |
| Settings > M-Pesa Config | System Settings > Payment Settings tab |

---

## Keyboard Shortcuts (Recommended)

These can be implemented for power users:

- `Alt + D` - Dashboard
- `Alt + M` - Member Management
- `Alt + A` - Agent Management
- `Alt + C` - Claims
- `Alt + P` - Payments
- `Alt + T` - Communications
- `Alt + R` - Reports & Analytics
- `Alt + S` - System Settings

---

## URL Patterns

### Members
- `/admin/members` - Member list
- `/admin/members/view/{id}` - Member details
- `/admin/members/edit/{id}` - Edit member
- `/admin/members/register` - Register new

### Agents
- `/admin/agents` - Agent list
- `/admin/agents/view/{id}` - Agent details
- `/admin/agents/create` - Add agent
- `/admin/agents/commission` - Process commission

### Claims
- `/admin/claims` - All claims
- `/admin/claims/view/{id}` - Claim details
- `/admin/claims/approve/{id}` - Approve claim
- `/admin/claims/reject/{id}` - Reject claim

### Payments
- `/admin/payments` - All payments
- `/admin/payments/reconciliation` - Reconciliation
- `/admin/payments/pending` - Pending payments
- `/admin/payments/failed` - Failed payments

### Communications
- `/admin/communications` - Communications hub
- `/admin/email-campaigns` - Email campaigns
- `/admin/bulk-sms` - SMS campaigns

### Reports
- `/admin/reports` - All reports
- `/admin/reports/export/{type}` - Export report

### Settings
- `/admin/settings` - System settings
- `/admin/settings/update` - Update settings

---

## Common Tasks Quick Access

### Register New Member
1. Click **Member Management**
2. Click **Register Member** button
3. Fill form → Submit

### Process Claim
1. Click **Claims**
2. View **Pending Claims** tab
3. Click claim → **Process** → Actions

### Send Bulk SMS
1. Click **Communications**
2. Select **SMS Campaigns** tab
3. Create campaign → Send

### View Financial Reports
1. Click **Reports & Analytics**
2. Select **Financial Analytics** tab
3. Choose date range → **Export PDF**

### Configure M-Pesa
1. Click **System Settings**
2. Select **Payment Settings** tab
3. Update M-Pesa credentials → **Save**

### Add Agent
1. Click **Agent Management**
2. Click **Add Agent** button
3. Fill form → Submit

### Reconcile Payments
1. Click **Payments** dropdown
2. Select **Reconciliation**
3. Or click Payments → **Reconciliation** tab

---

## Badge Indicators

**Red Badge** - Critical attention needed
- Unprocessed claims
- Failed payments
- Pending approvals

**Yellow Badge** - Warnings
- Pending payments
- Awaiting review

**Blue Badge** - Information
- New items
- Updates available

---

## Tips for Efficient Navigation

1. **Use the dropdown** for quick payment access
2. **Badges show counts** - click to see details
3. **Tabs keep you in context** - no page reloads
4. **Quick action buttons** in headers
5. **Export options** available on all major views
6. **Search/filter** within each section

---

**Last Updated:** February 4, 2026
