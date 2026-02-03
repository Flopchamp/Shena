<?php include_once __DIR__ . '/../layouts/admin-header.php'; ?>

<style>
    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .page-title {
        font-family: 'Playfair Display', serif;
        font-size: 28px;
        font-weight: 700;
        color: #1F2937;
        margin: 0 0 4px 0;
    }

    .page-subtitle {
        font-size: 13px;
        color: #9CA3AF;
        margin: 0;
    }

    .header-actions {
        display: flex;
        gap: 12px;
    }

    .btn-action {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: #7F3D9E;
        color: white;
    }

    .btn-primary:hover {
        background: #7F3D9E;
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: #F3F4F6;
        color: #6B7280;
    }

    .btn-secondary:hover {
        background: #E5E7EB;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #E5E7EB;
        transition: all 0.2s;
    }

    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .stat-label {
        font-size: 13px;
        font-weight: 600;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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

    .stat-icon.success {
        background: #D1FAE5;
        color: #10B981;
    }

    .stat-icon.info {
        background: #DBEAFE;
        color: #3B82F6;
    }

    .stat-icon.warning {
        background: #FEF3C7;
        color: #F59E0B;
    }

    .stat-icon.primary {
        background: #EDE9FE;
        color: #7F3D9E;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 8px;
    }

    .stat-change {
        font-size: 12px;
        color: #6B7280;
    }

    /* Table Card */
    .table-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #E5E7EB;
        margin-bottom: 30px;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 2px solid #F3F4F6;
    }

    .table-title {
        font-size: 18px;
        font-weight: 700;
        color: #1F2937;
    }

    .table-subtitle {
        font-size: 13px;
        color: #9CA3AF;
        margin-top: 4px;
    }

    .filter-group {
        display: flex;
        gap: 8px;
    }

    .filter-btn {
        padding: 8px 16px;
        border: 1px solid #E5E7EB;
        background: white;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #6B7280;
        cursor: pointer;
        transition: all 0.2s;
    }

    .filter-btn:hover {
        border-color: #7F3D9E;
        color: #7F3D9E;
    }

    .filter-btn.active {
        background: #7F3D9E;
        color: white;
        border-color: #7F3D9E;
    }

    /* Payments Table */
    .payments-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .payments-table thead th {
        background: #F9FAFB;
        padding: 12px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #E5E7EB;
    }

    .payments-table tbody td {
        padding: 16px;
        border-bottom: 1px solid #F3F4F6;
        font-size: 14px;
        color: #1F2937;
    }

    .payments-table tbody tr:hover {
        background: #F9FAFB;
    }

    .member-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .member-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
        color: white;
    }

    .member-avatar.blue {
        background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
    }

    .member-avatar.green {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
    }

    .member-avatar.purple {
        background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
    }

    .member-avatar.orange {
        background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
    }

    .member-details .member-name {
        font-weight: 600;
        color: #1F2937;
        margin-bottom: 2px;
    }

    .member-details .member-number {
        font-size: 12px;
        color: #9CA3AF;
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.completed {
        background: #D1FAE5;
        color: #059669;
    }

    .status-badge.pending {
        background: #FEF3C7;
        color: #D97706;
    }

    .status-badge.failed {
        background: #FEE2E2;
        color: #DC2626;
    }

    .type-badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        background: #F3F4F6;
        color: #6B7280;
    }

    .action-btns {
        display: flex;
        gap: 6px;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: 1px solid #E5E7EB;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        color: #6B7280;
    }

    .action-btn:hover {
        background: #7F3D9E;
        color: white;
        border-color: #7F3D9E;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 64px;
        color: #E5E7EB;
        margin-bottom: 20px;
    }

    .empty-state h5 {
        font-size: 18px;
        font-weight: 700;
        color: #6B7280;
        margin-bottom: 8px;
    }

    .empty-state p {
        font-size: 14px;
        color: #9CA3AF;
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 12px;
        border: none;
    }

    .modal-header {
        border-bottom: 2px solid #F3F4F6;
        padding: 20px 24px;
    }

    .modal-title {
        font-size: 18px;
        font-weight: 700;
        color: #1F2937;
    }

    .modal-body {
        padding: 24px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 10px 16px;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #7F3D9E;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-info {
        background: #EFF6FF;
        border: 1px solid #DBEAFE;
        color: #1E40AF;
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Payments Management</h1>
        <p class="page-subtitle">Track and manage all payment transactions</p>
    </div>
    <div class="header-actions">
        <button class="btn-action btn-secondary" data-bs-toggle="modal" data-bs-target="#verifyPaymentModal">
            <i class="fas fa-search"></i>
            Verify Payment
        </button>
        <button class="btn-action btn-primary" onclick="window.location.href='/admin/payments-reconciliation'">
            <i class="fas fa-sync-alt"></i>
            Reconciliation
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-label">Total Revenue</div>
            <div class="stat-icon success">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
        <div class="stat-value">KES <?php echo number_format(array_sum(array_column($payments ?? [], 'amount')), 0); ?></div>
        <div class="stat-change">All-time collections</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-label">Total Payments</div>
            <div class="stat-icon info">
                <i class="fas fa-receipt"></i>
            </div>
        </div>
        <div class="stat-value"><?php echo count($payments ?? []); ?></div>
        <div class="stat-change">Total transactions</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-label">Pending</div>
            <div class="stat-icon warning">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <div class="stat-value"><?php echo count(array_filter($payments ?? [], fn($p) => $p['status'] === 'pending')); ?></div>
        <div class="stat-change">Awaiting verification</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-label">This Month</div>
            <div class="stat-icon primary">
                <i class="fas fa-calendar"></i>
            </div>
        </div>
        <div class="stat-value">KES <?php 
            $thisMonth = array_filter($payments ?? [], fn($p) => 
                date('Y-m', strtotime($p['created_at'])) === date('Y-m')
            );
            echo number_format(array_sum(array_column($thisMonth, 'amount')), 0);
        ?></div>
        <div class="stat-change"><?php echo date('F Y'); ?></div>
    </div>
</div>

<!-- Payments Table -->
<div class="table-card">
    <div class="table-header">
        <div>
            <div class="table-title">Payment Transactions</div>
            <div class="table-subtitle">View and manage all payment records</div>
        </div>
        <div class="filter-group">
            <button class="filter-btn active" onclick="filterPayments('all')">All</button>
            <button class="filter-btn" onclick="filterPayments('completed')">Completed</button>
            <button class="filter-btn" onclick="filterPayments('pending')">Pending</button>
            <button class="filter-btn" onclick="filterPayments('failed')">Failed</button>
        </div>
    </div>

    <?php if (!empty($payments)): ?>
    <div style="overflow-x: auto;">
        <table class="payments-table">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Member</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $avatarColors = ['blue', 'green', 'purple', 'orange'];
                $index = 0;
                foreach ($payments as $payment): 
                    $initials = strtoupper(substr($payment['first_name'], 0, 1) . substr($payment['last_name'], 0, 1));
                    $avatarColor = $avatarColors[$index % 4];
                    $index++;
                ?>
                <tr data-status="<?php echo $payment['status']; ?>">
                    <td><strong><?php echo htmlspecialchars($payment['transaction_id'] ?? 'N/A'); ?></strong></td>
                    <td>
                        <div class="member-info">
                            <div class="member-avatar <?php echo $avatarColor; ?>"><?php echo $initials; ?></div>
                            <div class="member-details">
                                <div class="member-name"><?php echo htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']); ?></div>
                                <div class="member-number"><?php echo htmlspecialchars($payment['member_number']); ?></div>
                            </div>
                        </div>
                    </td>
                    <td><strong>KES <?php echo number_format($payment['amount'], 2); ?></strong></td>
                    <td><span class="type-badge"><?php echo ucfirst($payment['payment_type'] ?? 'monthly'); ?></span></td>
                    <td><span class="type-badge"><?php echo strtoupper($payment['payment_method'] ?? 'mpesa'); ?></span></td>
                    <td>
                        <span class="status-badge <?php echo $payment['status']; ?>">
                            <?php echo ucfirst($payment['status']); ?>
                        </span>
                    </td>
                    <td><?php echo date('M j, Y H:i', strtotime($payment['created_at'])); ?></td>
                    <td>
                        <div class="action-btns">
                            <button class="action-btn" data-bs-toggle="modal" data-bs-target="#paymentModal<?php echo $payment['id']; ?>" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn" data-bs-toggle="modal" data-bs-target="#verifyPaymentModal" title="Verify">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Payment Details Modal -->
                <div class="modal fade" id="paymentModal<?php echo $payment['id']; ?>" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Payment Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Transaction ID</label>
                                        <div style="padding: 10px; background: #F9FAFB; border-radius: 6px; font-weight: 600;">
                                            <?php echo htmlspecialchars($payment['transaction_id'] ?? 'N/A'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Amount</label>
                                        <div style="padding: 10px; background: #F9FAFB; border-radius: 6px; font-weight: 600;">
                                            KES <?php echo number_format($payment['amount'], 2); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Member Name</label>
                                        <div style="padding: 10px; background: #F9FAFB; border-radius: 6px;">
                                            <?php echo htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Member Number</label>
                                        <div style="padding: 10px; background: #F9FAFB; border-radius: 6px;">
                                            <?php echo htmlspecialchars($payment['member_number']); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Payment Type</label>
                                        <div style="padding: 10px; background: #F9FAFB; border-radius: 6px;">
                                            <?php echo ucfirst($payment['payment_type'] ?? 'monthly'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Payment Method</label>
                                        <div style="padding: 10px; background: #F9FAFB; border-radius: 6px;">
                                            <?php echo strtoupper($payment['payment_method'] ?? 'mpesa'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Status</label>
                                        <div style="padding: 10px;">
                                            <span class="status-badge <?php echo $payment['status']; ?>">
                                                <?php echo ucfirst($payment['status']); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Date & Time</label>
                                        <div style="padding: 10px; background: #F9FAFB; border-radius: 6px;">
                                            <?php echo date('M j, Y H:i:s', strtotime($payment['created_at'])); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn-action btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-money-bill-wave"></i>
        <h5>No payments found</h5>
        <p>Payment transactions will appear here</p>
    </div>
    <?php endif; ?>
</div>

<!-- Verify Payment Modal -->
<div class="modal fade" id="verifyPaymentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verify Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="verifyPaymentForm">
                <?php if (isset($_SESSION['csrf_token'])): ?>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <?php endif; ?>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <span>Use this to verify a Paybill payment or STK push when a member reports missing payment</span>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Verification Method</label>
                            <select class="form-control" name="method" id="verifyMethod" required>
                                <option value="stk">STK Push</option>
                                <option value="paybill">Paybill Receipt</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Payment Type</label>
                            <select class="form-control" name="payment_type">
                                <option value="monthly">Monthly Contribution</option>
                                <option value="registration">Registration Fee</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Amount (KES)</label>
                            <input type="number" class="form-control" name="amount" min="1" step="1" placeholder="e.g. 500">
                        </div>
                        <div class="col-md-6 mb-3" id="stkField">
                            <label class="form-label">Checkout Request ID</label>
                            <input type="text" class="form-control" name="checkout_request_id" id="checkoutRequestId" placeholder="ws_CO_...">
                        </div>
                        <div class="col-md-6 mb-3" id="receiptField" style="display: none;">
                            <label class="form-label">M-Pesa Receipt</label>
                            <input type="text" class="form-control" name="mpesa_receipt_number" id="mpesaReceiptNumber" placeholder="RH81M7...">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Member ID (Optional)</label>
                            <input type="number" class="form-control" name="member_id" id="memberIdInput" placeholder="Member ID">
                        </div>
                    </div>

                    <div id="verifyResults" style="display: none; margin-top: 20px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-action btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-action btn-primary">
                        <i class="fas fa-check"></i>
                        Verify Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
