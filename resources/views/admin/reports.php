<?php
$page = 'reports';
$pageTitle = 'Reports & Analytics';
$pageSubtitle = 'View comprehensive reports and business insights';
include VIEWS_PATH . '/layouts/dashboard-header.php';
?>

<!-- Report Filters -->
<div class="card">
    <div class="card-header">
        <h4 style="margin: 0;"><i class="bi bi-sliders"></i> Report Configuration</h4>
    </div>
    <div class="card-body">
        <form method="GET" action="/admin/reports" style="display: grid; grid-template-columns: repeat(4, 1fr) auto; gap: 1rem; align-items: end;">
            <div class="form-group" style="margin: 0;">
                <label class="form-label" for="report_type">Report Type</label>
                <select id="report_type" name="report_type" class="form-select">
                    <option value="overview" <?php echo ($_GET['report_type'] ?? 'overview') === 'overview' ? 'selected' : ''; ?>>Overview</option>
                    <option value="members" <?php echo ($_GET['report_type'] ?? '') === 'members' ? 'selected' : ''; ?>>Members Report</option>
                    <option value="payments" <?php echo ($_GET['report_type'] ?? '') === 'payments' ? 'selected' : ''; ?>>Payments Report</option>
                    <option value="claims" <?php echo ($_GET['report_type'] ?? '') === 'claims' ? 'selected' : ''; ?>>Claims Report</option>
                    <option value="agents" <?php echo ($_GET['report_type'] ?? '') === 'agents' ? 'selected' : ''; ?>>Agents Report</option>
                    <option value="financial" <?php echo ($_GET['report_type'] ?? '') === 'financial' ? 'selected' : ''; ?>>Financial Summary</option>
                </select>
            </div>
            
            <div class="form-group" style="margin: 0;">
                <label class="form-label" for="date_from">Date From</label>
                <input type="date" 
                       id="date_from" 
                       name="date_from" 
                       class="form-control" 
                       value="<?php echo $_GET['date_from'] ?? date('Y-m-01'); ?>">
            </div>
            
            <div class="form-group" style="margin: 0;">
                <label class="form-label" for="date_to">Date To</label>
                <input type="date" 
                       id="date_to" 
                       name="date_to" 
                       class="form-control" 
                       value="<?php echo $_GET['date_to'] ?? date('Y-m-d'); ?>">
            </div>
            
            <div class="form-group" style="margin: 0;">
                <label class="form-label" for="format">Export Format</label>
                <select id="format" name="format" class="form-select">
                    <option value="pdf">PDF Document</option>
                    <option value="excel">Excel Spreadsheet</option>
                    <option value="csv">CSV File</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-file-earmark-bar-graph-fill"></i> Generate
            </button>
        </form>
    </div>
</div>

<!-- Key Metrics Overview -->
<div class="stats-grid" style="margin-top: 2rem;">
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--gradient-primary);">
            <i class="bi bi-people-fill"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value"><?php echo number_format($totalMembers ?? 0); ?></div>
            <div class="stat-label">Total Members</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--gradient-success);">
            <i class="bi bi-cash-stack"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value">KES <?php echo number_format($totalRevenue ?? 0); ?></div>
            <div class="stat-label">Total Revenue</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--gradient-info);">
            <i class="bi bi-file-medical-fill"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value"><?php echo number_format($totalClaims ?? 0); ?></div>
            <div class="stat-label">Total Claims</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--gradient-warning);">
            <i class="bi bi-graph-up-arrow"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value"><?php echo number_format($growthRate ?? 0, 1); ?>%</div>
            <div class="stat-label">Growth Rate</div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem;">
    <!-- Revenue Chart -->
    <div class="card">
        <div class="card-header">
            <h4 style="margin: 0;"><i class="bi bi-bar-chart-fill"></i> Monthly Revenue</h4>
        </div>
        <div class="card-body">
            <canvas id="revenueChart" style="max-height: 300px;"></canvas>
        </div>
    </div>
    
    <!-- Membership Growth Chart -->
    <div class="card">
        <div class="card-header">
            <h4 style="margin: 0;"><i class="bi bi-graph-up"></i> Membership Growth</h4>
        </div>
        <div class="card-body">
            <canvas id="membershipChart" style="max-height: 300px;"></canvas>
        </div>
    </div>
</div>

