<?php include_once 'admin-header.php'; ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
        </h1>
        <div class="text-muted">
            Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <!-- Total Members -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Members
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo number_format($stats['total_members']); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Members -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Members
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo number_format($stats['active_members']); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Monthly Revenue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                KES <?php echo number_format($stats['monthly_revenue'], 2); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Claims -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Claims
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo number_format($stats['pending_claims']); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-medical fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="row">
        <!-- Recent Members -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Members</h6>
                    <a href="/admin/members" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($recent_members)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Member #</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Registered</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_members as $member): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($member['member_number']); ?></td>
                                        <td><?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo $member['status'] === 'active' ? 'success' : 'warning'; ?>">
                                                <?php echo !empty($member['status']) ? ucfirst($member['status']) : 'Pending'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo !empty($member['registration_date']) ? date('M j, Y', strtotime($member['registration_date'])) : (!empty($member['created_at']) ? date('M j, Y', strtotime($member['created_at'])) : 'N/A'); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center">No recent members to display.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success">Recent Payments</h6>
                    <a href="/admin/payments" class="btn btn-sm btn-success">View All</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($recent_payments)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Member</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_payments as $payment): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']); ?></td>
                                        <td>KES <?php echo number_format($payment['amount'], 2); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo $payment['status'] === 'completed' ? 'success' : 'warning'; ?>">
                                                <?php echo !empty($payment['status']) ? ucfirst($payment['status']) : 'Pending'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M j, Y', strtotime($payment['created_at'])); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center">No recent payments to display.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Claims -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-warning">Recent Claims</h6>
                    <a href="/admin/claims" class="btn btn-sm btn-warning">View All</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($recent_claims)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Member</th>
                                        <th>Deceased</th>
                                        <th>Claim Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_claims as $claim): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($claim['first_name'] . ' ' . $claim['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($claim['deceased_name']); ?></td>
                                        <td>KES <?php echo number_format($claim['claim_amount'], 2); ?></td>
                                        <td>
                                            <span class="badge badge-<?php 
                                                echo match($claim['status'] ?? 'pending') {
                                                    'submitted' => 'primary',
                                                    'approved' => 'success',
                                                    'rejected' => 'danger',
                                                    'paid' => 'info',
                                                    default => 'secondary'
                                                };
                                            ?>">
                                                <?php echo !empty($claim['status']) ? ucfirst($claim['status']) : 'Pending'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M j, Y', strtotime($claim['created_at'])); ?></td>
                                        <td>
                                            <?php if ($claim['status'] === 'submitted'): ?>
                                                <a href="/admin/claims?id=<?php echo $claim['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                    Review
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center">No recent claims to display.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-3">
                            <a href="/admin/members" class="btn btn-outline-primary btn-lg btn-block">
                                <i class="fas fa-users fa-2x mb-2"></i><br>
                                Manage Members
                            </a>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <a href="/admin/payments" class="btn btn-outline-success btn-lg btn-block">
                                <i class="fas fa-money-bill-wave fa-2x mb-2"></i><br>
                                View Payments
                            </a>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <a href="/admin/claims" class="btn btn-outline-warning btn-lg btn-block">
                                <i class="fas fa-file-medical fa-2x mb-2"></i><br>
                                Process Claims
                            </a>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <a href="/admin/communications" class="btn btn-outline-info btn-lg btn-block">
                                <i class="fas fa-envelope fa-2x mb-2"></i><br>
                                Send Messages
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'admin-footer.php'; ?>
