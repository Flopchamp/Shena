<?php include_once __DIR__ . '/../layouts/admin-header.php'; ?>

<!-- Page Header with Navigation Tabs -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0"><i class="fas fa-chart-bar me-2"></i>Reports & Analytics</h1>
</div>

<!-- Reports & Analytics Navigation Tabs -->
<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" href="/admin/reports">
            <i class="fas fa-file-alt"></i> All Reports
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/admin/financial-dashboard">
            <i class="fas fa-chart-line"></i> Financial Analytics
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/admin/reports/members">
            <i class="fas fa-users"></i> Member Analytics
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/admin/reports/claims">
            <i class="fas fa-file-medical"></i> Claims Analytics
        </a>
    </li>
</ul>

<style>
    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .page-title {
        font-family: 'Playfair Display', serif;
        font-size: 28px;
        font-weight: 700;
        color: #1F2937;
        margin: 0 0 4px 0;
    }

    .page-subtitle {
        font-size: 13px;
        color: #9CA3AF;
        margin: 0;
    }

    .header-actions {
        display: flex;
        gap: 12px;
    }

    .btn-export {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-export.pdf {
        background: #DC2626;
        color: white;
    }

    .btn-export.pdf:hover {
        background: #B91C1C;
        transform: translateY(-1px);
    }

    .btn-export.excel {
        background: #10B981;
        color: white;
    }

    .btn-export.excel:hover {
        background: #059669;
        transform: translateY(-1px);
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #E5E7EB;
        margin-bottom: 30px;
    }

    .filter-title {
        font-size: 16px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 20px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 10px 16px;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #7F3D9E;
        box-shadow: 0 0 0 3px rgba(127, 61, 158, 0.1);
    }

    .btn-generate {
        background: #7F3D9E;
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        width: 100%;
    }

    .btn-generate:hover {
        background: #7F3D9E;
        transform: translateY(-1px);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #E5E7EB;
        transition: all 0.2s;
    }

    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .stat-label {
        font-size: 13px;
        font-weight: 600;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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

    .stat-icon.primary {
        background: #EDE9FE;
        color: #7F3D9E;
    }

    .stat-icon.success {
        background: #D1FAE5;
        color: #10B981;
    }

    .stat-icon.info {
        background: #DBEAFE;
        color: #3B82F6;
    }

    .stat-icon.warning {
        background: #FEF3C7;
        color: #F59E0B;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 8px;
    }

    .stat-change {
        font-size: 12px;
        color: #6B7280;
    }

    /* Charts Grid */
    .charts-grid {
        display: grid;
        grid-template-columns: 1.8fr 1fr;
        gap: 24px;
        margin-bottom: 30px;
    }

    .chart-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #E5E7EB;
    }

    .chart-header {
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 2px solid #F3F4F6;
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

    .chart-container {
        height: 300px;
        position: relative;
    }

    .chart-legend {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #6B7280;
    }

    .legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    .legend-dot.primary {
        background: #7F3D9E;
    }

    .legend-dot.success {
        background: #10B981;
    }

    .legend-dot.info {
        background: #3B82F6;
    }

    /* Table Card */
    .table-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #E5E7EB;
        margin-bottom: 30px;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 2px solid #F3F4F6;
    }

    .table-title {
        font-size: 18px;
        font-weight: 700;
        color: #1F2937;
    }

    /* Report Table */
    .report-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .report-table thead th {
        background: #F9FAFB;
        padding: 12px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #E5E7EB;
    }

    .report-table tbody td {
        padding: 16px;
        border-bottom: 1px solid #F3F4F6;
        font-size: 14px;
        color: #1F2937;
    }

    .report-table tbody tr:hover {
        background: #F9FAFB;
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.active {
        background: #D1FAE5;
        color: #059669;
    }

    .status-badge.inactive {
        background: #FEF3C7;
        color: #D97706;
    }

    .status-badge.pending {
        background: #E5E7EB;
        color: #6B7280;
    }

    .status-badge.completed {
        background: #D1FAE5;
        color: #059669;
    }

    .status-badge.failed {
        background: #FEE2E2;
        color: #DC2626;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 64px;
        color: #E5E7EB;
        margin-bottom: 20px;
    }

    .empty-state h5 {
        font-size: 18px;
        font-weight: 700;
        color: #6B7280;
        margin-bottom: 8px;
    }

    .empty-state p {
        font-size: 14px;
        color: #9CA3AF;
    }

    @media (max-width: 1024px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Reports & Analytics</h1>
        <p class="page-subtitle">Generate comprehensive reports and insights</p>
    </div>
    <div class="header-actions">
        <button class="btn-export pdf" onclick="exportReport('pdf')">
            <i class="fas fa-file-pdf"></i>
            Export PDF
        </button>
        <button class="btn-export excel" onclick="exportReport('excel')">
            <i class="fas fa-file-excel"></i>
            Export Excel
        </button>
    </div>
</div>

<!-- Report Filters -->
<div class="filter-card">
    <div class="filter-title">Report Filters</div>
    <form method="GET" action="/admin/reports">
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label">Report Type</label>
                <select name="report_type" class="form-control">
                    <option value="overview" <?php echo ($_GET['report_type'] ?? '') === 'overview' ? 'selected' : ''; ?>>Overview</option>
                    <option value="members" <?php echo ($_GET['report_type'] ?? '') === 'members' ? 'selected' : ''; ?>>Members</option>
                    <option value="payments" <?php echo ($_GET['report_type'] ?? '') === 'payments' ? 'selected' : ''; ?>>Payments</option>
                    <option value="claims" <?php echo ($_GET['report_type'] ?? '') === 'claims' ? 'selected' : ''; ?>>Claims</option>
                    <option value="financial" <?php echo ($_GET['report_type'] ?? '') === 'financial' ? 'selected' : ''; ?>>Financial</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Date From</label>
                <input type="date" name="date_from" class="form-control" value="<?php echo $_GET['date_from'] ?? date('Y-m-01'); ?>">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Date To</label>
                <input type="date" name="date_to" class="form-control" value="<?php echo $_GET['date_to'] ?? date('Y-m-d'); ?>">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn-generate">
                    <i class="fas fa-chart-bar"></i> Generate Report
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Key Metrics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-label">Total Members</div>
            <div class="stat-icon primary">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-value"><?php echo $totalMembers ?? 0; ?></div>
        <div class="stat-change">Registered members</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-label">Total Revenue</div>
            <div class="stat-icon success">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
        <div class="stat-value">KES <?php echo number_format($totalRevenue ?? 0, 0); ?></div>
        <div class="stat-change">Cumulative collections</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-label">Claims Paid</div>
            <div class="stat-icon info">
                <i class="fas fa-file-medical"></i>
            </div>
        </div>
        <div class="stat-value">KES <?php echo number_format($totalClaimsPaid ?? 0, 0); ?></div>
        <div class="stat-change">Total disbursements</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-label">Net Income</div>
            <div class="stat-icon warning">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
        <div class="stat-value">KES <?php echo number_format(($totalRevenue ?? 0) - ($totalClaimsPaid ?? 0), 0); ?></div>
        <div class="stat-change">Revenue - Claims</div>
    </div>
</div>

<!-- Charts Section -->
<div class="charts-grid">
    <!-- Revenue Trend Chart -->
    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title">Monthly Revenue Trend</div>
            <div class="chart-subtitle">Revenue performance over time</div>
        </div>
        <div class="chart-container">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Member Status Distribution -->
    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title">Member Distribution</div>
            <div class="chart-subtitle">By status category</div>
        </div>
        <div class="chart-container">
            <canvas id="memberChart"></canvas>
        </div>
        <div class="chart-legend">
            <div class="legend-item">
                <div class="legend-dot primary"></div>
                <span>Active</span>
            </div>
            <div class="legend-item">
                <div class="legend-dot success"></div>
                <span>Inactive</span>
            </div>
            <div class="legend-item">
                <div class="legend-dot info"></div>
                <span>Pending</span>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Reports -->
<?php if (($_GET['report_type'] ?? 'overview') === 'members'): ?>
<div class="table-card">
    <div class="table-header">
        <div class="table-title">Member Registration Report</div>
    </div>
    <?php if (!empty($memberReports)): ?>
    <div style="overflow-x: auto;">
        <table class="report-table">
            <thead>
                <tr>
                    <th>Member Number</th>
                    <th>Name</th>
                    <th>Package</th>
                    <th>Status</th>
                    <th>Join Date</th>
                    <th>Last Payment</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($memberReports as $member): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($member['member_number']); ?></strong></td>
                    <td><?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?></td>
                    <td><?php echo !empty($member['package_type']) ? ucfirst($member['package_type']) : 'Individual'; ?></td>
                    <td>
                        <span class="status-badge <?php echo $member['status'] === 'active' ? 'active' : 'inactive'; ?>">
                            <?php echo !empty($member['status']) ? ucfirst($member['status']) : 'Pending'; ?>
                        </span>
                    </td>
                    <td><?php echo date('M j, Y', strtotime($member['created_at'])); ?></td>
                    <td><?php echo $member['last_payment'] ? date('M j, Y', strtotime($member['last_payment'])) : 'N/A'; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-users"></i>
        <h5>No member data available</h5>
        <p>Member reports will appear here</p>
    </div>
    <?php endif; ?>
</div>

<?php elseif (($_GET['report_type'] ?? 'overview') === 'payments'): ?>
<div class="table-card">
    <div class="table-header">
        <div class="table-title">Payment Transaction Report</div>
    </div>
    <?php if (!empty($paymentReports)): ?>
    <div style="overflow-x: auto;">
        <table class="report-table">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Member</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Method</th>
                    <th>Status</th>
                            <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($paymentReports as $payment): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($payment['transaction_id']); ?></strong></td>
                    <td><?php echo htmlspecialchars($payment['member_name'] ?? 'N/A'); ?></td>
                    <td>KES <?php echo number_format($payment['amount'], 0); ?></td>
                    <td><?php echo !empty($payment['payment_type']) ? ucfirst($payment['payment_type']) : 'Monthly'; ?></td>
                    <td><?php echo !empty($payment['payment_method']) ? strtoupper($payment['payment_method']) : 'N/A'; ?></td>
                    <td>
                        <span class="status-badge <?php echo $payment['status'] === 'completed' ? 'completed' : ($payment['status'] === 'pending' ? 'pending' : 'failed'); ?>">
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
    <div class="empty-state">
        <i class="fas fa-file-invoice-dollar"></i>
        <h5>No payment data available</h5>
        <p>Payment reports will appear here</p>
    </div>
    <?php endif; ?>
</div>

<?php elseif (($_GET['report_type'] ?? 'overview') === 'claims'): ?>
<div class="table-card">
    <div class="table-header">
        <div class="table-title">Claims Processing Report</div>
    </div>
    <?php if (!empty($claimReports)): ?>
    <div style="overflow-x: auto;">
        <table class="report-table">
            <thead>
                <tr>
                    <th>Claim Number</th>
                    <th>Member</th>
                    <th>Deceased</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Filed Date</th>
                    <th>Processed Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($claimReports as $claim): ?>
                <tr>
                    <td><strong>#<?php echo str_pad($claim['id'], 4, '0', STR_PAD_LEFT); ?></strong></td>
                    <td><?php echo htmlspecialchars($claim['member_name'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($claim['deceased_name'] ?? 'N/A'); ?></td>
                    <td>KES <?php echo number_format($claim['claim_amount'], 0); ?></td>
                    <td>
                        <span class="status-badge <?php 
                            echo $claim['status'] === 'approved' ? 'completed' : 
                                 ($claim['status'] === 'pending' ? 'pending' : 'failed'); 
                        ?>">
                            <?php echo !empty($claim['status']) ? ucfirst($claim['status']) : 'Pending'; ?>
                        </span>
                    </td>
                    <td><?php echo date('M j, Y', strtotime($claim['created_at'])); ?></td>
                    <td><?php echo $claim['processed_at'] ? date('M j, Y', strtotime($claim['processed_at'])) : 'N/A'; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-file-medical"></i>
        <h5>No claims data available</h5>
        <p>Claims reports will appear here</p>
    </div>
    <?php endif; ?>
</div>

<?php elseif (($_GET['report_type'] ?? 'overview') === 'financial'): ?>
<div class="table-card">
    <div class="table-header">
        <div class="table-title">Financial Summary Report</div>
    </div>
    <?php if (!empty($financialReports)): ?>
    <div style="overflow-x: auto;">
        <table class="report-table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Amount (KES)</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalAmount = array_sum(array_column($financialReports, 'amount'));
                foreach ($financialReports as $item): 
                    $percentage = $totalAmount > 0 ? ($item['amount'] / $totalAmount) * 100 : 0;
                ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($item['category']); ?></strong></td>
                    <td>KES <?php echo number_format($item['amount'], 0); ?></td>
                    <td><?php echo number_format($percentage, 2); ?>%</td>
                </tr>
                <?php endforeach; ?>
                <tr style="background: #F9FAFB; font-weight: 700;">
                    <td>Total</td>
                    <td>KES <?php echo number_format($totalAmount, 0); ?></td>
                    <td>100%</td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-chart-pie"></i>
        <h5>No financial data available</h5>
        <p>Financial reports will appear here</p>
    </div>
    <?php endif; ?>
</div>

<?php else: ?>
<!-- Overview Report -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="table-card">
            <div class="table-header">
                <div class="table-title">Member Summary</div>
            </div>
            <div class="row">
                <div class="col-sm-6 mb-3">
                    <p style="font-size: 13px; color: #6B7280; margin-bottom: 4px;">Total Members</p>
                    <p style="font-size: 24px; font-weight: 700; color: #1F2937; margin: 0;"><?php echo $totalMembers ?? 0; ?></p>
                </div>
                <div class="col-sm-6 mb-3">
                    <p style="font-size: 13px; color: #6B7280; margin-bottom: 4px;">Active Members</p>
                    <p style="font-size: 24px; font-weight: 700; color: #10B981; margin: 0;"><?php echo $activeMembers ?? 0; ?></p>
                </div>
                <div class="col-sm-6 mb-3">
                    <p style="font-size: 13px; color: #6B7280; margin-bottom: 4px;">Inactive Members</p>
                    <p style="font-size: 24px; font-weight: 700; color: #F59E0B; margin: 0;"><?php echo $inactiveMembers ?? 0; ?></p>
                </div>
                <div class="col-sm-6 mb-3">
                    <p style="font-size: 13px; color: #6B7280; margin-bottom: 4px;">New This Month</p>
                    <p style="font-size: 24px; font-weight: 700; color: #8B5CF6; margin: 0;"><?php echo $newMembersThisMonth ?? 0; ?></p>
                </div>
                <div class="col-sm-6">
                    <p style="font-size: 13px; color: #6B7280; margin-bottom: 4px;">Pending Applications</p>
                    <p style="font-size: 24px; font-weight: 700; color: #6B7280; margin: 0;"><?php echo $pendingMembers ?? 0; ?></p>
                </div>
                <div class="col-sm-6">
                    <p style="font-size: 13px; color: #6B7280; margin-bottom: 4px;">Renewal Due</p>
                    <p style="font-size: 24px; font-weight: 700; color: #DC2626; margin: 0;"><?php echo $renewalDue ?? 0; ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="table-card">
            <div class="table-header">
                <div class="table-title">Financial Summary</div>
            </div>
            <div class="row">
                <div class="col-sm-6 mb-3">
                    <p style="font-size: 13px; color: #6B7280; margin-bottom: 4px;">Total Revenue</p>
                    <p style="font-size: 24px; font-weight: 700; color: #10B981; margin: 0;">KES <?php echo number_format($totalRevenue ?? 0, 0); ?></p>
                </div>
                <div class="col-sm-6 mb-3">
                    <p style="font-size: 13px; color: #6B7280; margin-bottom: 4px;">This Month</p>
                    <p style="font-size: 24px; font-weight: 700; color: #3B82F6; margin: 0;">KES <?php echo number_format($monthlyRevenue ?? 0, 0); ?></p>
                </div>
                <div class="col-sm-6 mb-3">
                    <p style="font-size: 13px; color: #6B7280; margin-bottom: 4px;">Claims Paid</p>
                    <p style="font-size: 24px; font-weight: 700; color: #F59E0B; margin: 0;">KES <?php echo number_format($totalClaimsPaid ?? 0, 0); ?></p>
                </div>
                <div class="col-sm-6 mb-3">
                    <p style="font-size: 13px; color: #6B7280; margin-bottom: 4px;">Net Income</p>
                    <p style="font-size: 24px; font-weight: 700; color: #8B5CF6; margin: 0;">KES <?php echo number_format(($totalRevenue ?? 0) - ($totalClaimsPaid ?? 0), 0); ?></p>
                </div>
                <div class="col-sm-6">
                    <p style="font-size: 13px; color: #6B7280; margin-bottom: 4px;">Pending Payments</p>
                    <p style="font-size: 24px; font-weight: 700; color: #6B7280; margin: 0;"><?php echo $pendingPayments ?? 0; ?></p>
                </div>
                <div class="col-sm-6">
                    <p style="font-size: 13px; color: #6B7280; margin-bottom: 4px;">Failed Payments</p>
                    <p style="font-size: 24px; font-weight: 700; color: #DC2626; margin: 0;"><?php echo $failedPayments ?? 0; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Monthly Revenue',
            data: <?php echo json_encode($monthlyRevenueData ?? array_fill(0, 12, 0)); ?>,
            borderColor: '#8B5CF6',
            backgroundColor: 'rgba(139, 92, 246, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#8B5CF6',
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
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'KES ' + value.toLocaleString();
                    }
                },
                grid: {
                    color: '#F3F4F6'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Member Chart
const memberCtx = document.getElementById('memberChart').getContext('2d');
const memberChart = new Chart(memberCtx, {
    type: 'doughnut',
    data: {
        labels: ['Active', 'Inactive', 'Pending'],
        datasets: [{
            data: [
                <?php echo $activeMembers ?? 0; ?>,
                <?php echo $inactiveMembers ?? 0; ?>,
                <?php echo $pendingMembers ?? 0; ?>
            ],
            backgroundColor: ['#8B5CF6', '#10B981', '#3B82F6'],
            borderWidth: 0
        }],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        cutout: '70%'
    }
});

function exportReport(format) {
    const reportType = new URLSearchParams(window.location.search).get('report_type') || 'overview';
    const dateFrom = new URLSearchParams(window.location.search).get('date_from') || '';
    const dateTo = new URLSearchParams(window.location.search).get('date_to') || '';
    
    let url = `/admin/reports/export?format=${format}&type=${reportType}`;
    if (dateFrom) url += `&date_from=${dateFrom}`;
    if (dateTo) url += `&date_to=${dateTo}`;
    
    window.open(url, '_blank');
}
</script>

<?php include_once __DIR__ . '/../layouts/admin-footer.php'; ?>