<!-- Detailed Statistics -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem;">
    <!-- Members Statistics -->
    <div class="card">
        <div class="card-header">
            <h4 style="margin: 0;"><i class="bi bi-people-fill"></i> Member Statistics</h4>
        </div>
        <div class="card-body">
            <div class="info-grid" style="gap: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.75rem; border-bottom: 1px solid var(--light-grey);">
                    <span style="color: var(--medium-grey);">Active Members:</span>
                    <span style="font-weight: 700; color: var(--primary-purple); font-size: 1.125rem;">
                        <?php echo number_format($activeMembers ?? 0); ?>
                    </span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.75rem; border-bottom: 1px solid var(--light-grey);">
                    <span style="color: var(--medium-grey);">Pending Approval:</span>
                    <span style="font-weight: 700; color: var(--warning-yellow); font-size: 1.125rem;">
                        <?php echo number_format($pendingMembers ?? 0); ?>
                    </span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.75rem; border-bottom: 1px solid var(--light-grey);">
                    <span style="color: var(--medium-grey);">Inactive Members:</span>
                    <span style="font-weight: 700; color: var(--medium-grey); font-size: 1.125rem;">
                        <?php echo number_format($inactiveMembers ?? 0); ?>
                    </span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--medium-grey);">New This Month:</span>
                    <span style="font-weight: 700; color: var(--success-green); font-size: 1.125rem;">
                        <?php echo number_format($newMembersThisMonth ?? 0); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Financial Summary -->
    <div class="card">
        <div class="card-header">
            <h4 style="margin: 0;"><i class="bi bi-wallet-fill"></i> Financial Summary</h4>
        </div>
        <div class="card-body">
            <div class="info-grid" style="gap: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.75rem; border-bottom: 1px solid var(--light-grey);">
                    <span style="color: var(--medium-grey);">Total Collections:</span>
                    <span style="font-weight: 700; color: var(--success-green); font-size: 1.125rem;">
                        KES <?php echo number_format($totalCollections ?? 0, 2); ?>
                    </span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.75rem; border-bottom: 1px solid var(--light-grey);">
                    <span style="color: var(--medium-grey);">Claims Paid:</span>
                    <span style="font-weight: 700; color: var(--danger-red); font-size: 1.125rem;">
                        KES <?php echo number_format($claimsPaid ?? 0, 2); ?>
                    </span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.75rem; border-bottom: 1px solid var(--light-grey);">
                    <span style="color: var(--medium-grey);">Agent Commissions:</span>
                    <span style="font-weight: 700; color: var(--warning-yellow); font-size: 1.125rem;">
                        KES <?php echo number_format($agentCommissions ?? 0, 2); ?>
                    </span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--medium-grey);">Net Balance:</span>
                    <span style="font-weight: 700; color: var(--primary-purple); font-size: 1.125rem;">
                        KES <?php echo number_format($netBalance ?? 0, 2); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Claims Statistics -->
<div class="card" style="margin-top: 2rem;">
    <div class="card-header">
        <h4 style="margin: 0;"><i class="bi bi-clipboard-data-fill"></i> Claims Analysis</h4>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem;">
            <div style="text-align: center; padding: 1.5rem; background: var(--soft-grey); border-radius: var(--radius-md);">
                <div style="font-size: 2rem; font-weight: 700; color: var(--primary-purple); margin-bottom: 0.5rem;">
                    <?php echo number_format($totalClaims ?? 0); ?>
                </div>
                <div style="color: var(--medium-grey); text-transform: uppercase; font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">
                    Total Claims
                </div>
            </div>
            
            <div style="text-align: center; padding: 1.5rem; background: var(--soft-grey); border-radius: var(--radius-md);">
                <div style="font-size: 2rem; font-weight: 700; color: var(--warning-yellow); margin-bottom: 0.5rem;">
                    <?php echo number_format($pendingClaims ?? 0); ?>
                </div>
                <div style="color: var(--medium-grey); text-transform: uppercase; font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">
                    Pending
                </div>
            </div>
            
            <div style="text-align: center; padding: 1.5rem; background: var(--soft-grey); border-radius: var(--radius-md);">
                <div style="font-size: 2rem; font-weight: 700; color: var(--success-green); margin-bottom: 0.5rem;">
                    <?php echo number_format($approvedClaims ?? 0); ?>
                </div>
                <div style="color: var(--medium-grey); text-transform: uppercase; font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">
                    Approved
                </div>
            </div>
            
            <div style="text-align: center; padding: 1.5rem; background: var(--soft-grey); border-radius: var(--radius-md);">
                <div style="font-size: 2rem; font-weight: 700; color: var(--danger-red); margin-bottom: 0.5rem;">
                    <?php echo number_format($rejectedClaims ?? 0); ?>
                </div>
                <div style="color: var(--medium-grey); text-transform: uppercase; font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">
                    Rejected
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Actions -->
<div class="card" style="margin-top: 2rem;">
    <div class="card-header">
        <h4 style="margin: 0;"><i class="bi bi-download"></i> Quick Export Options</h4>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
            <button class="btn btn-outline" onclick="exportReport('members-list')">
                <i class="bi bi-people-fill"></i> Export Members List
            </button>
            <button class="btn btn-outline" onclick="exportReport('payments-summary')">
                <i class="bi bi-cash-stack"></i> Export Payments Summary
            </button>
            <button class="btn btn-outline" onclick="exportReport('claims-report')">
                <i class="bi bi-file-medical-fill"></i> Export Claims Report
            </button>
            <button class="btn btn-outline" onclick="exportReport('financial-summary')">
                <i class="bi bi-wallet-fill"></i> Export Financial Summary
            </button>
            <button class="btn btn-outline" onclick="exportReport('agents-performance')">
                <i class="bi bi-person-badge-fill"></i> Export Agents Performance
            </button>
            <button class="btn btn-primary" onclick="exportReport('comprehensive')">
                <i class="bi bi-file-earmark-bar-graph-fill"></i> Export Comprehensive Report
            </button>
        </div>
    </div>
</div>

<script>
function exportReport(type) {
    const dateFrom = document.getElementById('date_from').value;
    const dateTo = document.getElementById('date_to').value;
    const format = document.getElementById('format').value;
    
    window.location.href = `/admin/reports/export?type=${type}&date_from=${dateFrom}&date_to=${dateTo}&format=${format}`;
}

// Placeholder for chart initialization - integrate with Chart.js when ready
window.addEventListener('DOMContentLoaded', function() {
    // Initialize charts here using Chart.js
    console.log('Charts will be initialized with actual data');
});
</script>

<?php include VIEWS_PATH . '/layouts/dashboard-footer.php'; ?>
