<?php include_once __DIR__ . '/../layouts/admin-header.php'; ?>

<style>
    /* Page Header */
    .page-header {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #E5E7EB;
    }

    .page-title {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 700;
        color: #1F2937;
        margin: 0 0 0.5rem 0;
    }

    .page-subtitle {
        font-size: 0.875rem;
        color: #6B7280;
        margin: 0;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid #E5E7EB;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border-color: #7F3D9E;
        transform: translateY(-2px);
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .stat-icon-wrapper {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .stat-icon-wrapper i {
        font-size: 1.5rem;
    }

    .stat-card.members .stat-icon-wrapper,
    .stat-card.primary .stat-icon-wrapper {
        background: linear-gradient(135deg, #7F3D9E 0%, #7C3AED 100%);
        color: white;
    }

    .stat-card.finance .stat-icon-wrapper,
    .stat-card.success .stat-icon-wrapper {
        background: linear-gradient(135deg, #059669 0%, #10B981 100%);
        color: white;
    }

    .stat-card.claims .stat-icon-wrapper,
    .stat-card.warning .stat-icon-wrapper {
        background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
        color: white;
    }

    .stat-card.agents .stat-icon-wrapper,
    .stat-card.info .stat-icon-wrapper {
        background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
        color: white;
    }

    .stat-card.communications .stat-icon-wrapper {
        background: linear-gradient(135deg, #EC4899 0%, #DB2777 100%);
        color: white;
    }

    .stat-label {
        color: #6B7280;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1F2937;
        margin: 0.25rem 0;
        line-height: 1;
    }

    .stat-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.75rem;
    }

    .stat-trend {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        background: #F3F4F6;
        color: #6B7280;
    }

    .stat-trend.positive {
        background: #D1FAE5;
        color: #059669;
    }

    .stat-trend.negative {
        background: #FEE2E2;
        color: #DC2626;
    }

    .stat-trend.neutral {
        background: #E5E7EB;
        color: #6B7280;
    }

    .stat-context {
        font-size: 0.75rem;
        color: #9CA3AF;
    }

    .stat-action {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #F3F4F6;
    }

    .stat-action-link {
        color: #7F3D9E;
        font-size: 0.8125rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.875rem;
        border-radius: 999px;
        border: 1px solid rgba(127, 61, 158, 0.2);
        background: rgba(127, 61, 158, 0.08);
        letter-spacing: 0.3px;
        transition: all 0.2s ease;
    }

    .stat-action-link:hover {
        color: #7C3AED;
        gap: 0.75rem;
        border-color: rgba(127, 61, 158, 0.4);
        background: rgba(127, 61, 158, 0.12);
        transform: translateY(-1px);
    }

    /* Main Content Layout */
    .dashboard-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
    }

    @media (max-width: 1024px) {
        .dashboard-content {
            grid-template-columns: 1fr;
        }
    }

    /* Quick Actions */
    .quick-actions-section {
        margin-bottom: 2rem;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1F2937;
        margin: 0;
    }

    .quick-actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }

    .quick-action-btn {
        padding: 1.25rem;
        background: white;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        text-decoration: none;
        color: #1F2937;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.2s;
    }

    .quick-action-btn:hover {
        border-color: #7F3D9E;
        color: #7F3D9E;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .quick-action-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #7F3D9E 0%, #7C3AED 100%);
        color: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .quick-action-text {
        font-size: 0.875rem;
        font-weight: 600;
    }

    /* Activity Feed */
    .activity-feed {
        background: white;
        border-radius: 8px;
        border: 1px solid #E5E7EB;
        padding: 1.5rem;
    }

    .activity-item {
        padding: 1rem;
        border-bottom: 1px solid #F3F4F6;
        display: flex;
        gap: 1rem;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
    }

    .activity-content {
        flex: 1;
    }

    .activity-title {
        font-weight: 600;
        color: #1F2937;
        margin-bottom: 0.25rem;
        font-size: 0.875rem;
    }

    .activity-meta {
        font-size: 0.75rem;
        color: #6B7280;
        display: flex;
        gap: 0.5rem;
    }

    .activity-time {
        color: #9CA3AF;
    }

    /* Data Tables */
    .data-card {
        background: white;
        border-radius: 8px;
        border: 1px solid #E5E7EB;
        margin-bottom: 1.5rem;
    }

    .data-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #F3F4F6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .data-card-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1F2937;
        margin: 0;
    }

    .data-card-link {
        color: #7F3D9E;
        font-size: 0.8125rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 0.75rem;
        border-radius: 999px;
        border: 1px solid rgba(127, 61, 158, 0.2);
        background: rgba(127, 61, 158, 0.08);
        letter-spacing: 0.3px;
        transition: all 0.2s ease;
    }

    .data-card-link:hover {
        color: #7C3AED;
        gap: 0.6rem;
        border-color: rgba(127, 61, 158, 0.4);
        background: rgba(127, 61, 158, 0.12);
        transform: translateY(-1px);
    }

    .data-card-body {
        padding: 1.5rem;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table-row {
        border-bottom: 1px solid #F3F4F6;
    }

    .data-table-row:last-child {
        border-bottom: none;
    }

    .data-table-cell {
        padding: 1rem 0;
    }

    .entity-name {
        font-weight: 600;
        color: #1F2937;
        margin-bottom: 0.25rem;
    }

    .entity-meta {
        font-size: 0.75rem;
        color: #6B7280;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-badge.pending {
        background: #FEF3C7;
        color: #D97706;
    }

    .status-badge.active {
        background: #D1FAE5;
        color: #059669;
    }

    .status-badge.completed {
        background: #DBEAFE;
        color: #2563EB;
    }

    .status-badge.sent {
        background: #EDE9FE;
        color: #7C3AED;
    }

    .action-btn {
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #7F3D9E 0%, #7C3AED 100%);
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(127, 61, 158, 0.2);
    }

    /* Chart Container */
    .chart-card {
        background: white;
        border-radius: 8px;
        border: 1px solid #E5E7EB;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .chart-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1F2937;
        margin: 0;
    }

    .chart-container {
        position: relative;
        height: 300px;
    }

    /* Empty States */
    .empty-state {
        padding: 2rem;
        text-align: center;
        color: #9CA3AF;
    }

    .empty-icon {
        font-size: 2rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-text {
        font-size: 0.875rem;
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .quick-actions-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-content {
            grid-template-columns: 1fr;
        }

        .data-card-header,
        .data-card-body {
            padding: 1rem;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">Dashboard Overview</h1>
    <p class="page-subtitle">Welcome back! Here's what's happening with your organization today.</p>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card members">
        <div class="stat-icon-wrapper">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-label">Total Members</div>
        <div class="stat-value"><?php echo number_format($stats['total_members'] ?? 0); ?></div>
        <div class="stat-meta">
            <?php
            $memberGrowthData = $stats['member_growth'] ?? 0;
            $growth = is_array($memberGrowthData) ? ($memberGrowthData['growth_percentage'] ?? 0) : $memberGrowthData;
            $growth = is_numeric($growth) ? (float) $growth : 0;
            $trendClass = $growth > 0 ? 'positive' : ($growth < 0 ? 'negative' : 'neutral');
            ?>
            <span class="stat-trend <?php echo $trendClass; ?>">
                <i class="fas fa-arrow-<?php echo $growth > 0 ? 'up' : ($growth < 0 ? 'down' : 'right'); ?>"></i>
                <?php echo abs($growth); ?>%
            </span>
            <span class="stat-context">vs last month</span>
        </div>
        <div class="stat-action">
            <a href="/admin/members" class="stat-action-link">
                View all members
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-icon-wrapper">
            <i class="fas fa-user-check"></i>
        </div>
        <div class="stat-label">Active Members</div>
        <div class="stat-value"><?php echo number_format($stats['active_members'] ?? 0); ?></div>
        <div class="stat-meta">
            <span class="stat-trend neutral">
                <?php echo number_format(($stats['active_members'] / max($stats['total_members'], 1)) * 100, 1); ?>% of total
            </span>
        </div>
        <div class="stat-action">
            <a href="/admin/members?status=active" class="stat-action-link">
                View active members
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <div class="stat-card finance">
        <div class="stat-icon-wrapper">
            <i class="fas fa-coins"></i>
        </div>
        <div class="stat-label">Monthly Revenue</div>
        <div class="stat-value">KES <?php echo number_format($stats['monthly_revenue'] ?? 0); ?></div>
        <div class="stat-meta">
            <span class="stat-trend neutral">
                <i class="fas fa-chart-line"></i>
                <?php echo number_format($stats['contribution_count'] ?? 0); ?> contributions
            </span>
        </div>
        <div class="stat-action">
            <a href="/admin/financial-dashboard" class="stat-action-link">
                View finances
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <div class="stat-card warning">
        <div class="stat-icon-wrapper">
            <i class="fas fa-file-medical"></i>
        </div>
        <div class="stat-label">Pending Claims</div>
        <div class="stat-value"><?php echo number_format($stats['pending_claims'] ?? 0); ?></div>
        <div class="stat-meta">
            <span class="stat-trend neutral">
                <?php echo number_format($stats['approved_claims'] ?? 0); ?> approved
            </span>
        </div>
        <div class="stat-action">
            <a href="/admin/claims" class="stat-action-link">
                Process claims
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions-section">
    <div class="section-header">
        <h2 class="section-title">Quick Actions</h2>
    </div>
    <div class="quick-actions-grid">
        <a href="/admin/members/register" class="quick-action-btn">
            <div class="quick-action-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <span class="quick-action-text">Register New Member</span>
        </a>
        <a href="/admin/claims" class="quick-action-btn">
            <div class="quick-action-icon">
                <i class="fas fa-file-medical"></i>
            </div>
            <span class="quick-action-text">Process Claims</span>
        </a>
        <a href="/admin/payments-reconciliation" class="quick-action-btn">
            <div class="quick-action-icon">
                <i class="fas fa-sync-alt"></i>
            </div>
            <span class="quick-action-text">Reconcile Payments</span>
        </a>
        <a href="/admin/communications" class="quick-action-btn">
            <div class="quick-action-icon">
                <i class="fas fa-paper-plane"></i>
            </div>
            <span class="quick-action-text">Send Communication</span>
        </a>
    </div>
</div>

<!-- Main Content Area -->
<div class="dashboard-content">
    <!-- Left Column -->
    <div class="left-column">
        <!-- Chart Section -->
        <div class="chart-card">
            <div class="chart-header">
                <h2 class="chart-title">Monthly Contribution Analysis</h2>
                <a href="/admin/financial-dashboard" class="data-card-link">
                    View details
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="chart-container">
                <canvas id="contributionChart"></canvas>
            </div>
        </div>

        <!-- Recent Claims -->
        <div class="data-card">
            <div class="data-card-header">
                <h2 class="data-card-title">Recent Claims</h2>
                <a href="/admin/claims" class="data-card-link">
                    View all
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="data-card-body">
                <?php if (!empty($recent_claims)): ?>
                    <table class="data-table">
                        <?php foreach ($recent_claims as $claim): ?>
                        <tr class="data-table-row">
                            <td class="data-table-cell">
                                <div class="entity-name">
                                    Claim #<?php echo htmlspecialchars($claim['claim_number'] ?? $claim['id']); ?>
                                </div>
                                <div class="entity-meta">
                                    <?php echo htmlspecialchars($claim['deceased_name'] ?? 'N/A'); ?> • 
                                    KES <?php echo number_format($claim['claim_amount'] ?? 0); ?>
                                </div>
                            </td>
                            <td class="data-table-cell" style="text-align: right;">
                                <?php if (($claim['status'] ?? 'pending') === 'pending'): ?>
                                    <button class="action-btn" onclick="window.location.href='/admin/claims'">
                                        Review
                                    </button>
                                <?php else: ?>
                                    <span class="status-badge <?php echo strtolower($claim['status']); ?>">
                                        <?php echo htmlspecialchars($claim['status']); ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-file-medical"></i>
                        </div>
                        <p class="empty-text">No recent claims</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="data-card">
            <div class="data-card-header">
                <h2 class="data-card-title">Recent Payments</h2>
                <a href="/admin/payments" class="data-card-link">
                    View all
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="data-card-body">
                <?php if (!empty($recent_payments)): ?>
                    <table class="data-table">
                        <?php foreach ($recent_payments as $payment): ?>
                        <tr class="data-table-row">
                            <td class="data-table-cell">
                                <div class="entity-name">
                                    KES <?php echo number_format($payment['amount'] ?? 0); ?>
                                </div>
                                <div class="entity-meta">
                                    <?php echo htmlspecialchars($payment['phone_number'] ?? $payment['payment_method'] ?? 'N/A'); ?> • 
                                    <?php echo date('M d, Y', strtotime($payment['payment_date'] ?? 'now')); ?>
                                </div>
                            </td>
                            <td class="data-table-cell" style="text-align: right;">
                                <span class="status-badge <?php echo strtolower($payment['status'] ?? 'pending'); ?>">
                                    <?php echo htmlspecialchars($payment['status'] ?? 'Pending'); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <p class="empty-text">No recent payments</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="right-column">
        <!-- Recent Members -->
        <div class="data-card">
            <div class="data-card-header">
                <h2 class="data-card-title">Recent Members</h2>
                <a href="/admin/members" class="data-card-link">
                    View all
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="data-card-body">
                <?php if (!empty($recent_members)): ?>
                    <table class="data-table">
                        <?php foreach ($recent_members as $member): ?>
                        <tr class="data-table-row">
                            <td class="data-table-cell">
                                <div class="entity-name">
                                    <?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?>
                                </div>
                                <div class="entity-meta">
                                    <?php echo htmlspecialchars($member['member_number'] ?? 'N/A'); ?> • 
                                    <?php echo htmlspecialchars($member['package'] ?? 'Standard'); ?> Package
                                </div>
                            </td>
                            <td class="data-table-cell" style="text-align: right;">
                                <span class="status-badge <?php echo strtolower($member['status'] ?? 'pending'); ?>">
                                    <?php echo htmlspecialchars($member['status'] ?? 'Pending'); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <p class="empty-text">No recent members</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Activity Feed -->
        <div class="activity-feed">
            <div class="section-header">
                <h2 class="section-title">Recent Activity</h2>
            </div>
            <div class="activity-list">
                <?php if (!empty($recent_activities)): ?>
                    <?php foreach ($recent_activities as $activity): ?>
                        <div class="activity-item">
                            <div class="activity-icon" style="background: linear-gradient(135deg,
                                <?php
                                switch ($activity['type']) {
                                    case 'member_registration':
                                        echo '#7F3D9E 0%, #7C3AED 100%';
                                        break;
                                    case 'payment':
                                        echo '#059669 0%, #10B981 100%';
                                        break;
                                    case 'claim':
                                        echo '#F59E0B 0%, #D97706 100%';
                                        break;
                                    default:
                                        echo '#3B82F6 0%, #2563EB 100%';
                                }
                                ?>);">
                                <i class="fas fa-<?php
                                switch ($activity['type']) {
                                    case 'member_registration':
                                        echo 'user-plus';
                                        break;
                                    case 'payment':
                                        echo 'coins';
                                        break;
                                    case 'claim':
                                        echo 'file-medical';
                                        break;
                                    default:
                                        echo 'envelope';
                                }
                                ?>"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title"><?php echo htmlspecialchars($activity['title']); ?></div>
                                <div class="activity-meta">
                                    <span><?php echo htmlspecialchars($activity['description']); ?></span>
                                    <span class="activity-time"><?php echo date('M d, Y H:i', strtotime($activity['activity_time'])); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <p class="empty-text">No recent activities</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Additional Stats -->
        <div class="stats-grid" style="margin-top: 1.5rem;">
            <div class="stat-card agents">
                <div class="stat-icon-wrapper">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="stat-label">Agent Commissions</div>
                <div class="stat-value">KES <?php echo number_format($stats['total_commissions'] ?? 0); ?></div>
                <div class="stat-meta">
                    <span class="stat-trend neutral">
                        <?php echo number_format($stats['active_agents'] ?? 0); ?> active agents
                    </span>
                </div>
                <div class="stat-action">
                    <a href="/admin/agents/commissions" class="stat-action-link">
                        View details
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Contribution Analysis Chart
const ctx = document.getElementById('contributionChart').getContext('2d');
const gradient = ctx.createLinearGradient(0, 0, 0, 300);
gradient.addColorStop(0, 'rgba(127, 61, 158, 0.2)');
gradient.addColorStop(1, 'rgba(127, 61, 158, 0.01)');

const monthlyRevenue = <?php echo json_encode($stats['monthly_revenue'] ?? 0); ?>;

const contributionChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        datasets: [{
            label: 'Revenue (KES)',
            data: [0, 0, 0, 0, 0, 0, monthlyRevenue],
            borderColor: '#7F3D9E',
            backgroundColor: gradient,
            borderWidth: 2,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#7F3D9E',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: '#1F2937',
                titleColor: '#F9FAFB',
                bodyColor: '#F9FAFB',
                borderColor: '#7F3D9E',
                borderWidth: 1,
                callbacks: {
                    label: function(context) {
                        return 'KES ' + context.parsed.y.toLocaleString();
                    }
                }
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    color: '#6B7280',
                    font: {
                        size: 12,
                        weight: '500'
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
                    color: '#6B7280',
                    font: {
                        size: 12
                    },
                    callback: function(value) {
                        return 'KES ' + (value / 1000).toFixed(0) + 'K';
                    }
                }
            }
        }
    }
});
</script>

<?php include_once __DIR__ . '/../layouts/admin-footer.php'; ?>