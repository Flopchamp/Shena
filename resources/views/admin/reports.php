<?php include_once 'admin-header.php'; ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-bar mr-2"></i>Reports & Analytics
        </h1>
        <div class="btn-group">
            <button type="button" class="btn btn-primary" onclick="exportReport('pdf')">
                <i class="fas fa-file-pdf mr-2"></i>Export PDF
            </button>
            <button type="button" class="btn btn-success" onclick="exportReport('excel')">
                <i class="fas fa-file-excel mr-2"></i>Export Excel
            </button>
        </div>
    </div>

    <!-- Report Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Report Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="/admin/reports">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Report Type</label>
                            <select name="report_type" class="form-control">
                                <option value="overview" <?php echo ($_GET['report_type'] ?? '') === 'overview' ? 'selected' : ''; ?>>Overview</option>
                                <option value="members" <?php echo ($_GET['report_type'] ?? '') === 'members' ? 'selected' : ''; ?>>Members</option>
                                <option value="payments" <?php echo ($_GET['report_type'] ?? '') === 'payments' ? 'selected' : ''; ?>>Payments</option>
                                <option value="claims" <?php echo ($_GET['report_type'] ?? '') === 'claims' ? 'selected' : ''; ?>>Claims</option>
                                <option value="financial" <?php echo ($_GET['report_type'] ?? '') === 'financial' ? 'selected' : ''; ?>>Financial</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Date From</label>
                            <input type="date" name="date_from" class="form-control" value="<?php echo $_GET['date_from'] ?? date('Y-m-01'); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Date To</label>
                            <input type="date" name="date_to" class="form-control" value="<?php echo $_GET['date_to'] ?? date('Y-m-d'); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">Generate Report</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Key Metrics Overview -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Members
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $totalMembers ?? 0; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Revenue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                KES <?php echo number_format($totalRevenue ?? 0, 2); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Claims Paid
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                KES <?php echo number_format($totalClaimsPaid ?? 0, 2); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-medical fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Net Income
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                KES <?php echo number_format(($totalRevenue ?? 0) - ($totalClaimsPaid ?? 0), 2); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Revenue Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Revenue Trend</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Member Growth Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Member Growth</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="memberChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> Active
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Inactive
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-info"></i> Pending
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Reports Tables -->
    <?php if (($_GET['report_type'] ?? 'overview') === 'members'): ?>
    <!-- Member Report -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Member Registration Report</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
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
                        <?php if (!empty($memberReports)): ?>
                            <?php foreach ($memberReports as $member): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($member['member_number']); ?></td>
                                <td><?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?></td>
                                <td><?php echo ucfirst($member['package_type'] ?? 'individual'); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $member['status'] === 'active' ? 'success' : 'warning'; ?>">
                                        <?php echo ucfirst($member['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($member['created_at'])); ?></td>
                                <td><?php echo $member['last_payment'] ? date('M j, Y', strtotime($member['last_payment'])) : 'N/A'; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No member data available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php elseif (($_GET['report_type'] ?? 'overview') === 'payments'): ?>
    <!-- Payment Report -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Payment Transaction Report</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
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
                        <?php if (!empty($paymentReports)): ?>
                            <?php foreach ($paymentReports as $payment): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($payment['transaction_id']); ?></td>
                                <td><?php echo htmlspecialchars($payment['member_name']); ?></td>
                                <td>KES <?php echo number_format($payment['amount'], 2); ?></td>
                                <td><?php echo ucfirst($payment['payment_type']); ?></td>
                                <td><?php echo strtoupper($payment['payment_method']); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $payment['status'] === 'completed' ? 'success' : 'warning'; ?>">
                                        <?php echo ucfirst($payment['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($payment['created_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No payment data available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php elseif (($_GET['report_type'] ?? 'overview') === 'claims'): ?>
    <!-- Claims Report -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Claims Processing Report</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Claim ID</th>
                            <th>Member</th>
                            <th>Deceased Name</th>
                            <th>Claim Amount</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Processed</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($claimReports)): ?>
                            <?php foreach ($claimReports as $claim): ?>
                            <tr>
                                <td>#<?php echo str_pad($claim['id'], 4, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo htmlspecialchars($claim['member_name']); ?></td>
                                <td><?php echo htmlspecialchars($claim['deceased_name']); ?></td>
                                <td>KES <?php echo number_format($claim['claim_amount'], 2); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $claim['status'] === 'approved' ? 'success' : ($claim['status'] === 'rejected' ? 'danger' : 'warning'); ?>">
                                        <?php echo ucfirst($claim['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($claim['created_at'])); ?></td>
                                <td><?php echo $claim['processed_at'] ? date('M j, Y', strtotime($claim['processed_at'])) : 'N/A'; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No claims data available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php else: ?>
    <!-- Overview Report -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Member Summary</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <p><strong>Total Members:</strong> <?php echo $totalMembers ?? 0; ?></p>
                            <p><strong>Active Members:</strong> <?php echo $activeMembers ?? 0; ?></p>
                            <p><strong>Inactive Members:</strong> <?php echo $inactiveMembers ?? 0; ?></p>
                        </div>
                        <div class="col-sm-6">
                            <p><strong>New This Month:</strong> <?php echo $newMembersThisMonth ?? 0; ?></p>
                            <p><strong>Pending Applications:</strong> <?php echo $pendingMembers ?? 0; ?></p>
                            <p><strong>Renewal Due:</strong> <?php echo $renewalDue ?? 0; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Financial Summary</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <p><strong>Total Revenue:</strong> KES <?php echo number_format($totalRevenue ?? 0, 2); ?></p>
                            <p><strong>This Month:</strong> KES <?php echo number_format($monthlyRevenue ?? 0, 2); ?></p>
                            <p><strong>Claims Paid:</strong> KES <?php echo number_format($totalClaimsPaid ?? 0, 2); ?></p>
                        </div>
                        <div class="col-sm-6">
                            <p><strong>Net Income:</strong> KES <?php echo number_format(($totalRevenue ?? 0) - ($totalClaimsPaid ?? 0), 2); ?></p>
                            <p><strong>Pending Payments:</strong> <?php echo $pendingPayments ?? 0; ?></p>
                            <p><strong>Failed Payments:</strong> <?php echo $failedPayments ?? 0; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

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
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'KES ' + value.toLocaleString();
                    }
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
            backgroundColor: ['#1cc88a', '#36b9cc', '#f6c23e'],
            hoverBackgroundColor: ['#17a673', '#2c9faf', '#f4b619'],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
        }],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        }
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

<?php include_once 'admin-footer.php'; ?>
