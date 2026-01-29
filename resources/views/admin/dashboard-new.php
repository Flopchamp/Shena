<?php
// Set page variables
$pageTitle = 'Dashboard';
$pageSubtitle = 'Overview of system statistics and recent activity';
$activePage = 'dashboard';
$userName = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Administrator';
$userRole = 'Super Admin';
$notificationCount = 5; // Example

// Include header
include __DIR__ . '/../layouts/dashboard-header.php';
?>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card stat-primary">
        <div class="stat-card-value">1,247</div>
        <div class="stat-card-label">Total Members</div>
        <i class="bi bi-people-fill stat-card-icon"></i>
    </div>
    
    <div class="stat-card stat-success">
        <div class="stat-card-value">1,105</div>
        <div class="stat-card-label">Active Members</div>
        <i class="bi bi-person-check-fill stat-card-icon"></i>
    </div>
    
    <div class="stat-card stat-warning">
        <div class="stat-card-value">12</div>
        <div class="stat-card-label">Pending Claims</div>
        <i class="bi bi-file-earmark-text-fill stat-card-icon"></i>
    </div>
    
    <div class="stat-card stat-success">
        <div class="stat-card-value">KES 845,600</div>
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
                    <tr>
                        <td>John Doe</td>
                        <td>Jan 28, 2026</td>
                        <td><span class="badge badge-warning">Pending</span></td>
                        <td><a href="#" class="btn btn-sm btn-primary">Review</a></td>
                    </tr>
                    <tr>
                        <td>Jane Smith</td>
                        <td>Jan 27, 2026</td>
                        <td><span class="badge badge-success">Approved</span></td>
                        <td><a href="#" class="btn btn-sm btn-outline">View</a></td>
                    </tr>
                    <tr>
                        <td>Peter Mwangi</td>
                        <td>Jan 26, 2026</td>
                        <td><span class="badge badge-warning">Pending</span></td>
                        <td><a href="#" class="btn btn-sm btn-primary">Review</a></td>
                    </tr>
                    <tr>
                        <td>Mary Wanjiku</td>
                        <td>Jan 25, 2026</td>
                        <td><span class="badge badge-success">Approved</span></td>
                        <td><a href="#" class="btn btn-sm btn-outline">View</a></td>
                    </tr>
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
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <!-- Member Item -->
                <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--soft-grey); border-radius: var(--radius-sm);">
                    <div style="width: 45px; height: 45px; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; flex-shrink: 0;">
                        JK
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; color: var(--secondary-violet);">James Kariuki</div>
                        <div style="font-size: 0.875rem; color: var(--medium-grey);">Individual Package • Today</div>
                    </div>
                    <span class="badge badge-success">Active</span>
                </div>
                
                <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--soft-grey); border-radius: var(--radius-sm);">
                    <div style="width: 45px; height: 45px; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; flex-shrink: 0;">
                        AO
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; color: var(--secondary-violet);">Alice Ochieng</div>
                        <div style="font-size: 0.875rem; color: var(--medium-grey);">Family Package • Today</div>
                    </div>
                    <span class="badge badge-success">Active</span>
                </div>
                
                <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--soft-grey); border-radius: var(--radius-sm);">
                    <div style="width: 45px; height: 45px; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; flex-shrink: 0;">
                        DK
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; color: var(--secondary-violet);">David Kamau</div>
                        <div style="font-size: 0.875rem; color: var(--medium-grey);">Couple Package • Yesterday</div>
                    </div>
                    <span class="badge badge-success">Active</span>
                </div>
                
                <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--soft-grey); border-radius: var(--radius-sm);">
                    <div style="width: 45px; height: 45px; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; flex-shrink: 0;">
                        GN
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; color: var(--secondary-violet);">Grace Njeri</div>
                        <div style="font-size: 0.875rem; color: var(--medium-grey);">Individual Package • 2 days ago</div>
                    </div>
                    <span class="badge badge-warning">Pending</span>
                </div>
            </div>
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
            <a href="/admin/communications/send" class="btn btn-info" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 1.25rem; background: var(--info); color: white; border: none;">
                <i class="bi bi-envelope-fill"></i> Send SMS
            </a>
            <a href="/admin/reports" class="btn btn-outline" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 1.25rem;">
                <i class="bi bi-download"></i> Export Report
            </a>
        </div>
    </div>
</div>

<!-- Monthly Stats Chart Placeholder -->
<div class="card" style="margin-top: 2rem;">
    <div class="card-header">
        <h4 style="margin: 0;">Monthly Revenue Trend</h4>
    </div>
    <div class="card-body">
        <div style="height: 300px; display: flex; align-items: center; justify-content: center; background: var(--soft-grey); border-radius: var(--radius-md);">
            <div style="text-align: center; color: var(--medium-grey);">
                <i class="bi bi-graph-up" style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                <p>Chart will be implemented with Chart.js</p>
                <p style="font-size: 0.875rem;">Monthly revenue and contribution tracking</p>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/dashboard-footer.php'; ?>
