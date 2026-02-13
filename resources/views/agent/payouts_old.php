<?php 
$page = 'payouts'; 
include __DIR__ . '/../layouts/agent-header.php';

// Data passed from controller: $agent, $commissions, $total_earned, $pending_amount, $current_balance
// Process commissions for display
if (!empty($commissions)) {
    foreach ($commissions as &$commission) {
        // Format date
        $commission['display_date'] = date('d M Y', strtotime($commission['created_at']));
        
        // Format status for display
        $status = strtoupper($commission['status']);
        $commission['display_status'] = $status;
        
        // Status badge class
        switch($commission['status']) {
            case 'paid':
                $commission['status_class'] = 'paid';
                break;
            case 'pending':
            case 'approved':
                $commission['status_class'] = 'pending';
                break;
            default:
                $commission['status_class'] = 'pending';
        }
        
        // Format commission type
        $commission['display_type'] = ucfirst(str_replace('_', ' ', $commission['commission_type']));
    }
    unset($commission);
}

// Get agent phone for M-Pesa
$mpesa_number = $agent['phone'] ?? '+254 700 000 000';

// Get recent payout requests (filter paid commissions from last 2 months)
$recent_requests = [];
if (!empty($commissions)) {
    $paid_commissions = array_filter($commissions, function($c) {
        return $c['status'] === 'paid' && $c['paid_at'];
    });
    $recent_requests = array_slice($paid_commissions, 0, 3);
}
?>

<style>
/* Payouts Page Styles */
.payouts-container {
    padding: 30px 30px 40px 25px;
    background: #F8F9FA;
    min-height: calc(100vh - 80px);
}

.payouts-header {
    margin-bottom: 32px;
}

.payouts-header h1 {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    font-weight: 700;
    color: #1F2937;
    margin: 0 0 4px 0;
}

.payouts-header p {
    font-size: 14px;
    color: #6B7280;
    margin: 0;
}

/* Stats Grid */
.payouts-stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    margin-bottom: 32px;
}

.payout-stat-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}

.payout-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.payout-stat-card.balance-card {
    background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
    color: white;
}

.stat-header-payout {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}

.stat-icon-payout {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

.payout-stat-card:not(.balance-card) .stat-icon-payout {
    background: #F3E8FF;
    color: #7F20B0;
}

.stat-label-payout {
    font-size: 11px;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.9);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.payout-stat-card:not(.balance-card) .stat-label-payout {
    color: #6B7280;
}

.stat-value-payout {
    font-family: 'Playfair Display', serif;
    font-size: 36px;
    font-weight: 700;
    color: white;
    margin: 8px 0;
}

.payout-stat-card:not(.balance-card) .stat-value-payout {
    color: #1F2937;
}

.stat-description-payout {
    font-size: 13px;
    color: rgba(255, 255, 255, 0.8);
}

.payout-stat-card:not(.balance-card) .stat-description-payout {
    color: #9CA3AF;
}

/* Main Content Grid */
.payouts-main-grid {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 24px;
}

/* Transactions Section */
.transactions-section {
    background: white;
    border-radius: 16px;
    padding: 32px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.transactions-controls {
    display: flex;
    gap: 12px;
    margin-bottom: 24px;
}

.filter-select {
    flex: 1;
    padding: 10px 14px;
    border: 1px solid #D1D5DB;
    border-radius: 8px;
    font-size: 14px;
    color: #4B5563;
    background: white;
    cursor: pointer;
}

.filter-select:focus {
    outline: none;
    border-color: #7F20B0;
}

.filter-btn-payout {
    width: 44px;
    height: 44px;
    border: 1px solid #D1D5DB;
    border-radius: 8px;
    background: white;
    color: #6B7280;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}

.filter-btn-payout:hover {
    background: #F9FAFB;
    border-color: #9CA3AF;
}

/* Transactions Table */
.transactions-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.transactions-table thead th {
    font-size: 11px;
    font-weight: 700;
    color: #9CA3AF;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px 16px;
    border-bottom: 1px solid #E5E7EB;
    text-align: left;
}

.transactions-table tbody tr {
    transition: background-color 0.2s;
}

.transactions-table tbody tr:hover {
    background: #F9FAFB;
}

.transactions-table tbody td {
    padding: 16px;
    border-bottom: 1px solid #F3F4F6;
}

.transaction-date {
    font-size: 13px;
    color: #6B7280;
}

.transaction-member {
    font-size: 14px;
    font-weight: 600;
    color: #7F20B0;
}

.transaction-action {
    font-size: 13px;
    color: #6B7280;
}

.transaction-amount {
    font-size: 14px;
    font-weight: 600;
    color: #1F2937;
}

.transaction-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.3px;
}

.transaction-status.paid {
    background: #D1FAE5;
    color: #059669;
}

.transaction-status.pending {
    background: #FEF3C7;
    color: #D97706;
}

.transaction-status i {
    font-size: 8px;
}

/* Pagination */
.transactions-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid #E5E7EB;
}

.pagination-info-payout {
    font-size: 14px;
    color: #6B7280;
}

.pagination-controls-payout {
    display: flex;
    gap: 8px;
}

.page-btn-payout {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: 1px solid #D1D5DB;
    background: white;
    color: #4B5563;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.2s;
    text-decoration: none;
}

.page-btn-payout:hover:not(.active) {
    background: #F9FAFB;
    border-color: #9CA3AF;
}

.page-btn-payout.active {
    background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
    color: white;
    border-color: transparent;
}

.page-btn-payout.disabled {
    pointer-events: none;
    opacity: 0.5;
}

/* Withdrawal Panel */
.withdrawal-panel {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    height: fit-content;
}

.withdrawal-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid #E5E7EB;
}

