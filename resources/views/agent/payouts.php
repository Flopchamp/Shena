<?php 
$page = 'payouts'; 
include __DIR__ . '/../layouts/agent-header.php';

// Sample data - replace with actual database queries
$agent = [
    'current_balance' => 8240.50,
    'pending_commissions' => 2150.00,
    'total_earned' => 45980.00,
    'mpesa_number' => '+254 712 345 678'
];

$transactions = [
    [
        'date' => '24 Oct 2023',
        'member_name' => 'Thabo Mbeki',
        'action' => 'New Registration',
        'amount' => 450.00,
        'status' => 'PAID',
        'status_class' => 'success'
    ],
    [
        'date' => '22 Oct 2023',
        'member_name' => 'Sipho Khumalo',
        'action' => 'Monthly Renewal',
        'amount' => 120.00,
        'status' => 'PENDING',
        'status_class' => 'warning'
    ],
    [
        'date' => '20 Oct 2023',
        'member_name' => 'Patience Ndlovu',
        'action' => 'Monthly Renewal',
        'amount' => 120.00,
        'status' => 'PAID',
        'status_class' => 'success'
    ],
    [
        'date' => '18 Oct 2023',
        'member_name' => 'Zama Dlamini',
        'action' => 'New Registration',
        'amount' => 450.00,
        'status' => 'PENDING',
        'status_class' => 'warning'
    ]
];

$recent_requests = [
    [
        'amount' => 2400.00,
        'date' => '15 Oct 2023',
        'status' => 'SUCCESS'
    ],
    [
        'amount' => 1500.00,
        'date' => '08 Oct 2023',
        'status' => 'SUCCESS'
    ]
];
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
            <div class="stat-value-payout">R <?php echo number_format($agent['current_balance'], 2); ?></div>
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
            <div class="stat-value-payout">R <?php echo number_format($agent['pending_commissions'], 2); ?></div>
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
            <div class="stat-value-payout">R <?php echo number_format($agent['total_earned'], 2); ?></div>
            <div class="stat-description-payout">Lifetime performance</div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="payouts-main-grid">
        <!-- Transactions Section -->
        <div class="transactions-section">
            <div class="transactions-controls">
                <select class="filter-select">
                    <option value="all">Filter by Month (All Time)</option>
                    <option value="jan">January 2024</option>
                    <option value="feb">February 2024</option>
                    <option value="mar">March 2024</option>
                </select>
                <select class="filter-select">
                    <option value="all">Status: All</option>
                    <option value="paid">Paid</option>
                    <option value="pending">Pending</option>
                </select>
                <button class="filter-btn-payout">
                    <i class="fas fa-sliders-h"></i>
                </button>
            </div>

            <table class="transactions-table">
                <thead>
                    <tr>
                        <th>DATE</th>
                        <th>MEMBER NAME</th>
                        <th>ACTION</th>
                        <th>AMOUNT</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td class="transaction-date"><?php echo $transaction['date']; ?></td>
                        <td class="transaction-member"><?php echo htmlspecialchars($transaction['member_name']); ?></td>
                        <td class="transaction-action"><?php echo htmlspecialchars($transaction['action']); ?></td>
                        <td class="transaction-amount">R <?php echo number_format($transaction['amount'], 2); ?></td>
                        <td>
                            <span class="transaction-status <?php echo $transaction['status_class']; ?>">
                                <i class="fas fa-circle"></i>
                                <?php echo $transaction['status']; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="transactions-footer">
                <div class="pagination-info-payout">Showing last 20 transactions</div>
                <div class="pagination-controls-payout">
                    <button class="page-btn-payout">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="page-btn-payout active">1</button>
                    <button class="page-btn-payout">2</button>
                    <button class="page-btn-payout">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
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
                <div class="balance-amount">R <?php echo number_format($agent['current_balance'], 2); ?></div>
            </div>

            <div class="transfer-method-section">
                <div class="transfer-label">Transfer Method</div>
                <div class="transfer-method-box">
                    <div class="transfer-method-info">
                        <div class="mpesa-icon">M</div>
                        <div class="transfer-method-details">
                            <h6>M-Pesa Transfer</h6>
                            <p><?php echo htmlspecialchars($agent['mpesa_number']); ?></p>
                        </div>
                    </div>
                    <button class="change-btn">Change</button>
                </div>
            </div>

            <div class="amount-input-section">
                <div class="amount-label">Enter Amount (ZAR)</div>
                <div class="amount-input-wrapper">
                    <span class="currency-symbol">R</span>
                    <input type="number" class="amount-input" value="5000" min="1" step="0.01">
                </div>
            </div>

            <button class="btn-request-payout">
                Request Payout <i class="fas fa-arrow-right"></i>
            </button>

            <div class="recent-requests-section">
                <div class="recent-requests-header">Recent Requests</div>
                <?php foreach ($recent_requests as $request): ?>
                <div class="request-item">
                    <div>
                        <div class="request-amount">R <?php echo number_format($request['amount'], 2); ?></div>
                        <div class="request-date"><?php echo $request['date']; ?></div>
                    </div>
                    <span class="request-status">
                        <?php echo $request['status']; ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/agent-footer.php'; ?>
