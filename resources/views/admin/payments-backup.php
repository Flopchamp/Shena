<?php include_once 'admin-header.php'; ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-money-bill-wave mr-2"></i>Payments Management
        </h1>
        <div class="btn-group">
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="/admin/payments">All Payments</a>
                <a class="dropdown-item" href="/admin/payments?status=completed">Completed</a>
                <a class="dropdown-item" href="/admin/payments?status=pending">Pending</a>
                <a class="dropdown-item" href="/admin/payments?status=failed">Failed</a>
            </div>
        </div>
    </div>

    <!-- Payments Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Revenue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                KES <?php echo number_format(array_sum(array_column($payments, 'amount')), 2); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Payments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo count($payments); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Payments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo count(array_filter($payments, fn($p) => $p['status'] === 'pending')); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                This Month
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                KES <?php 
                                    $thisMonth = array_filter($payments, fn($p) => 
                                        date('Y-m', strtotime($p['created_at'])) === date('Y-m')
                                    );
                                    echo number_format(array_sum(array_column($thisMonth, 'amount')), 2);
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Payment Transactions</h6>
        </div>
        <div class="card-body">
            <?php if (!empty($payments)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
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
                            <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($payment['transaction_id'] ?? 'N/A'); ?></td>
                                <td>
                                    <div>
                                        <strong><?php echo htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']); ?></strong><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($payment['member_number']); ?></small>
                                    </div>
                                </td>
                                <td>
                                    <strong>KES <?php echo number_format($payment['amount'], 2); ?></strong>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">
                                        <?php echo ucfirst($payment['payment_type'] ?? 'monthly'); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        <?php echo strtoupper($payment['payment_method'] ?? 'mpesa'); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-<?php 
                                        echo match($payment['status']) {
                                            'completed' => 'success',
                                            'pending' => 'warning',
                                            'failed' => 'danger',
                                            default => 'secondary'
                                        };
                                    ?>">
                                        <?php echo ucfirst($payment['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y H:i', strtotime($payment['created_at'])); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#paymentModal<?php echo $payment['id']; ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <?php if ($payment['status'] === 'pending'): ?>
                                        <button type="button" class="btn btn-success btn-sm" onclick="confirmPayment(<?php echo $payment['id']; ?>)">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="failPayment(<?php echo $payment['id']; ?>)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>

                            <!-- Payment Details Modal -->
                            <div class="modal fade" id="paymentModal<?php echo $payment['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Payment Details</h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Payment Information</h6>
                                                    <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($payment['transaction_id'] ?? 'N/A'); ?></p>
                                                    <p><strong>Amount:</strong> KES <?php echo number_format($payment['amount'], 2); ?></p>
                                                    <p><strong>Type:</strong> <?php echo ucfirst($payment['payment_type'] ?? 'monthly'); ?></p>
                                                    <p><strong>Method:</strong> <?php echo strtoupper($payment['payment_method'] ?? 'mpesa'); ?></p>
                                                    <p><strong>Status:</strong> <span class="badge badge-<?php echo $payment['status'] === 'completed' ? 'success' : 'warning'; ?>"><?php echo ucfirst($payment['status']); ?></span></p>
                                                    <p><strong>Date:</strong> <?php echo date('M j, Y H:i:s', strtotime($payment['created_at'])); ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Member Information</h6>
                                                    <p><strong>Name:</strong> <?php echo htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']); ?></p>
                                                    <p><strong>Member Number:</strong> <?php echo htmlspecialchars($payment['member_number']); ?></p>
                                                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($payment['phone_number'] ?? 'N/A'); ?></p>
                                                    <p><strong>Reference:</strong> <?php echo htmlspecialchars($payment['reference'] ?? 'N/A'); ?></p>
                                                    <p><strong>Notes:</strong> <?php echo htmlspecialchars($payment['notes'] ?? 'N/A'); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-money-bill-wave fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">No payments found</h5>
                    <p class="text-gray-500">Payment transactions will appear here.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function confirmPayment(paymentId) {
    if (confirm('Confirm this payment as completed?')) {
        // Implementation for confirming payment
        window.location.href = `/admin/payments/confirm/${paymentId}`;
    }
}

function failPayment(paymentId) {
    const reason = prompt('Reason for payment failure:');
    if (reason) {
        // Implementation for marking payment as failed
        window.location.href = `/admin/payments/fail/${paymentId}?reason=${encodeURIComponent(reason)}`;
    }
}
</script>

<?php include_once 'admin-footer.php'; ?>