.withdrawal-icon {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

.withdrawal-header h3 {
    font-size: 16px;
    font-weight: 700;
    color: #1F2937;
    margin: 0;
}

.withdrawal-balance {
    margin-bottom: 20px;
}

.balance-label {
    font-size: 11px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}

.balance-amount {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    font-weight: 700;
    color: #1F2937;
}

.transfer-method-section {
    margin-bottom: 20px;
}

.transfer-label {
    font-size: 11px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
}

.transfer-method-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    background: #F9FAFB;
}

.transfer-method-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.mpesa-icon {
    width: 32px;
    height: 32px;
    background: #059669;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
    font-weight: 700;
}

.transfer-method-details h6 {
    font-size: 13px;
    font-weight: 600;
    color: #1F2937;
    margin: 0 0 2px 0;
}

.transfer-method-details p {
    font-size: 11px;
    color: #9CA3AF;
    margin: 0;
}

.change-btn {
    font-size: 12px;
    color: #7F20B0;
    font-weight: 600;
    background: none;
    border: none;
    cursor: pointer;
    text-decoration: underline;
}

.amount-input-section {
    margin-bottom: 20px;
}

.amount-label {
    font-size: 11px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}

.amount-input {
    width: 100%;
    padding: 12px 14px 12px 40px;
    border: 1px solid #D1D5DB;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    color: #1F2937;
    position: relative;
}

.amount-input:focus {
    outline: none;
    border-color: #7F20B0;
    box-shadow: 0 0 0 3px rgba(127, 32, 176, 0.1);
}

.amount-input-wrapper {
    position: relative;
}

.currency-symbol {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 16px;
    font-weight: 600;
    color: #6B7280;
}

.btn-request-payout {
    background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
    color: white;
    border: none;
    padding: 14px 24px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    width: 100%;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-request-payout:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(127, 32, 176, 0.3);
}

/* Recent Requests */
.recent-requests-section {
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid #E5E7EB;
}

.recent-requests-header {
    font-size: 11px;
    font-weight: 700;
    color: #9CA3AF;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
}

.request-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #F3F4F6;
}

.request-item:last-child {
    border-bottom: none;
}

.request-amount {
    font-size: 16px;
    font-weight: 700;
    color: #1F2937;
    margin-bottom: 2px;
}

.request-date {
    font-size: 12px;
    color: #9CA3AF;
}

