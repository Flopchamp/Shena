<?php include 'admin-header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/dashboard">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/members">
                            <i class="fas fa-users"></i> Members
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/payments">
                            <i class="fas fa-money-bill-wave"></i> Payments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/claims">
                            <i class="fas fa-file-medical"></i> Claims
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/agents">
                            <i class="fas fa-user-tie"></i> Agents
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/communications">
                            <i class="fas fa-envelope"></i> Communications
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/plan-upgrades">
                            <i class="fas fa-arrow-up"></i> Plan Upgrades
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/admin/financial-dashboard">
                            <i class="fas fa-chart-line"></i> Financial Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/mpesa-config">
                            <i class="fas fa-cog"></i> M-Pesa Configuration
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Financial Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group mr-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="exportReport()">
                            <i class="fas fa-download"></i> Export Report
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                </div>
            </div>

            <!-- Date Range Filter -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="/admin/financial-dashboard" class="form-inline">
                        <div class="form-group mr-2 mb-2">
                            <label for="from_date" class="mr-2">From:</label>
                            <input type="date" class="form-control" id="from_date" name="from_date" 
                                   value="<?php echo htmlspecialchars($_GET['from_date'] ?? date('Y-m-01')); ?>">
                        </div>
                        <div class="form-group mr-2 mb-2">
                            <label for="to_date" class="mr-2">To:</label>
                            <input type="date" class="form-control" id="to_date" name="to_date" 
                                   value="<?php echo htmlspecialchars($_GET['to_date'] ?? date('Y-m-d')); ?>">
                        </div>
                        <button type="submit" class="btn btn-primary mb-2">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <button type="button" class="btn btn-secondary mb-2 ml-2" onclick="resetFilters()">
                            <i class="fas fa-redo"></i> This Month
                        </button>
                    </form>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Total Revenue</h6>
                                    <h3 class="mb-0">KES <?php echo number_format($data['kpis']['total_revenue'] ?? 0, 0); ?></h3>
                                    <small>
                                        <?php 
                                        $change = $data['kpis']['revenue_change'] ?? 0;
                                        $icon = $change >= 0 ? 'arrow-up' : 'arrow-down';
                                        $color = $change >= 0 ? 'success' : 'danger';
                                        ?>
                                        <i class="fas fa-<?php echo $icon; ?> text-<?php echo $color; ?>"></i>
                                        <?php echo abs($change); ?>% vs last period
                                    </small>
                                </div>
                                <i class="fas fa-money-bill-wave fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Payments</h6>
                                    <h3 class="mb-0">KES <?php echo number_format($data['kpis']['total_payments'] ?? 0, 0); ?></h3>
                                    <small><?php echo $data['kpis']['paying_members'] ?? 0; ?> paying members</small>
                                </div>
                                <i class="fas fa-credit-card fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Commissions</h6>
                                    <h3 class="mb-0">KES <?php echo number_format($data['kpis']['total_commissions'] ?? 0, 0); ?></h3>
                                    <small><?php echo $data['kpis']['earning_agents'] ?? 0; ?> agents paid</small>
                                </div>
                                <i class="fas fa-percentage fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Net Revenue</h6>
                                    <h3 class="mb-0">KES <?php echo number_format($data['kpis']['net_revenue'] ?? 0, 0); ?></h3>
                                    <small>
                                        <?php 
                                        $margin = $data['kpis']['revenue'] > 0 ? 
                                            round(($data['kpis']['net_revenue'] / $data['kpis']['total_revenue']) * 100, 1) : 0;
                                        echo $margin; 
                                        ?>% margin
                                    </small>
                                </div>
                                <i class="fas fa-chart-line fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Monthly Revenue Trend</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="revenueChart" height="80"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Transaction Breakdown</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="transactionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tables Row -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Top Performing Agents</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>Agent</th>
                                            <th class="text-right">Members</th>
                                            <th class="text-right">Commission</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($data['top_agents'])): ?>
                                            <?php foreach ($data['top_agents'] as $agent): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($agent['agent_name']); ?></td>
                                                    <td class="text-right"><?php echo $agent['total_members']; ?></td>
                                                    <td class="text-right">KES <?php echo number_format($agent['total_commissions'], 0); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-3">No data available</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Recent Transactions</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Member</th>
                                            <th class="text-right">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($data['recent_transactions'])): ?>
                                            <?php foreach ($data['recent_transactions'] as $txn): ?>
                                                <tr>
                                                    <td><small><?php echo date('M d', strtotime($txn['transaction_date'])); ?></small></td>
                                                    <td>
                                                        <span class="badge badge-<?php 
                                                            echo $txn['transaction_type'] === 'payment' ? 'success' : 
                                                                 ($txn['transaction_type'] === 'commission' ? 'warning' : 'info'); 
                                                        ?>">
                                                            <?php echo ucfirst($txn['transaction_type']); ?>
                                                        </span>
                                                    </td>
                                                    <td><small><?php echo htmlspecialchars($txn['member_name'] ?? 'N/A'); ?></small></td>
                                                    <td class="text-right">KES <?php echo number_format($txn['amount'], 0); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-3">No transactions found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Summary Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Monthly Financial Summary</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th class="text-right">Payments</th>
                                    <th class="text-right">Commissions</th>
                                    <th class="text-right">Upgrades</th>
                                    <th class="text-right">Refunds</th>
                                    <th class="text-right">Net Revenue</th>
                                    <th class="text-right">Members</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['monthly_summary'])): ?>
                                    <?php foreach ($data['monthly_summary'] as $month): ?>
                                        <tr>
                                            <td><strong><?php echo date('F Y', strtotime($month['month'] . '-01')); ?></strong></td>
                                            <td class="text-right text-success">
                                                KES <?php echo number_format($month['total_payments'], 0); ?>
                                            </td>
                                            <td class="text-right text-warning">
                                                KES <?php echo number_format($month['total_commissions'], 0); ?>
                                            </td>
                                            <td class="text-right text-info">
                                                KES <?php echo number_format($month['total_upgrades'], 0); ?>
                                            </td>
                                            <td class="text-right text-danger">
                                                KES <?php echo number_format($month['total_refunds'], 0); ?>
                                            </td>
                                            <td class="text-right">
                                                <strong>KES <?php 
                                                    $net = $month['total_payments'] + $month['total_upgrades'] - 
                                                           $month['total_commissions'] - $month['total_refunds'];
                                                    echo number_format($net, 0); 
                                                ?></strong>
                                            </td>
                                            <td class="text-right"><?php echo $month['paying_members']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            No financial data available for the selected period
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
// Revenue Trend Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_column($data['monthly_summary'] ?? [], 'month')); ?>,
        datasets: [{
            label: 'Revenue',
            data: <?php echo json_encode(array_column($data['monthly_summary'] ?? [], 'total_payments')); ?>,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.4
        }, {
            label: 'Commissions',
            data: <?php echo json_encode(array_column($data['monthly_summary'] ?? [], 'total_commissions')); ?>,
            borderColor: 'rgb(255, 159, 64)',
            backgroundColor: 'rgba(255, 159, 64, 0.2)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
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
                }
            }
        }
    }
});

// Transaction Breakdown Chart
const transactionCtx = document.getElementById('transactionChart').getContext('2d');
const transactionChart = new Chart(transactionCtx, {
    type: 'doughnut',
    data: {
        labels: ['Payments', 'Commissions', 'Upgrades', 'Refunds'],
        datasets: [{
            data: [
                <?php echo $data['kpis']['total_payments'] ?? 0; ?>,
                <?php echo $data['kpis']['total_commissions'] ?? 0; ?>,
                <?php echo $data['kpis']['total_upgrades'] ?? 0; ?>,
                <?php echo $data['kpis']['total_refunds'] ?? 0; ?>
            ],
            backgroundColor: [
                'rgba(75, 192, 192, 0.8)',
                'rgba(255, 159, 64, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 99, 132, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

function resetFilters() {
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    const fromDate = firstDay.toISOString().split('T')[0];
    const toDate = today.toISOString().split('T')[0];
    
    window.location.href = `/admin/financial-dashboard?from_date=${fromDate}&to_date=${toDate}`;
}

function exportReport() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = '/admin/financial-dashboard/export?' + params.toString();
}
</script>

<?php include 'admin-footer.php'; ?>
