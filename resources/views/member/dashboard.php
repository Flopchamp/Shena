
<?php include VIEWS_PATH . '/layouts/member-header.php'; ?>

<div class="container-fluid py-4">
    <h2 class="mb-4"><i class="fas fa-tachometer-alt"></i> Member Dashboard</h2>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6>Account Status</h6>
                    <h3><?php echo isset($member['status']) ? ucfirst($member['status']) : 'N/A'; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6>Monthly Contribution</h6>
                    <h3>KES <?php echo isset($member['monthly_contribution']) ? number_format($member['monthly_contribution'], 2) : '0.00'; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6>Total Payments</h6>
                    <h3><?php echo isset($stats['total_payments']) ? $stats['total_payments'] : 0; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6>Active Claims</h6>
                    <h3><?php echo isset($stats['active_claims']) ? $stats['active_claims'] : 0; ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Member Info -->
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-user"></i> Member Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Member Number:</strong> <?php echo isset($member['member_number']) ? $member['member_number'] : 'N/A'; ?></p>
                    <p><strong>Name:</strong> <?php echo (isset($member['first_name']) ? $member['first_name'] : '') . ' ' . (isset($member['last_name']) ? $member['last_name'] : ''); ?></p>
                    <p><strong>Package:</strong> <?php echo isset($member['package']) ? ucfirst($member['package']) : 'N/A'; ?></p>
                    <p><strong>Registered:</strong> <?php echo isset($member['created_at']) ? date('M j, Y', strtotime($member['created_at'])) : 'N/A'; ?></p>
                    <a href="/profile" class="btn btn-primary btn-sm">Update Profile</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-credit-card"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="/payments" class="btn btn-success btn-block mb-2">
                        <i class="fas fa-money-bill"></i> Make Payment
                    </a>
                    <a href="/beneficiaries" class="btn btn-info btn-block mb-2">
                        <i class="fas fa-users"></i> Manage Beneficiaries
                    </a>
                    <a href="/claims" class="btn btn-warning btn-block">
                        <i class="fas fa-file-medical"></i> Submit Claim
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-history"></i> Recent Payments</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($recent_payments)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_payments as $payment): ?>
                    <tr>
                        <td><?php echo date('M j, Y', strtotime($payment['payment_date'])); ?></td>
                        <td>KES <?php echo number_format($payment['amount'], 2); ?></td>
                        <td><?php echo ucfirst($payment['payment_type']); ?></td>
                        <td><span class="badge badge-<?php echo $payment['status'] === 'completed' ? 'success' : 'warning'; ?>"><?php echo ucfirst($payment['status']); ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p class="text-muted">No payments yet</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/layouts/member-footer.php'; ?>
