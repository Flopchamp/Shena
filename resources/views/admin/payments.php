<?php include_once __DIR__ . '/../layouts/admin-header.php'; ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0"><i class="fas fa-money-bill-wave me-2"></i>Payments Management</h1>
    <div class="header-actions">
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#recordPaymentModal">
            <i class="fas fa-plus me-2"></i>Record Payment
        </button>
        <button class="btn btn-success btn-sm" onclick="exportPayments()">
            <i class="fas fa-file-excel me-2"></i>Export Data
        </button>
    </div>
</div>

<!-- Payment Stats -->
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon purple">
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>
        <div class="stat-label">Total Revenue</div>
        <div class="stat-value">KES <?php echo number_format($stats['total_revenue'] ?? 0); ?></div>
        <div class="stat-change positive">
            <i class="fas fa-arrow-up"></i> This month
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="stat-label">Successful Payments</div>
        <div class="stat-value"><?php echo number_format($stats['successful_payments'] ?? 0); ?></div>
        <div class="stat-change positive">
            <i class="fas fa-arrow-up"></i> +12% from last month
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon yellow">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <div class="stat-label">Pending Verification</div>
        <div class="stat-value"><?php echo number_format($stats['pending_payments'] ?? 0); ?></div>
        <div class="stat-change">
            <i class="fas fa-minus"></i> Requires action
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon red">
                <i class="fas fa-times-circle"></i>
            </div>
        </div>
        <div class="stat-label">Failed Payments</div>
        <div class="stat-value"><?php echo number_format($stats['failed_payments'] ?? 0); ?></div>
        <div class="stat-change negative">
            <i class="fas fa-exclamation-circle"></i> Needs review
        </div>
    </div>
</div>

