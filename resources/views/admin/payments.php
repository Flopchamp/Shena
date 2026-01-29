<?php
$page = 'payments';
$pageTitle = 'Payments Management';
$pageSubtitle = 'Track and manage member contributions and transactions';
include VIEWS_PATH . '/layouts/dashboard-header.php';

// Calculate stats
$total_revenue = array_sum(array_column($payments ?? [], 'amount'));
$completed_count = count(array_filter($payments ?? [], fn($p) => $p['status'] === 'completed'));
$pending_count = count(array_filter($payments ?? [], fn($p) => $p['status'] === 'pending'));
$this_month = array_filter($payments ?? [], fn($p) => 
    date('Y-m', strtotime($p['created_at'])) === date('Y-m')
);
$this_month_revenue = array_sum(array_column($this_month, 'amount'));
?>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--gradient-success);">
            <i class="bi bi-cash-stack"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value">KES <?php echo number_format($total_revenue); ?></div>
            <div class="stat-label">Total Revenue</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--gradient-info);">
            <i class="bi bi-receipt-cutoff"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value"><?php echo number_format(count($payments ?? [])); ?></div>
            <div class="stat-label">Total Transactions</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--gradient-warning);">
            <i class="bi bi-hourglass-split"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value"><?php echo number_format($pending_count); ?></div>
            <div class="stat-label">Pending Payments</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--gradient-primary);">
            <i class="bi bi-calendar-month-fill"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value">KES <?php echo number_format($this_month_revenue); ?></div>
            <div class="stat-label">This Month</div>
        </div>
    </div>
</div>

<!-- Search and Filter Card -->
<div class="card" style="margin-top: 2rem;">
    <div class="card-header">
        <h4 style="margin: 0;"><i class="bi bi-funnel-fill"></i> Filter Payments</h4>
    </div>
    <div class="card-body">
        <form method="GET" action="/admin/payments" style="display: grid; grid-template-columns: 2fr 1fr 1fr auto auto; gap: 1rem; align-items: end;">
            <div class="form-group" style="margin: 0;">
                <label class="form-label" for="search">Search Payments</label>
                <input type="text" 
                       id="search" 
                       name="search" 
                       class="form-control" 
                       placeholder="Member name or transaction ID" 
                       value="<?php echo htmlspecialchars($search ?? ''); ?>">
            </div>
            
            <div class="form-group" style="margin: 0;">
                <label class="form-label" for="status">Status</label>
                <select id="status" name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="completed" <?php echo ($status ?? '') === 'completed' ? 'selected' : ''; ?>>Completed</option>
                    <option value="pending" <?php echo ($status ?? '') === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="failed" <?php echo ($status ?? '') === 'failed' ? 'selected' : ''; ?>>Failed</option>
                </select>
            </div>
            
            <div class="form-group" style="margin: 0;">
                <label class="form-label" for="method">Payment Method</label>
                <select id="method" name="method" class="form-select">
                    <option value="">All Methods</option>
                    <option value="mpesa" <?php echo ($method ?? '') === 'mpesa' ? 'selected' : ''; ?>>M-Pesa</option>
                    <option value="bank" <?php echo ($method ?? '') === 'bank' ? 'selected' : ''; ?>>Bank Transfer</option>
                    <option value="cash" <?php echo ($method ?? '') === 'cash' ? 'selected' : ''; ?>>Cash</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i> Search
            </button>
            
            <a href="/admin/payments" class="btn btn-outline">
                <i class="bi bi-x-circle"></i> Clear
            </a>
        </form>
    </div>
</div>

