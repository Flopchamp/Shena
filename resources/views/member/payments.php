
<?php
$page = 'payments';
include VIEWS_PATH . '/layouts/member-header.php';
?>

<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-money-bill-wave"></i> Payment History</h2>
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 bg-success text-white">
                <div class="card-body">
                    <h6>Total Paid</h6>
                    <h3>KES <?php echo number_format($total_paid, 2); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 bg-warning text-white">
                <div class="card-body">
                    <h6>Pending Payments</h6>
                    <h3><?php echo $pending_count; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 bg-info text-white">
                <div class="card-body">
                    <h6>Monthly Contribution</h6>
                    <h3>KES <?php echo number_format($member->monthly_contribution, 2); ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-white">
            <h5 class="mb-0"><i class="fas fa-history"></i> Payment History</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#paymentModal">
                <i class="fas fa-plus"></i> Make Payment
            </button>
        </div>
        <div class="card-body">
            <?php if (!empty($payments)): ?>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td><?php echo date('M j, Y', strtotime($payment['payment_date'] ?? $payment['created_at'])); ?></td>
                            <td>KES <?php echo number_format($payment['amount'], 2); ?></td>
                            <td><?php echo !empty($payment['payment_type']) ? ucfirst($payment['payment_type']) : 'Monthly'; ?></td>
                            <td><?php echo !empty($payment['payment_method']) ? strtoupper($payment['payment_method']) : 'N/A'; ?></td>
                            <td><?php echo $payment['transaction_id'] ?? 'N/A'; ?></td>
                            <td><span class="badge bg-<?php echo $payment['status'] === 'completed' ? 'success' : ($payment['status'] === 'failed' ? 'danger' : 'warning'); ?>"><?php echo !empty($payment['status']) ? ucfirst($payment['status']) : 'Pending'; ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <p class="text-muted text-center py-4">No payment history</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Make Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>M-Pesa Paybill:</strong> 4163987</p>
                <p><strong>Account Number:</strong> <?php echo $member->member_number; ?></p>
                <p><strong>Amount:</strong> KES <?php echo number_format($member->monthly_contribution, 2); ?></p>
                <hr>
                <p class="text-muted">After making payment via M-Pesa, it will reflect here automatically.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/layouts/member-footer.php'; ?>
