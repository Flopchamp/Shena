<?php 
$page = 'payouts'; 
include __DIR__ . '/../layouts/agent-header.php';

// Data passed from controller: $agent, $payout_requests, $total_earned, $available_balance, $payout_stats

// Get agent phone for M-Pesa
$mpesa_number = $agent['phone'] ?? '+254 700 000 000';

// Calculate stats
$pendingRequests = $payout_stats['pending_count'] ?? 0;
$totalPaid = $payout_stats['total_paid'] ?? 0;
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

.transactions-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.transactions-header h2 {
    font-size: 18px;
    font-weight: 700;
    color: #1F2937;
    margin: 0;
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

.transaction-amount {
    font-size: 14px;
    font-weight: 600;
    color: #1F2937;
}

.transaction-method {
    font-size: 13px;
    color: #6B7280;
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

.transaction-status.requested {
    background: #FEF3C7;
    color: #D97706;
}

.transaction-status.processing {
    background: #DBEAFE;
    color: #2563EB;
}

.transaction-status.paid {
    background: #D1FAE5;
    color: #059669;
}

.transaction-status.rejected {
    background: #FEE2E2;
    color: #DC2626;
}

.transaction-status i {
    font-size: 8px;
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

.filter-select {
    width: 100%;
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

/* Payment Fields */
.payment-fields {
    margin-top: 12px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state i {
    font-size: 64px;
    color: #D1D5DB;
    margin-bottom: 16px;
}

.empty-state h3 {
    font-size: 18px;
    color: #6B7280;
    margin: 0 0 8px 0;
}

.empty-state p {
    font-size: 14px;
    color: #9CA3AF;
    margin: 0;
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
        <h1>My Payouts</h1>
        <p>Request and track your commission payouts</p>
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
        <!-- Available Balance -->
        <div class="payout-stat-card balance-card">
            <div class="stat-header-payout">
                <div class="stat-icon-payout">
                    <i class="fas fa-wallet"></i>
                </div>
                <span class="stat-label-payout">Available Balance</span>
            </div>
            <div class="stat-value-payout">KES <?php echo number_format($available_balance ?? 0, 2); ?></div>
            <div class="stat-description-payout">Available for withdrawal</div>
        </div>

        <!-- Pending Requests -->
        <div class="payout-stat-card">
            <div class="stat-header-payout">
                <div class="stat-icon-payout">
                    <i class="fas fa-clock"></i>
                </div>
                <span class="stat-label-payout">Pending Requests</span>
            </div>
            <div class="stat-value-payout"><?php echo $pendingRequests; ?></div>
            <div class="stat-description-payout">Awaiting processing</div>
        </div>

        <!-- Total Paid Out -->
        <div class="payout-stat-card">
            <div class="stat-header-payout">
                <div class="stat-icon-payout">
                    <i class="fas fa-check-circle"></i>
                </div>
                <span class="stat-label-payout">Total Paid Out</span>
            </div>
            <div class="stat-value-payout">KES <?php echo number_format($totalPaid, 2); ?></div>
            <div class="stat-description-payout">Lifetime payouts</div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="payouts-main-grid">
        <!-- Payout Requests Table -->
        <div class="transactions-section">
            <div class="transactions-header">
                <h2>Payout History</h2>
            </div>

            <?php if (empty($payout_requests)): ?>
                <div class="empty-state">
                    <i class="fas fa-money-bill-wave"></i>
                    <h3>No Payout Requests Yet</h3>
                    <p>Your payout requests will appear here once submitted</p>
                </div>
            <?php else: ?>
            <table class="transactions-table">
                <thead>
                    <tr>
                        <th>DATE REQUESTED</th>
                        <th>AMOUNT</th>
                        <th>METHOD</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payout_requests as $request): ?>
                    <tr>
                        <td class="transaction-date">
                            <?php echo date('d M Y', strtotime($request['requested_at'])); ?>
                        </td>
                        <td class="transaction-amount">
                            KES <?php echo number_format($request['amount'], 2); ?>
                        </td>
                        <td class="transaction-method">
                            <?php 
                            $method = ucfirst(str_replace('_', ' ', $request['payment_method']));
                            echo htmlspecialchars($method);
                            ?>
                        </td>
                        <td>
                            <span class="transaction-status <?php echo strtolower($request['status']); ?>">
                                <i class="fas fa-circle"></i>
                                <?php echo htmlspecialchars($request['display_status'] ?? $request['status']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>

        <!-- Withdrawal Panel -->
        <div class="withdrawal-panel">
            <div class="withdrawal-header">
                <div class="withdrawal-icon">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <h3>Request Payout</h3>
            </div>

            <div class="withdrawal-balance">
                <div class="balance-label">Available to Withdraw</div>
                <div class="balance-amount">KES <?php echo number_format($available_balance ?? 0, 2); ?></div>
            </div>

            <form action="/agent/payouts/request" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? ''); ?>">

                <div class="transfer-method-section">
                    <div class="transfer-label">Payment Method</div>
                    <select class="filter-select" name="payment_method" id="payment_method" onchange="togglePaymentFields()">
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
                        <div class="amount-input-section" style="margin-top: 12px;">
                            <div class="amount-label">Bank Name</div>
                            <input type="text" class="amount-input" name="bank_name" placeholder="Enter bank name" style="padding-left: 14px;">
                        </div>
                        <div class="amount-input-section">
                            <div class="amount-label">Account Number</div>
                            <input type="text" class="amount-input" name="account_number" placeholder="Enter account number" style="padding-left: 14px;">
                        </div>
                        <div class="amount-input-section">
                            <div class="amount-label">Account Name</div>
                            <input type="text" class="amount-input" name="account_name" placeholder="Enter account holder name" style="padding-left: 14px;">
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
                    <div class="amount-label">Amount (KES)</div>
                    <div class="amount-input-wrapper">
                        <span class="currency-symbol">KES</span>
                        <input type="number" class="amount-input" name="amount" value="1000" min="1" step="0.01" required>
                    </div>
                </div>

                <div class="amount-input-section" id="phone_section">
                    <div class="amount-label">Phone Number</div>
                    <div class="amount-input-wrapper">
                        <input type="text" class="amount-input" name="phone_number" id="phone_number" value="<?php echo htmlspecialchars($mpesa_number); ?>" required style="padding-left: 14px;">
                    </div>
                </div>
                
                <div class="amount-input-section">
                    <div class="amount-label">Notes (Optional)</div>
                    <div class="amount-input-wrapper">
                        <textarea class="amount-input" name="payout_notes" rows="2" placeholder="Any special instructions..." style="padding: 12px; height: auto;"></textarea>
                    </div>
                </div>

                <button class="btn-request-payout" type="submit">
                    Request Payout <i class="fas fa-arrow-right"></i>
                </button>
            </form>
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