.request-status {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 700;
    background: #D1FAE5;
    color: #059669;
}

/* Responsive */
@media (max-width: 1200px) {
    .payouts-stats-grid {
        grid-template-columns: 1fr;
    }

    .payouts-main-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .payouts-container {
        padding: 20px 15px;
    }

    .transactions-section,
    .withdrawal-panel {
        padding: 20px;
    }

    .transactions-table {
        display: block;
        overflow-x: auto;
    }
}
</style>

<div class="payouts-container">
    <div class="payouts-header">
        <h1>Agent Payouts & Commission Tracking</h1>
        <p>Manage your earnings and request withdrawals</p>
    </div>

    <!-- Message Display -->
    <div class="payouts-messages" style="margin-bottom: 20px;">
    <?php if (isset($_SESSION['success'])): ?>
    <div style="background: #D1FAE5; color: #059669; padding: 12px; border-radius: 8px; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
        <i class="fas fa-check-circle"></i>
        <?php echo htmlspecialchars($_SESSION['success']); ?>
    </div>
    <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
    <div style="background: #FEE2E2; color: #DC2626; padding: 12px; border-radius: 8px; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
        <i class="fas fa-exclamation-circle"></i>
        <?php echo htmlspecialchars($_SESSION['error']); ?>
    </div>
    <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    </div>

    <!-- Stats Grid -->
    <div class="payouts-stats-grid">
        <!-- Current Balance -->
        <div class="payout-stat-card balance-card">
            <div class="stat-header-payout">
                <div class="stat-icon-payout">
                    <i class="fas fa-wallet"></i>
                </div>
                <span class="stat-label-payout">Current Balance</span>
            </div>
            <div class="stat-value-payout">KES <?php echo number_format($current_balance ?? 0, 2); ?></div>
            <div class="stat-description-payout">Available for withdrawal</div>
        </div>

        <!-- Pending Commissions -->
        <div class="payout-stat-card">
            <div class="stat-header-payout">
                <div class="stat-icon-payout">
                    <i class="fas fa-clock"></i>
                </div>
                <span class="stat-label-payout">Pending Commissions</span>
            </div>
            <div class="stat-value-payout">KES <?php echo number_format($pending_amount ?? 0, 2); ?></div>
            <div class="stat-description-payout">Awaiting monthly settlement</div>
        </div>

        <!-- Total Earned -->
        <div class="payout-stat-card">
            <div class="stat-header-payout">
                <div class="stat-icon-payout">
                    <i class="fas fa-chart-line"></i>
                </div>
                <span class="stat-label-payout">Total Earned to Date</span>
            </div>
            <div class="stat-value-payout">KES <?php echo number_format($total_earned ?? 0, 2); ?></div>
            <div class="stat-description-payout">Lifetime performance</div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="payouts-main-grid">
        <!-- Transactions Section -->
        <div class="transactions-section">
            <form class="transactions-controls" method="GET" action="/agent/payouts">
                <select class="filter-select" name="month">
                    <option value="all">Filter by Month (All Time)</option>
                    <?php foreach (($available_months ?? []) as $month): ?>
                        <option value="<?php echo htmlspecialchars($month); ?>" <?php echo (($filters['month'] ?? 'all') === $month) ? 'selected' : ''; ?>>
                            <?php echo date('F Y', strtotime($month . '-01')); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <select class="filter-select" name="status">
                    <?php $activeStatus = $filters['status'] ?? 'all'; ?>
                    <option value="all" <?php echo $activeStatus === 'all' ? 'selected' : ''; ?>>Status: All</option>
                    <option value="paid" <?php echo $activeStatus === 'paid' ? 'selected' : ''; ?>>Paid</option>
                    <option value="approved" <?php echo $activeStatus === 'approved' ? 'selected' : ''; ?>>Approved</option>
                    <option value="pending" <?php echo $activeStatus === 'pending' ? 'selected' : ''; ?>>Pending</option>
                </select>
                <button class="filter-btn-payout" type="submit" title="Apply Filters">
                    <i class="fas fa-sliders-h"></i>
                </button>
            </form>

            <?php if (empty($commissions)): ?>
                <div style="text-align: center; padding: 60px 20px;">
                    <i class="fas fa-money-bill-wave" style="font-size: 64px; color: #D1D5DB; margin-bottom: 16px;"></i>
                    <h3 style="font-size: 18px; color: #6B7280; margin: 0 0 8px 0;">No Commissions Yet</h3>
                    <p style="font-size: 14px; color: #9CA3AF; margin: 0;">Commission records will appear here once members are registered</p>
                </div>
            <?php else: ?>
            <table class="transactions-table">
                <thead>
                    <tr>
                        <th>DATE</th>
                        <th>MEMBER ID</th>
                        <th>TYPE</th>
                        <th>AMOUNT</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commissions as $commission): ?>
                    <tr>
                        <td class="transaction-date"><?php echo htmlspecialchars($commission['display_date']); ?></td>
                        <td class="transaction-member"><?php echo htmlspecialchars($commission['member_number'] ?? 'N/A'); ?></td>
                        <td class="transaction-action"><?php echo htmlspecialchars($commission['display_type']); ?></td>
                        <td class="transaction-amount">KES <?php echo number_format($commission['commission_amount'], 2); ?></td>
                        <td>
                            <span class="transaction-status <?php echo $commission['status_class']; ?>">
                                <i class="fas fa-circle"></i>
                                <?php echo $commission['display_status']; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>

            <?php if (!empty($commissions)): ?>
            <div class="transactions-footer">
                <div class="pagination-info-payout">
                    <?php
                        $total = (int)($pagination['total'] ?? count($commissions));
                        $page = (int)($pagination['page'] ?? 1);
                        $perPage = (int)($pagination['per_page'] ?? count($commissions));
                        $startItem = $total > 0 ? (($page - 1) * $perPage) + 1 : 0;
                        $endItem = $total > 0 ? min($total, $page * $perPage) : 0;
                    ?>
                    Showing <?php echo $startItem; ?>-<?php echo $endItem; ?> of <?php echo $total; ?> commission<?php echo $total != 1 ? 's' : ''; ?>
                </div>
                <div class="pagination-controls-payout">
                    <?php
                        $totalPages = (int)($pagination['total_pages'] ?? 1);
                        $baseQuery = 'status=' . urlencode($filters['status'] ?? 'all') . '&month=' . urlencode($filters['month'] ?? 'all');
                    ?>
                    <a class="page-btn-payout<?php echo $page <= 1 ? ' disabled' : ''; ?>" href="/agent/payouts?<?php echo $baseQuery; ?>&page=<?php echo max(1, $page - 1); ?>">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a class="page-btn-payout <?php echo $i === $page ? 'active' : ''; ?>" href="/agent/payouts?<?php echo $baseQuery; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                    <a class="page-btn-payout<?php echo $page >= $totalPages ? ' disabled' : ''; ?>" href="/agent/payouts?<?php echo $baseQuery; ?>&page=<?php echo min($totalPages, $page + 1); ?>">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Withdrawal Panel -->
        <div class="withdrawal-panel">
            <div class="withdrawal-header">
                <div class="withdrawal-icon">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <h3>Withdrawal Request</h3>
            </div>

            <div class="withdrawal-balance">
                <div class="balance-label">Available to Withdraw</div>
                <div class="balance-amount">KES <?php echo number_format($current_balance ?? 0, 2); ?></div>
            </div>

            <form action="/agent/payouts/request" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? ''); ?>">

                <div class="transfer-method-section">
                    <div class="transfer-label">Transfer Method</div>
                    <select class="filter-select" name="payment_method" id="payment_method" style="width: 100%; margin-bottom: 12px;" onchange="togglePaymentFields()">
                        <option value="mpesa" selected>M-Pesa Mobile Money</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="cash">Cash Pickup</option>
                    </select>
                    
                    <!-- M-Pesa Fields -->
                    <div id="mpesa_fields" class="payment-fields">
                        <div class="transfer-method-box">
                            <div class="transfer-method-info">
                                <div class="mpesa-icon">M</div>
                                <div class="transfer-method-details">
                                    <h6>M-Pesa Transfer</h6>
                                    <p>Instant to mobile number</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bank Transfer Fields -->
                    <div id="bank_fields" class="payment-fields" style="display: none;">
                        <div class="amount-input-section">
                            <div class="amount-label">Bank Name</div>
                            <input type="text" class="amount-input" name="bank_name" placeholder="Enter bank name">
                        </div>
                        <div class="amount-input-section">
                            <div class="amount-label">Account Number</div>
                            <input type="text" class="amount-input" name="account_number" placeholder="Enter account number">
                        </div>
                        <div class="amount-input-section">
                            <div class="amount-label">Account Name</div>
                            <input type="text" class="amount-input" name="account_name" placeholder="Enter account holder name">
                        </div>
                    </div>
                    
                    <!-- Cash Pickup Fields -->
                    <div id="cash_fields" class="payment-fields" style="display: none;">
                        <div class="transfer-method-box">
                            <div class="transfer-method-info">
                                <div class="mpesa-icon" style="background: #F59E0B;">C</div>
                                <div class="transfer-method-details">
                                    <h6>Cash Pickup</h6>
                                    <p>Collect from office</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="amount-input-section">
                    <div class="amount-label">Enter Amount (KES)</div>
                    <div class="amount-input-wrapper">
                        <span class="currency-symbol">KES</span>
                        <input type="number" class="amount-input" name="amount" value="5000" min="1" step="0.01" required>
                    </div>
                </div>

                <div class="amount-input-section" id="phone_section">
                    <div class="amount-label">Payout Phone Number</div>
                    <div class="amount-input-wrapper">
                        <input type="text" class="amount-input" name="phone_number" id="phone_number" value="<?php echo htmlspecialchars($mpesa_number); ?>" required>
                    </div>
                </div>
                
                <div class="amount-input-section">
                    <div class="amount-label">Notes (Optional)</div>
                    <div class="amount-input-wrapper">
                        <textarea class="amount-input" name="payout_notes" rows="2" placeholder="Any special instructions for this payout..." style="padding: 12px; height: auto;"></textarea>
                    </div>
                </div>


                <button class="btn-request-payout" type="submit">
                    Request Payout <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <div class="recent-requests-section">
                <div class="recent-requests-header">Recent Payouts</div>
                <?php if (empty($recent_requests)): ?>
                    <div style="text-align: center; padding: 20px; color: #9CA3AF; font-size: 13px;">
                        <i class="fas fa-inbox" style="font-size: 32px; margin-bottom: 8px; display: block;"></i>
                        No recent payouts
                    </div>
                <?php else: ?>
                    <?php foreach ($recent_requests as $request): ?>
                    <div class="request-item">
                        <div>
                            <div class="request-amount">KES <?php echo number_format($request['commission_amount'], 2); ?></div>
                            <div class="request-date"><?php echo date('d M Y', strtotime($request['paid_at'])); ?></div>
                        </div>
                        <span class="request-status">
                            PAID
                        </span>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function togglePaymentFields() {
    var method = document.getElementById('payment_method').value;
    
    // Hide all fields first
    document.getElementById('mpesa_fields').style.display = 'none';
    document.getElementById('bank_fields').style.display = 'none';
    document.getElementById('cash_fields').style.display = 'none';
    document.getElementById('phone_section').style.display = 'block';
    
    // Show relevant fields
    if (method === 'mpesa') {
        document.getElementById('mpesa_fields').style.display = 'block';
        document.getElementById('phone_number').required = true;
    } else if (method === 'bank_transfer') {
        document.getElementById('bank_fields').style.display = 'block';
        document.getElementById('phone_section').style.display = 'none';
        document.getElementById('phone_number').required = false;
    } else if (method === 'cash') {
        document.getElementById('cash_fields').style.display = 'block';
        document.getElementById('phone_section').style.display = 'none';
        document.getElementById('phone_number').required = false;
    }
}
</script>

<?php include __DIR__ . '/../layouts/agent-footer.php'; ?>
