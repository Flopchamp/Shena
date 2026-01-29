
<?php include VIEWS_PATH . '/layouts/member-header.php'; ?>

<div class="container-fluid py-4">
    <h2 class="mb-4"><i class="fas fa-tachometer-alt"></i> Member Dashboard</h2>
    
    <!-- Grace Period Warning Alert -->
    <?php if (isset($member['status']) && $member['status'] === 'grace_period'): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <h4 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Grace Period Active</h4>
        <p><strong>Your account is in grace period.</strong> Please make your payment to avoid suspension.</p>
        <?php if (!empty($member['grace_period_expires'])): 
            $expiry = new DateTime($member['grace_period_expires']);
            $today = new DateTime();
            $diff = $today->diff($expiry);
            $daysLeft = $diff->days;
        ?>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <p class="mb-0"><strong>Days Remaining:</strong> 
                    <span class="badge badge-danger fs-5"><?php echo $daysLeft; ?> days</span>
                </p>
                <p class="mb-0"><small>Grace period expires on: <?php echo date('F j, Y', strtotime($member['grace_period_expires'])); ?></small></p>
            </div>
            <div class="col-md-6 text-right">
                <a href="/payments" class="btn btn-success btn-lg">
                    <i class="fas fa-money-bill-wave"></i> Make Payment Now
                </a>
            </div>
        </div>
        <?php endif; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>
    
    <?php if (isset($member['status']) && $member['status'] === 'inactive'): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <h4 class="alert-heading"><i class="fas fa-clock"></i> Maturity Period Active</h4>
        <p><strong>Your membership is in the maturity period.</strong> Coverage will begin after completion.</p>
        <?php if (!empty($member['maturity_ends'])): 
            $maturityDate = new DateTime($member['maturity_ends']);
            $today = new DateTime();
            $diff = $today->diff($maturityDate);
            $daysUntilActive = $diff->days;
        ?>
        <hr>
        <p class="mb-0"><strong>Coverage starts in:</strong> 
            <span class="badge badge-info fs-5"><?php echo $daysUntilActive; ?> days</span>
            <small class="d-block mt-1">Activation date: <?php echo date('F j, Y', strtotime($member['maturity_ends'])); ?></small>
        </p>
        <?php endif; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>
    
    <?php if (isset($member['status']) && $member['status'] === 'defaulted'): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h4 class="alert-heading"><i class="fas fa-ban"></i> Account Suspended</h4>
        <p><strong>Your account has been suspended due to non-payment.</strong></p>
        <p>To reactivate your membership, you must:</p>
        <ol>
            <li>Pay all outstanding contributions</li>
            <li>Pay a reactivation fee of KES <?php echo number_format(REACTIVATION_FEE); ?></li>
            <li>Wait for a new 4-month maturity period</li>
        </ol>
        <a href="/payments" class="btn btn-danger">
            <i class="fas fa-redo"></i> Reactivate Account
        </a>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card <?php 
                echo isset($member['status']) ? (
                    $member['status'] === 'active' ? 'bg-success' : 
                    ($member['status'] === 'grace_period' ? 'bg-warning' : 
                    ($member['status'] === 'defaulted' ? 'bg-danger' : 'bg-primary'))
                ) : 'bg-primary'; 
            ?> text-white">
                <div class="card-body">
                    <h6>Account Status</h6>
                    <h3><?php echo isset($member['status']) ? ucfirst(str_replace('_', ' ', $member['status'])) : 'N/A'; ?></h3>
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
