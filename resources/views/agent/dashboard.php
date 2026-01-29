<?php $page = 'dashboard'; include __DIR__ . '/../layouts/agent-header.php'; ?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="fas fa-tachometer-alt text-primary"></i> Agent Dashboard
            </h2>
            <p class="text-muted mb-0">Welcome back, <?php echo htmlspecialchars($agent['first_name'] . ' ' . $agent['last_name']); ?>!</p>
        </div>
        <div class="col-md-4 text-end">
            <span class="badge bg-<?php echo $agent['status'] === 'active' ? 'success' : 'warning'; ?> fs-6">
                <i class="fas fa-circle" style="font-size: 8px;"></i> <?php echo ucfirst($agent['status']); ?>
            </span>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card border-left-primary" style="border-left: 4px solid #667eea;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #667eea;">
                            Total Members
                        </div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($stats['total_members'] ?? 0); ?>
                        </div>
                        <small class="text-muted">Registered by you</small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x" style="color: #667eea; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card border-left-success" style="border-left: 4px solid #28a745;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Active Members
                        </div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($stats['active_members'] ?? 0); ?>
                        </div>
                        <small class="text-muted">Currently active</small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-check fa-2x" style="color: #28a745; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card border-left-warning" style="border-left: 4px solid #ffc107;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pending Commission
                        </div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">
                            KES <?php echo number_format($stats['pending_commission'] ?? 0, 2); ?>
                        </div>
                        <small class="text-muted">Awaiting approval</small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x" style="color: #ffc107; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card border-left-info" style="border-left: 4px solid #17a2b8;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            This Month
                        </div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($stats['recent_registrations'] ?? 0); ?>
                        </div>
                        <small class="text-muted">New registrations</small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-plus fa-2x" style="color: #17a2b8; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-bolt"></i> Quick Actions
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <a href="/agent/register-member" class="btn btn-agent-primary w-100">
                            <i class="fas fa-user-plus"></i> Register New Member
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="/agent/members" class="btn btn-outline-primary w-100">
                            <i class="fas fa-users"></i> View All Members
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="/agent/commissions" class="btn btn-outline-success w-100">
                            <i class="fas fa-money-bill-wave"></i> View Commissions
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="/agent/profile" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-user-edit"></i> Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Members -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-users"></i> Recent Member Registrations</span>
                <a href="/agent/members" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <?php if (empty($recent_members)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No recent registrations</p>
                        <a href="/agent/register-member" class="btn btn-sm btn-agent-primary">
                            <i class="fas fa-user-plus"></i> Register Your First Member
                        </a>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recent_members as $member): ?>
                            <div class="list-group-item px-0 border-0 border-bottom">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            <i class="fas fa-user-circle text-muted"></i>
                                            <?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?>
                                        </h6>
                                        <small class="text-muted">
                                            <i class="fas fa-id-card"></i> <?php echo htmlspecialchars($member['member_number']); ?>
                                            • <i class="fas fa-phone"></i> <?php echo htmlspecialchars($member['phone']); ?>
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($member['created_at'])); ?>
                                        </small>
                                    </div>
                                    <span class="badge bg-<?php echo $member['status'] === 'active' ? 'success' : ($member['status'] === 'inactive' ? 'secondary' : 'warning'); ?>">
                                        <?php echo ucfirst($member['status']); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent Commissions -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-money-bill-wave"></i> Recent Commissions</span>
                <a href="/agent/commissions" class="btn btn-sm btn-outline-success">View All</a>
            </div>
            <div class="card-body">
                <?php if (empty($recent_commissions)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No commissions yet</p>
                        <small class="text-muted">Register members to start earning commissions</small>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recent_commissions as $commission): ?>
                            <div class="list-group-item px-0 border-0 border-bottom">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            KES <?php echo number_format($commission['commission_amount'], 2); ?>
                                        </h6>
                                        <small class="text-muted">
                                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($commission['member_name'] ?? 'Member'); ?>
                                            • <?php echo htmlspecialchars($commission['member_number'] ?? 'N/A'); ?>
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($commission['created_at'])); ?>
                                        </small>
                                    </div>
                                    <span class="badge bg-<?php 
                                        echo $commission['status'] === 'paid' ? 'success' : 
                                             ($commission['status'] === 'pending' ? 'warning' : 'secondary'); 
                                    ?>">
                                        <?php echo ucfirst($commission['status']); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/agent-footer.php'; ?>
                <div class="card-body">
                    <?php if (empty($recent_commissions)): ?>
                        <p class="text-muted">No recent commissions</p>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($recent_commissions as $commission): ?>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">KES <?= number_format($commission['commission_amount'], 2) ?></h6>
                                            <small class="text-muted">
                                                <?= htmlspecialchars($commission['member_name']) ?> •
                                                <?= date('M j, Y', strtotime($commission['created_at'])) ?>
                                            </small>
                                        </div>
                                        <span class="badge bg-<?= $commission['status'] === 'paid' ? 'success' : ($commission['status'] === 'approved' ? 'info' : 'warning') ?>">
                                            <?= ucfirst($commission['status']) ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="/agent/members" class="btn btn-outline-primary w-100 mb-2">
                                <i class="fas fa-users"></i><br>
                                View Members
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="/agent/commissions" class="btn btn-outline-success w-100 mb-2">
                                <i class="fas fa-money-bill-wave"></i><br>
                                View Commissions
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="/agent/profile" class="btn btn-outline-info w-100 mb-2">
                                <i class="fas fa-user-edit"></i><br>
                                Update Profile
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="/logout" class="btn btn-outline-secondary w-100 mb-2">
                                <i class="fas fa-sign-out-alt"></i><br>
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/agent-footer.php'; ?>
