<?php include_once __DIR__ . '/../layouts/admin-header.php'; ?>

<style>
    /* Emergency Alert Banner */
    .emergency-alert {
        background: linear-gradient(135deg, #DC2626 0%, #EF4444 100%);
        border-radius: 12px;
        padding: 20px 24px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
    }

    .alert-content {
        display: flex;
        align-items: center;
        gap: 16px;
        flex: 1;
    }

    .alert-icon {
        width: 48px;
        height: 48px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
    }

    .alert-text h4 {
        color: white;
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .alert-badge {
        background: white;
        color: #DC2626;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
        margin-right: 8px;
    }

    .alert-text p {
        color: rgba(255, 255, 255, 0.95);
        font-size: 13px;
        margin: 0;
    }

    .alert-text strong {
        color: white;
        font-weight: 700;
    }

    .btn-process-claim {
        padding: 12px 24px;
        background: white;
        color: #DC2626;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .btn-process-claim:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #E5E7EB;
        transition: all 0.2s;
    }

    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }

    .stat-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
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

    .stat-icon.purple {
        background: #EDE9FE;
        color: #8B5CF6;
    }

    .stat-icon.orange {
        background: #FEF3C7;
        color: #F59E0B;
    }

    .stat-icon.green {
        background: #D1FAE5;
        color: #10B981;
    }

    .stat-icon.red {
        background: #FEE2E2;
        color: #EF4444;
    }

    .stat-change {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        font-weight: 600;
        margin-left: auto;
    }

    .stat-change.positive {
        color: #10B981;
    }

    .stat-change.negative {
        color: #EF4444;
    }

    .stat-change.warning {
        color: #F59E0B;
    }

    .stat-label {
        font-size: 12px;
        color: #9CA3AF;
        font-weight: 500;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 8px;
    }

    /* Main Content Layout */
    .content-layout {
        display: grid;
        grid-template-columns: 1.8fr 1fr;
        gap: 24px;
        margin-bottom: 30px;
    }

    /* Chart Card */
    .chart-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #E5E7EB;
        position: relative;
    }

    .chart-header {
        margin-bottom: 20px;
    }

    .chart-title {
        font-size: 18px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 4px;
    }

    .chart-subtitle {
        font-size: 13px;
        color: #9CA3AF;
    }

    .chart-meta {
        display: flex;
        align-items: baseline;
        gap: 8px;
        margin-top: 16px;
        margin-bottom: 20px;
    }

    .chart-amount {
        font-size: 32px;
        font-weight: 700;
        color: #1F2937;
    }

    .chart-change {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        background: #D1FAE5;
        color: #10B981;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .chart-period {
        position: absolute;
        top: 24px;
        right: 24px;
        font-size: 12px;
        color: #6B7280;
    }

    /* Pending Actions */
    .pending-actions-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #E5E7EB;
    }

    .actions-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .actions-title {
        font-size: 18px;
        font-weight: 700;
        color: #1F2937;
    }

    .view-tasks-link {
        font-size: 12px;
        color: #8B5CF6;
        text-decoration: none;
        font-weight: 600;
        padding: 6px 12px;
        border: 1px solid #E5E7EB;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .view-tasks-link:hover {
        background: #F9FAFB;
        color: #7C3AED;
    }

    .action-item {
        padding: 16px;
        background: #F9FAFB;
        border-radius: 10px;
        margin-bottom: 12px;
    }

    .action-item:last-child {
        margin-bottom: 0;
    }

    .action-person {
        font-size: 14px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 4px;
    }

    .action-description {
        font-size: 12px;
        color: #6B7280;
        margin-bottom: 12px;
        line-height: 1.5;
    }

    .action-tag {
        display: inline-block;
        padding: 4px 8px;
        background: #EDE9FE;
        color: #8B5CF6;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        flex: 1;
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-action.primary {
        background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
        color: white;
    }

    .btn-action.primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    .btn-action.secondary {
        background: white;
        color: #6B7280;
        border: 1px solid #E5E7EB;
    }

    .btn-action.secondary:hover {
        background: #F9FAFB;
    }

    /* Member Management Table */
    .table-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #E5E7EB;
        margin-top: 24px;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .table-title {
        font-size: 18px;
        font-weight: 700;
        color: #1F2937;
    }

    .table-subtitle {
        font-size: 13px;
        color: #9CA3AF;
        margin-top: 2px;
    }

    .table-actions {
        display: flex;
        gap: 12px;
    }

    .btn-filter-table {
        padding: 8px 16px;
        background: white;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        color: #6B7280;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .btn-filter-table:hover {
        border-color: #8B5CF6;
        color: #8B5CF6;
    }

    .btn-add-member {
        padding: 8px 16px;
        background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
        border: none;
        border-radius: 8px;
        color: white;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .btn-add-member:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    .members-table {
        width: 100%;
        border-collapse: collapse;
    }

    .members-table thead {
        border-bottom: 1px solid #E5E7EB;
    }

    .members-table th {
        text-align: left;
        padding: 12px 16px;
        font-size: 11px;
        font-weight: 700;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .members-table td {
        padding: 16px;
        font-size: 13px;
        color: #1F2937;
        border-bottom: 1px solid #F3F4F6;
    }

    .members-table tbody tr:hover {
        background: #F9FAFB;
    }

    .member-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .member-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        color: white;
        flex-shrink: 0;
    }

    .member-avatar.purple {
        background: linear-gradient(135deg, #A78BFA 0%, #8B5CF6 100%);
    }

    .member-avatar.orange {
        background: linear-gradient(135deg, #FCD34D 0%, #F59E0B 100%);
    }

    .member-avatar.red {
        background: linear-gradient(135deg, #FCA5A5 0%, #EF4444 100%);
    }

    .member-details {
        flex: 1;
    }

    .member-name {
        font-weight: 600;
        color: #1F2937;
        margin-bottom: 2px;
    }

    .member-role {
        font-size: 11px;
        color: #9CA3AF;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-badge.active {
        background: #D1FAE5;
        color: #10B981;
    }

    .status-badge.defaulted {
        background: #FEE2E2;
        color: #EF4444;
    }

    .status-badge.pending {
        background: #FEF3C7;
        color: #F59E0B;
    }

    .dependents-count {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #6B7280;
    }

    .action-menu-btn {
        width: 32px;
        height: 32px;
        background: transparent;
        border: none;
        border-radius: 6px;
        color: #9CA3AF;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .action-menu-btn:hover {
        background: #F3F4F6;
        color: #6B7280;
    }

    .table-pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        font-size: 13px;
        color: #6B7280;
    }

    .pagination-buttons {
        display: flex;
        gap: 8px;
    }

    .pagination-btn {
        padding: 8px 12px;
        background: white;
        border: 1px solid #E5E7EB;
        border-radius: 6px;
        color: #6B7280;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .pagination-btn:hover:not(:disabled) {
        border-color: #8B5CF6;
        color: #8B5CF6;
    }

    .pagination-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    @media (max-width: 1200px) {
        .content-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .emergency-alert {
            flex-direction: column;
            gap: 16px;
        }

        .btn-process-claim {
            width: 100%;
        }
    }

    /* Page Title */
    .page-title-section {
        margin-bottom: 24px;
    }

    .page-title {
        font-family: 'Playfair Display', serif;
        font-size: 28px;
        font-weight: 700;
        color: #1F2937;
        margin: 0;
    }
</style>

<!-- Page Title -->
<div class="page-title-section">
    <h1 class="page-title">Admin Overview</h1>
</div>

<!-- Emergency Alert Banner -->
<div class="emergency-alert">
    <div class="alert-content">
        <div class="alert-icon">
            <i class="fas fa-bell"></i>
        </div>
        <div class="alert-text">
            <h4>
                <span class="alert-badge">CRITICAL ALERT</span>
                Emergency Death Notification
            </h4>
            <p>
                Member <strong>John Doe (ID: #34592)</strong> reported at <strong>10:48 AM</strong>. Acknowledged for immediate fast-respect service and funeral procedures.
            </p>
        </div>
    </div>
    <button class="btn-process-claim">PROCESS CLAIM</button>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <!-- Total Members -->
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon purple">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>+2.8%</span>
            </div>
        </div>
        <div class="stat-label">Total Members</div>
        <div class="stat-value"><?php echo number_format($stats['total_members'] ?? 12650); ?></div>
    </div>

    <!-- Pending Claims -->
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon orange">
                <i class="fas fa-file-medical"></i>
            </div>
            <div class="stat-change warning">
                <span>None</span>
            </div>
        </div>
        <div class="stat-label">Pending Claims</div>
        <div class="stat-value"><?php echo number_format($stats['pending_claims'] ?? 42); ?></div>
    </div>

    <!-- Contributions (Est) -->
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green">
                <i class="fas fa-coins"></i>
            </div>
            <div class="stat-change negative">
                <i class="fas fa-arrow-down"></i>
                <span>-8%</span>
            </div>
        </div>
        <div class="stat-label">Contributions (Est)</div>
        <div class="stat-value"><?php echo number_format(($stats['monthly_revenue'] ?? 1200000) / 1000000, 1); ?>M</div>
    </div>

    <!-- System Defaulters -->
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon red">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-change negative">
                <i class="fas fa-arrow-up"></i>
                <span>+6.2%</span>
            </div>
        </div>
        <div class="stat-label">System Defaulters</div>
        <div class="stat-value"><?php echo number_format($stats['defaulters'] ?? 156); ?></div>
    </div>
</div>

<!-- Main Content Layout -->
<div class="content-layout">
    <!-- Left Column: Chart + Table -->
    <div>
        <!-- Contribution Analysis Chart -->
        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <div class="chart-title">Contribution Analysis</div>
                    <div class="chart-subtitle">Performance over the current fiscal year</div>
                </div>
            </div>
            <span class="chart-period">Fiscal Year 2025</span>
            <div class="chart-meta">
                <div class="chart-amount">KES <?php echo number_format(($stats['yearly_revenue'] ?? 8420000) / 1000000, 2); ?>M</div>
                <div class="chart-change">
                    <i class="fas fa-arrow-up"></i>
                    <span>+15%</span>
                </div>
            </div>
            <canvas id="contributionChart" height="100"></canvas>
        </div>

        <!-- Member Management Table -->
        <div class="table-card">
            <div class="table-header">
                <div>
                    <div class="table-title">Member Management</div>
                    <div class="table-subtitle">Manage and track member directory</div>
                </div>
                <div class="table-actions">
                    <button class="btn-filter-table">
                        <i class="fas fa-filter"></i>
                        Filter
                    </button>
                    <button class="btn-add-member">
                        <i class="fas fa-user-plus"></i>
                        Add New Member
                    </button>
                </div>
            </div>

            <table class="members-table">
                <thead>
                    <tr>
                        <th>FULL NAME</th>
                        <th>NATIONAL ID</th>
                        <th>STATUS</th>
                        <th>LAST ACTIVITY</th>
                        <th>DEPENDENTS</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sample_members = [
                        ['name' => 'Alice Mwangi', 'role' => 'Active', 'id' => '32458781', 'status' => 'active', 'activity' => 'Oct 12, 2023', 'dependents' => 4, 'avatar' => 'AM', 'color' => 'purple'],
                        ['name' => 'Peter Kamau', 'role' => 'Defaulter', 'id' => '28481032', 'status' => 'defaulted', 'activity' => 'Aug 25, 2023', 'dependents' => 2, 'avatar' => 'PK', 'color' => 'orange'],
                        ['name' => 'Samuel Otieno', 'role' => 'Pending Approval', 'id' => '30791445', 'status' => 'pending', 'activity' => 'N/A', 'dependents' => 5, 'avatar' => 'SO', 'color' => 'red']
                    ];
                    
                    foreach ($sample_members as $member): 
                    ?>
                    <tr>
                        <td>
                            <div class="member-info">
                                <div class="member-avatar <?php echo $member['color']; ?>">
                                    <?php echo $member['avatar']; ?>
                                </div>
                                <div class="member-details">
                                    <div class="member-name"><?php echo $member['name']; ?></div>
                                    <div class="member-role"><?php echo $member['role']; ?></div>
                                </div>
                            </div>
                        </td>
                        <td><?php echo $member['id']; ?></td>
                        <td>
                            <span class="status-badge <?php echo $member['status']; ?>">
                                <?php echo strtoupper($member['status']); ?>
                            </span>
                        </td>
                        <td><?php echo $member['activity']; ?></td>
                        <td>
                            <div class="dependents-count">
                                <i class="fas fa-users" style="font-size: 12px;"></i>
                                <span><?php echo $member['dependents']; ?></span>
                            </div>
                        </td>
                        <td>
                            <button class="action-menu-btn">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="table-pagination">
                <div>DISPLAYING 3 OF 12,545 ENTRIES</div>
                <div class="pagination-buttons">
                    <button class="pagination-btn" disabled>Previous</button>
                    <button class="pagination-btn">Next</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Pending Actions -->
    <div>
        <div class="pending-actions-card">
            <div class="actions-header">
                <div class="actions-title">Pending Actions</div>
                <a href="/admin/tasks" class="view-tasks-link">
                    <i class="fas fa-tasks"></i>
                    View Tasks
                </a>
            </div>

            <div class="action-item">
                <div class="action-person">Sarah Williams</div>
                <div class="action-description">Death claim ID: 29849-03 at 8:40 AM<br>REQ: Executive Immediate Approval</div>
                <div class="action-tag">CLAIM</div>
                <div class="action-buttons">
                    <button class="btn-action primary">Approval</button>
                    <button class="btn-action secondary">Review</button>
                </div>
            </div>

            <div class="action-item">
                <div class="action-person">Robert Kinyana</div>
                <div class="action-description">Standard Plan Registration<br>Initiated on Oct 3. Requires final verification</div>
                <div class="action-tag">REGISTRATION</div>
                <div class="action-buttons">
                    <button class="btn-action primary">Verify</button>
                    <button class="btn-action secondary">View</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Contribution Analysis Chart
const ctx = document.getElementById('contributionChart').getContext('2d');
const gradient = ctx.createLinearGradient(0, 0, 0, 300);
gradient.addColorStop(0, 'rgba(139, 92, 246, 0.3)');
gradient.addColorStop(1, 'rgba(139, 92, 246, 0.01)');

const contributionChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['JAN', 'MAR', 'MAY', 'JUL', 'SEP', 'NOV'],
        datasets: [{
            label: 'Contributions',
            data: [520, 680, 590, 720, 850, 730],
            borderColor: '#8B5CF6',
            backgroundColor: gradient,
            borderWidth: 3,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#8B5CF6',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    color: '#9CA3AF',
                    font: {
                        size: 11,
                        weight: '600'
                    }
                }
            },
            y: {
                beginAtZero: true,
                grid: {
                    color: '#F3F4F6',
                    drawBorder: false
                },
                ticks: {
                    color: '#9CA3AF',
                    font: {
                        size: 11
                    },
                    callback: function(value) {
                        return value + 'K';
                    }
                }
            }
        }
    }
});
</script>

<?php include_once __DIR__ . '/../layouts/admin-footer.php'; ?>