<!-- Payment Management Tabs -->
<ul class="nav nav-tabs mb-4" id="paymentTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="all-payments-tab" data-bs-toggle="tab" data-bs-target="#allPayments" type="button" role="tab">
            <i class="fas fa-list"></i> All Payments
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="pending-payments-tab" data-bs-toggle="tab" data-bs-target="#pendingPayments" type="button" role="tab">
            <i class="fas fa-clock"></i> Pending Payments
            <?php if (($stats['pending_payments'] ?? 0) > 0): ?>
                <span class="badge bg-warning ms-1"><?php echo $stats['pending_payments']; ?></span>
            <?php endif; ?>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="successful-tab" data-bs-toggle="tab" data-bs-target="#successfulPayments" type="button" role="tab">
            <i class="fas fa-check-circle"></i> Successful
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="failed-tab" data-bs-toggle="tab" data-bs-target="#failedPayments" type="button" role="tab">
            <i class="fas fa-times-circle"></i> Failed
            <?php if (($stats['failed_payments'] ?? 0) > 0): ?>
                <span class="badge bg-danger ms-1"><?php echo $stats['failed_payments']; ?></span>
            <?php endif; ?>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="reconciliation-tab" data-bs-toggle="tab" data-bs-target="#reconciliation" type="button" role="tab">
            <i class="fas fa-balance-scale"></i> Reconciliation
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="mpesa-tab" data-bs-toggle="tab" data-bs-target="#mpesa" type="button" role="tab">
            <i class="fas fa-mobile-alt"></i> M-Pesa Transactions
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="paymentTabContent">
    
    <!-- All Payments Tab -->
    <div class="tab-pane fade show active" id="allPayments" role="tabpanel">
        <div class="payments-table-card">
            <div class="table-header">
                <div>
                    <h3 class="table-title">All Payment Transactions</h3>
                </div>
                <div class="table-actions">
                    <select class="filter-select">
                        <option value="all">All Methods</option>
                        <option value="mpesa">M-Pesa</option>
                        <option value="bank">Bank Transfer</option>
                        <option value="cash">Cash</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>TRANSACTION ID</th>
                            <th>MEMBER</th>
                            <th>AMOUNT</th>
                            <th>METHOD</th>
                            <th>TYPE</th>
                            <th>DATE</th>
                            <th>STATUS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                        <tbody>
                            <?php if (!empty($payments)): ?>
                                <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($payment['transaction_id'] ?? 'N/A'); ?></strong></td>
                                    <td><?php echo htmlspecialchars($payment['member_name'] ?? 'Unknown'); ?></td>
                                    <td><strong>KES <?php echo number_format($payment['amount'] ?? 0); ?></strong></td>
                                    <td><?php echo ucfirst($payment['payment_method'] ?? 'N/A'); ?></td>
                                    <td><?php echo ucfirst($payment['payment_type'] ?? 'N/A'); ?></td>
                                    <td><?php echo isset($payment['created_at']) ? date('M d, Y H:i', strtotime($payment['created_at'])) : 'N/A'; ?></td>
                                    <td>
                                        <?php
                                        $status = $payment['status'] ?? 'pending';
                                        $badgeClass = match($status) {
                                            'completed', 'confirmed' => 'bg-success',
                                            'pending' => 'bg-warning',
                                            'failed' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?php echo $badgeClass; ?>"><?php echo ucfirst($status); ?></span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" onclick="viewPayment(<?php echo $payment['id']; ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">No payments found</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
        </div>
    </div>

    <!-- Pending Payments Tab -->
    <div class="tab-pane fade" id="pendingPayments" role="tabpanel">
        <div class="payments-table-card">
            <h3 class="table-title mb-3">Pending Payment Verification</h3>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>TRANSACTION ID</th>
                            <th>MEMBER</th>
                            <th>AMOUNT</th>
                            <th>METHOD</th>
                            <th>DATE</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                        <tbody>
                            <?php if (!empty($payments)): ?>
                                <?php foreach ($payments as $payment): ?>
                                    <?php if (($payment['status'] ?? '') === 'pending'): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($payment['transaction_id'] ?? 'N/A'); ?></strong></td>
                                        <td><?php echo htmlspecialchars($payment['member_name'] ?? 'Unknown'); ?></td>
                                        <td><strong>KES <?php echo number_format($payment['amount'] ?? 0); ?></strong></td>
                                        <td><?php echo ucfirst($payment['payment_method'] ?? 'N/A'); ?></td>
                                        <td><?php echo isset($payment['created_at']) ? date('M d, Y H:i', strtotime($payment['created_at'])) : 'N/A'; ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-success" onclick="confirmPayment(<?php echo $payment['id']; ?>)">
                                                <i class="fas fa-check"></i> Confirm
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="rejectPayment(<?php echo $payment['id']; ?>)">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                        <p class="text-muted">No pending payments</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Successful Payments Tab -->
    <div class="tab-pane fade" id="successfulPayments" role="tabpanel">
        <div class="payments-table-card">
            <h3 class="table-title mb-3">Successful Payment Transactions</h3>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>TRANSACTION ID</th>
                            <th>MEMBER</th>
                            <th>AMOUNT</th>
                            <th>METHOD</th>
                            <th>TYPE</th>
                            <th>DATE</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                        <tbody>
                            <?php if (!empty($payments)): ?>
                                <?php foreach ($payments as $payment): ?>
                                    <?php if (in_array($payment['status'] ?? '', ['completed', 'confirmed'])): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($payment['transaction_id'] ?? 'N/A'); ?></strong></td>
                                        <td><?php echo htmlspecialchars($payment['member_name'] ?? 'Unknown'); ?></td>
                                        <td><strong>KES <?php echo number_format($payment['amount'] ?? 0); ?></strong></td>
                                        <td><?php echo ucfirst($payment['payment_method'] ?? 'N/A'); ?></td>
                                        <td><?php echo ucfirst($payment['payment_type'] ?? 'N/A'); ?></td>
                                        <td><?php echo isset($payment['created_at']) ? date('M d, Y H:i', strtotime($payment['created_at'])) : 'N/A'; ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" onclick="viewPayment(<?php echo $payment['id']; ?>)">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            <button class="btn btn-sm btn-secondary" onclick="downloadReceipt(<?php echo $payment['id']; ?>)">
                                                <i class="fas fa-download"></i> Receipt
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">No successful payments</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Failed Payments Tab -->
    <div class="tab-pane fade" id="failedPayments" role="tabpanel">
        <div class="payments-table-card">
            <div class="table-header">
                <div>
                    <h3 class="table-title">Failed Payment Transactions</h3>
                    <p class="table-subtitle">Review and retry failed transactions</p>
                </div>
                <div class="table-actions">
                    <button class="btn btn-warning btn-sm" onclick="retryAllFailed()">
                        <i class="fas fa-redo"></i> Retry All
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>TRANSACTION ID</th>
                            <th>MEMBER</th>
                            <th>AMOUNT</th>
                            <th>METHOD</th>
                            <th>DATE</th>
                            <th>REASON</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                        <tbody>
                            <?php if (!empty($payments)): ?>
                                <?php foreach ($payments as $payment): ?>
                                    <?php if (($payment['status'] ?? '') === 'failed'): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($payment['transaction_id'] ?? 'N/A'); ?></strong></td>
                                        <td><?php echo htmlspecialchars($payment['member_name'] ?? 'Unknown'); ?></td>
                                        <td><strong>KES <?php echo number_format($payment['amount'] ?? 0); ?></strong></td>
                                        <td><?php echo ucfirst($payment['payment_method'] ?? 'N/A'); ?></td>
                                        <td><?php echo isset($payment['created_at']) ? date('M d, Y H:i', strtotime($payment['created_at'])) : 'N/A'; ?></td>
                                        <td><small><?php echo htmlspecialchars($payment['failure_reason'] ?? 'Unknown'); ?></small></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" onclick="viewPayment(<?php echo $payment['id']; ?>)">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            <button class="btn btn-sm btn-warning" onclick="retryPayment(<?php echo $payment['id']; ?>)">
                                                <i class="fas fa-redo"></i> Retry
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                        <p class="text-muted">No failed payments</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Reconciliation Tab -->
    <div class="tab-pane fade" id="reconciliation" role="tabpanel">
        <div class="payments-table-card">
            <div class="table-header">
                <div>
                    <h3 class="table-title">Payment Reconciliation</h3>
                    <p class="table-subtitle">Match and verify payment transactions</p>
                </div>
            </div>
            <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="reconStartDate">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">End Date</label>
                        <input type="date" class="form-control" id="reconEndDate">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select" id="reconMethod">
                            <option value="all">All Methods</option>
                            <option value="mpesa">M-Pesa</option>
                            <option value="bank">Bank Transfer</option>
                            <option value="cash">Cash</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <button class="btn btn-primary w-100" onclick="runReconciliation()">
                            <i class="fas fa-sync"></i> Run Reconciliation
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-header">
                                <div class="stat-icon purple">
                                    <i class="fas fa-list"></i>
                                </div>
                            </div>
                            <div class="stat-label">Total Transactions</div>
                            <div class="stat-value" id="reconTotal">0</div>
                            <div class="stat-change">All payments</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-header">
                                <div class="stat-icon green">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                            <div class="stat-label">Successful</div>
                            <div class="stat-value" id="reconSuccess">0</div>
                            <div class="stat-change positive">Confirmed</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-header">
                                <div class="stat-icon red">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                            </div>
                            <div class="stat-label">Failed/Pending</div>
                            <div class="stat-value" id="reconFailed">0</div>
                            <div class="stat-change negative">Requires action</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- M-Pesa Transactions Tab -->
    <div class="tab-pane fade" id="mpesa" role="tabpanel">
        <div class="payments-table-card">
            <div class="table-header">
                <div>
                    <h3 class="table-title">M-Pesa Transaction History</h3>
                    <p class="table-subtitle">All M-Pesa mobile money transactions</p>
                </div>
                <div class="table-actions">
                    <button class="btn btn-success btn-sm" onclick="syncMpesa()">
                        <i class="fas fa-sync"></i> Sync M-Pesa
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>M-PESA CODE</th>
                            <th>PHONE NUMBER</th>
                            <th>MEMBER</th>
                            <th>AMOUNT</th>
                            <th>TYPE</th>
                            <th>DATE</th>
                            <th>STATUS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                        <tbody>
                            <?php if (!empty($payments)): ?>
                                <?php foreach ($payments as $payment): ?>
                                    <?php if (($payment['payment_method'] ?? '') === 'mpesa'): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($payment['mpesa_receipt_number'] ?? $payment['transaction_id'] ?? 'N/A'); ?></strong></td>
                                        <td><?php echo htmlspecialchars($payment['phone_number'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($payment['member_name'] ?? 'Unknown'); ?></td>
                                        <td><strong>KES <?php echo number_format($payment['amount'] ?? 0); ?></strong></td>
                                        <td><?php echo ucfirst($payment['payment_type'] ?? 'N/A'); ?></td>
                                        <td><?php echo isset($payment['created_at']) ? date('M d, Y H:i', strtotime($payment['created_at'])) : 'N/A'; ?></td>
                                        <td>
                                            <?php
                                            $status = $payment['status'] ?? 'pending';
                                            $badgeClass = match($status) {
                                                'completed', 'confirmed' => 'bg-success',
                                                'pending' => 'bg-warning',
                                                'failed' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };
                                            ?>
                                            <span class="badge <?php echo $badgeClass; ?>"><?php echo ucfirst($status); ?></span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" onclick="viewPayment(<?php echo $payment['id']; ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-mobile-alt fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">No M-Pesa transactions found</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function viewPayment(paymentId) {
    // TODO: Implement payment details view
    alert('Viewing payment #' + paymentId);
}

function confirmPayment(paymentId) {
    if (confirm('Confirm this payment?')) {
        // TODO: Implement payment confirmation
        alert('Payment confirmed successfully!');
        location.reload();
    }
}

function rejectPayment(paymentId) {
    if (confirm('Reject this payment?')) {
        // TODO: Implement payment rejection
        alert('Payment rejected!');
        location.reload();
    }
}

function retryPayment(paymentId) {
    if (confirm('Retry this payment?')) {
        // TODO: Implement payment retry
        alert('Payment retry initiated!');
    }
}

function downloadReceipt(paymentId) {
    // TODO: Implement receipt download
    window.open('/admin/payment/' + paymentId + '/receipt', '_blank');
}

function runReconciliation() {
    const startDate = document.getElementById('reconStartDate').value;
    const endDate = document.getElementById('reconEndDate').value;
    const method = document.getElementById('reconMethod').value;
    
    if (!startDate || !endDate) {
        alert('Please select start and end dates');
        return;
    }
    
    // TODO: Implement reconciliation
    alert('Running reconciliation from ' + startDate + ' to ' + endDate);
}

function retryAllFailed() {
    if (confirm('Retry all failed payments? This will attempt to reprocess all failed transactions.')) {
        // TODO: Implement retry all failed payments
        alert('Retrying all failed payments...');
        location.reload();
    }
}

function syncMpesa() {
    // TODO: Implement M-Pesa sync
    alert('Syncing M-Pesa transactions...');
    location.reload();
}

function exportPayments() {
    // TODO: Implement payment export
    window.location.href = '/admin/payments/export';
}
</script>

<style>
/* Stats Grid - Universal Modern Design */
.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
    max-width: 100%;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #E5E7EB;
    transition: all 0.2s;
}

.stat-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    transform: translateY(-2px);
}

.stat-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.stat-icon.purple {
    background: #EDE9FE;
    color: #7F3D9E;
}

.stat-icon.green {
    background: #D1FAE5;
    color: #10B981;
}

.stat-icon.yellow {
    background: #FEF3C7;
    color: #F59E0B;
}

.stat-icon.red {
    background: #FEE2E2;
    color: #EF4444;
}

.stat-label {
    font-size: 11px;
    color: #9CA3AF;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #1F2937;
}

.stat-change {
    font-size: 12px;
    color: #6B7280;
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.stat-change.positive {
    color: #10B981;
}

.stat-change.negative {
    color: #EF4444;
}

/* Payments Table Card */
.payments-table-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    border: 1px solid #E5E7EB;
    max-width: 100%;
    overflow: hidden;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 16px;
}

.table-title {
    font-size: 18px;
    font-weight: 700;
    color: #1F2937;
}

.table-actions {
    display: flex;
    gap: 12px;
    align-items: center;
}

.filter-select {
    padding: 8px 16px;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    font-size: 13px;
    background: white;
    color: #1F2937;
    cursor: pointer;
}

.filter-select:focus {
    outline: none;
    border-color: #7F3D9E;
}

/* Table Styling */
.table-responsive {
    overflow-x: auto;
    max-width: 100%;
    -webkit-overflow-scrolling: touch;
}

.table {
    width: 100%;
    min-width: 800px;
    border-collapse: separate;
    border-spacing: 0;
}

.table thead th {
    background: #7F3D9E;
    color: white;
    padding: 14px 16px;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
}

.table thead th:first-child {
    border-radius: 8px 0 0 0;
}

.table thead th:last-child {
    border-radius: 0 8px 0 0;
}

.table tbody td {
    padding: 16px;
    border-bottom: 1px solid #F3F4F6;
    font-size: 13px;
    color: #1F2937;
}

.table tbody tr:hover {
    background: #F9FAFB;
}

/* Badge Styling */
.badge {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
}

.badge.bg-success {
    background: #D1FAE5 !important;
    color: #10B981 !important;
}

.badge.bg-warning {
    background: #FEF3C7 !important;
    color: #F59E0B !important;
}

.badge.bg-danger {
    background: #FEE2E2 !important;
    color: #EF4444 !important;
}

.badge.bg-secondary {
    background: #F3F4F6 !important;
    color: #6B7280 !important;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .stats-row {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }
}

@media (max-width: 768px) {
    .stats-row {
        grid-template-columns: 1fr;
    }
    
    .payments-table-card {
        padding: 16px;
    }
    
    .table {
        min-width: 600px;
    }
}
</style>

<script>
// Filter payments by status
function filterPayments(status) {
    const rows = document.querySelectorAll('.payments-table tbody tr[data-status]');
    const buttons = document.querySelectorAll('.filter-btn');
    
    // Update button states
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    // Filter rows
    rows.forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Toggle verification method fields
document.getElementById('verifyMethod')?.addEventListener('change', function() {
    const stkField = document.getElementById('stkField');
    const receiptField = document.getElementById('receiptField');
    
    if (this.value === 'stk') {
        stkField.style.display = 'block';
        receiptField.style.display = 'none';
    } else {
        stkField.style.display = 'none';
        receiptField.style.display = 'block';
    }
});

// Verify payment form submission
document.getElementById('verifyPaymentForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const resultsDiv = document.getElementById('verifyResults');
    resultsDiv.style.display = 'block';
    resultsDiv.innerHTML = `
        <div class="alert alert-info">
            <i class="fas fa-spinner fa-spin"></i>
            <span>Verifying payment...</span>
        </div>
    `;
    
    const formData = new FormData(this);
    
    fetch('/admin/payments/verify', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            resultsDiv.innerHTML = `
                <div class="alert" style="background: #D1FAE5; border: 1px solid #10B981; color: #065F46;">
                    <i class="fas fa-check-circle"></i>
                    <span>${data.message}</span>
                </div>
            `;
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            resultsDiv.innerHTML = `
                <div class="alert" style="background: #FEE2E2; border: 1px solid #DC2626; color: #991B1B;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>${data.message || 'Verification failed'}</span>
                </div>
            `;
        }
    })
    .catch(error => {
        resultsDiv.innerHTML = `
            <div class="alert" style="background: #FEE2E2; border: 1px solid #DC2626; color: #991B1B;">
                <i class="fas fa-exclamation-circle"></i>
                <span>Error verifying payment. Please try again.</span>
            </div>
        `;
    });
});
</script>

<?php include_once __DIR__ . '/../layouts/admin-footer.php'; ?>
