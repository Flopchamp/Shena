<?php
// Set page variables
$pageTitle = 'Dashboard';
$pageSubtitle = 'Overview of system statistics and recent activity';
$activePage = 'dashboard';
$userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Administrator';
$userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'Super Admin';
$notificationCount = 0; // Can be updated with real notification count

// Include header
include __DIR__ . '/../layouts/dashboard-header.php';
?>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card stat-primary">
        <div class="stat-card-value"><?php echo number_format($stats['total_members'] ?? 0); ?></div>
        <div class="stat-card-label">Total Members</div>
        <i class="bi bi-people-fill stat-card-icon"></i>
    </div>
    
    <div class="stat-card stat-success">
        <div class="stat-card-value"><?php echo number_format($stats['active_members'] ?? 0); ?></div>
        <div class="stat-card-label">Active Members</div>
        <i class="bi bi-person-check-fill stat-card-icon"></i>
    </div>
    
    <div class="stat-card stat-warning">
        <div class="stat-card-value"><?php echo number_format($stats['pending_claims'] ?? 0); ?></div>
        <div class="stat-card-label">Pending Claims</div>
        <i class="bi bi-file-earmark-text-fill stat-card-icon"></i>
    </div>
    
    <div class="stat-card stat-success">
        <div class="stat-card-value">KES <?php echo number_format($stats['monthly_revenue'] ?? 0); ?></div>
        <div class="stat-card-label">Revenue This Month</div>
        <i class="bi bi-currency-dollar stat-card-icon"></i>
    </div>
</div>

<!-- Charts and Recent Activity -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem; margin-top: 2rem;">
    
    <!-- Recent Claims -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 style="margin: 0;">Recent Claims</h4>
                <a href="/admin/claims" class="btn btn-sm btn-outline">View All</a>
            </div>
        </div>
        <div class="table-container" style="border-radius: 0;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recent_claims) && is_array($recent_claims)): ?>
                        <?php foreach ($recent_claims as $claim): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($claim->member_name ?? 'N/A'); ?></td>
                                <td><?php echo date('M d, Y', strtotime($claim->created_at)); ?></td>
                                <td>
                                    <?php if ($claim->status == 'pending'): ?>
                                        <span class="badge badge-warning">Pending</span>
                                    <?php elseif ($claim->status == 'approved'): ?>
                                        <span class="badge badge-success">Approved</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Rejected</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($claim->status == 'pending'): ?>
                                        <a href="/admin/claims/view/<?php echo $claim->id; ?>" class="btn btn-sm btn-primary">Review</a>
                                    <?php else: ?>
                                        <a href="/admin/claims/view/<?php echo $claim->id; ?>" class="btn btn-sm btn-outline">View</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--medium-grey);">No recent claims</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Recent Registrations -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 style="margin: 0;">Recent Registrations</h4>
                <a href="/admin/members" class="btn btn-sm btn-outline">View All</a>
            </div>
        </div>
        <div class="card-body">
            <?php if (!empty($recent_members) && is_array($recent_members)): ?>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <?php foreach ($recent_members as $member): ?>
                        <!-- Member Item -->
                        <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--soft-grey); border-radius: var(--radius-sm);">
                            <div style="width: 45px; height: 45px; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; flex-shrink: 0;">
                                <?php 
                                    $initials = '';
                                    if (!empty($member->first_name)) $initials .= strtoupper(substr($member->first_name, 0, 1));
                                    if (!empty($member->last_name)) $initials .= strtoupper(substr($member->last_name, 0, 1));
                                    echo $initials ?: 'NA';
                                ?>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: var(--secondary-violet);">
                                    <?php echo htmlspecialchars($member->first_name . ' ' . $member->last_name); ?>
                                </div>
                                <div style="font-size: 0.875rem; color: var(--medium-grey);">
                                    <?php echo htmlspecialchars($member->package ?? 'Individual Package'); ?> • 
                                    <?php 
                                        $created = strtotime($member->created_at);
                                        $diff = time() - $created;
                                        if ($diff < 86400) echo 'Today';
                                        elseif ($diff < 172800) echo 'Yesterday';
                                        else echo date('M d, Y', $created);
                                    ?>
                                </div>
                            </div>
                            <?php if ($member->status == 'active'): ?>
                                <span class="badge badge-success">Active</span>
                            <?php elseif ($member->status == 'pending'): ?>
                                <span class="badge badge-warning">Pending</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Suspended</span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="text-align: center; color: var(--medium-grey); padding: 2rem;">No recent registrations</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card" style="margin-top: 2rem;">
    <div class="card-header">
        <h4 style="margin: 0;">Quick Actions</h4>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <a href="/admin/members/add" class="btn btn-primary" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 1.25rem;">
                <i class="bi bi-person-plus-fill"></i> Add Member
            </a>
            <a href="/admin/agents/add" class="btn btn-success" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 1.25rem;">
                <i class="bi bi-person-badge-fill"></i> Add Agent
            </a>
            <a href="/admin/communications" class="btn btn-info" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 1.25rem; background: var(--info); color: white; border: none;">
                <i class="bi bi-envelope-fill"></i> Send Communication
            </a>
            <a href="/admin/reports" class="btn btn-outline" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 1.25rem;">
                <i class="bi bi-download"></i> Export Report
            </a>
        </div>
    </div>
</div>

<!-- Additional Stats -->
<?php if (!empty($stats)): ?>
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-top: 2rem;">
    <div class="card">
        <div class="card-header">
            <h5 style="margin: 0;"><i class="bi bi-cash-stack"></i> Payment Summary</h5>
        </div>
        <div class="card-body">
            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                <span>Total Payments:</span>
                <strong>KES <?php echo number_format($stats['total_payments'] ?? 0); ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                <span>This Month:</span>
                <strong style="color: var(--success);">KES <?php echo number_format($stats['monthly_revenue'] ?? 0); ?></strong>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5 style="margin: 0;"><i class="bi bi-file-earmark-check"></i> Claims Summary</h5>
        </div>
        <div class="card-body">
            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                <span>Pending:</span>
                <strong style="color: var(--warning);"><?php echo number_format($stats['pending_claims'] ?? 0); ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                <span>Approved:</span>
                <strong style="color: var(--success);"><?php echo number_format($stats['approved_claims'] ?? 0); ?></strong>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5 style="margin: 0;"><i class="bi bi-people"></i> Member Status</h5>
        </div>
        <div class="card-body">
            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                <span>Active:</span>
                <strong style="color: var(--success);"><?php echo number_format($stats['active_members'] ?? 0); ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                <span>Pending:</span>
                <strong style="color: var(--warning);"><?php echo number_format($stats['pending_members'] ?? 0); ?></strong>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/dashboard-footer.php'; ?>