<!-- Payments Table -->
<div class="card" style="margin-top: 2rem;">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h4 style="margin: 0;"><i class="bi bi-table"></i> Payment Transactions</h4>
            <button class="btn btn-success btn-sm" onclick="window.location.href='/admin/export/payments'">
                <i class="bi bi-download"></i> Export CSV
            </button>
        </div>
    </div>
    <div class="card-body">
        <?php if (!empty($payments)): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Member</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Reference</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment): ?>
                    <tr>
                        <td>
                            <span style="font-family: var(--font-mono); font-weight: 600; color: var(--primary-purple);">
                                #<?php echo str_pad($payment['id'], 6, '0', STR_PAD_LEFT); ?>
                            </span>
                        </td>
                        <td>
                            <div>
                                <div style="font-weight: 600; color: var(--secondary-violet);">
                                    <?php echo htmlspecialchars($payment['member_name'] ?? 'N/A'); ?>
                                </div>
                                <?php if (!empty($payment['member_number'])): ?>
                                <div style="font-size: 0.75rem; color: var(--medium-grey);">
                                    <?php echo htmlspecialchars($payment['member_number']); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: var(--secondary-violet); font-family: var(--font-mono); font-size: 1.0625rem;">
                                KES <?php echo number_format($payment['amount'], 2); ?>
                            </div>
                        </td>
                        <td>
                            <?php
                            $methodIcon = match($payment['payment_method'] ?? '') {
                                'mpesa' => 'bi-phone-fill',
                                'bank' => 'bi-bank',
                                'cash' => 'bi-cash-stack',
                                default => 'bi-credit-card-fill'
                            };
                            ?>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <i class="bi <?php echo $methodIcon; ?>"></i>
                                <span><?php echo ucfirst($payment['payment_method'] ?? 'N/A'); ?></span>
                            </div>
                        </td>
                        <td>
                            <span style="font-family: var(--font-mono); font-size: 0.875rem; color: var(--medium-grey);">
                                <?php echo htmlspecialchars($payment['transaction_reference'] ?? 'N/A'); ?>
                            </span>
                        </td>
                        <td>
                            <?php
                            $statusClass = match($payment['status']) {
                                'completed' => 'badge-success',
                                'pending' => 'badge-warning',
                                'failed' => 'badge-danger',
                                default => 'badge-secondary'
                            };
                            ?>
                            <span class="badge <?php echo $statusClass; ?>">
                                <?php echo ucfirst($payment['status']); ?>
                            </span>
                        </td>
                        <td>
                            <div style="font-size: 0.875rem;">
                                <div><?php echo date('M d, Y', strtotime($payment['created_at'])); ?></div>
                                <div style="color: var(--medium-grey); font-size: 0.75rem;">
                                    <?php echo date('h:i A', strtotime($payment['created_at'])); ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-info" 
                                        onclick="viewPayment(<?php echo $payment['id']; ?>)"
                                        title="View Details">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                                
                                <?php if ($payment['status'] === 'pending'): ?>
                                <form method="POST" action="/admin/payment/confirm" style="display: inline;" onsubmit="return confirm('Confirm this payment?')">
                                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                                    <input type="hidden" name="payment_id" value="<?php echo $payment['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-success" title="Confirm">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Store payment data for modal -->
                    <script>
                        window.paymentData = window.paymentData || {};
                        window.paymentData[<?php echo $payment['id']; ?>] = {
                            transaction_id: "#<?php echo str_pad($payment['id'], 6, '0', STR_PAD_LEFT); ?>",
                            member_name: "<?php echo htmlspecialchars($payment['member_name'] ?? 'N/A'); ?>",
                            member_number: "<?php echo htmlspecialchars($payment['member_number'] ?? 'N/A'); ?>",
                            amount: "KES <?php echo number_format($payment['amount'], 2); ?>",
                            method: "<?php echo ucfirst($payment['payment_method'] ?? 'N/A'); ?>",
                            reference: "<?php echo htmlspecialchars($payment['transaction_reference'] ?? 'N/A'); ?>",
                            status: "<?php echo ucfirst($payment['status']); ?>",
                            payment_date: "<?php echo date('F d, Y \a\t h:i A', strtotime($payment['created_at'])); ?>",
                            description: "<?php echo htmlspecialchars($payment['description'] ?? 'Monthly contribution'); ?>"
                        };
                    </script>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="bi bi-receipt-cutoff" style="font-size: 4rem; color: var(--light-grey); margin-bottom: 1rem;"></i>
            <h3 style="color: var(--medium-grey); margin-bottom: 0.5rem;">No Payments Found</h3>
            <p style="color: var(--medium-grey);">
                <?php echo !empty($search) ? 'Try adjusting your search criteria.' : 'Payment transactions will appear here.'; ?>
            </p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Payment Details Modal -->
<div class="modal" id="paymentModal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3 id="modalTransactionId" style="margin: 0;"></h3>
            <button class="modal-close" onclick="closeModal('paymentModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="info-grid">
                <div class="info-item">
                    <label>Member:</label>
                    <span id="modalMemberName"></span>
                </div>
                <div class="info-item">
                    <label>Member Number:</label>
                    <span id="modalMemberNumber"></span>
                </div>
                <div class="info-item">
                    <label>Amount:</label>
                    <span id="modalAmount" style="font-size: 1.25rem; font-weight: 700; color: var(--primary-purple);"></span>
                </div>
                <div class="info-item">
                    <label>Payment Method:</label>
                    <span id="modalMethod"></span>
                </div>
                <div class="info-item">
                    <label>Transaction Reference:</label>
                    <span id="modalReference"></span>
                </div>
                <div class="info-item">
                    <label>Status:</label>
                    <span id="modalStatus"></span>
                </div>
                <div class="info-item">
                    <label>Payment Date:</label>
                    <span id="modalDate"></span>
                </div>
                <div class="info-item">
                    <label>Description:</label>
                    <span id="modalDescription"></span>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('paymentModal')">Close</button>
        </div>
    </div>
</div>

<script>
function viewPayment(paymentId) {
    const payment = window.paymentData[paymentId];
    if (!payment) return;
    
    document.getElementById('modalTransactionId').textContent = payment.transaction_id;
    document.getElementById('modalMemberName').textContent = payment.member_name;
    document.getElementById('modalMemberNumber').textContent = payment.member_number;
    document.getElementById('modalAmount').textContent = payment.amount;
    document.getElementById('modalMethod').textContent = payment.method;
    document.getElementById('modalReference').textContent = payment.reference;
    document.getElementById('modalStatus').textContent = payment.status;
    document.getElementById('modalDate').textContent = payment.payment_date;
    document.getElementById('modalDescription').textContent = payment.description;
    
    openModal('paymentModal');
}
</script>

<?php include VIEWS_PATH . '/layouts/dashboard-footer.php'; ?>
