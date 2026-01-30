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
                        <a class="nav-link active" href="/admin/plan-upgrades">
                            <i class="fas fa-arrow-up"></i> Plan Upgrades
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/financial-dashboard">
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
                <h1 class="h2">Plan Upgrade Management</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group mr-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="exportToCSV()">
                            <i class="fas fa-download"></i> Export CSV
                        </button>
                    </div>
                </div>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Pending</h6>
                                    <h2 class="mb-0"><?php echo $data['stats']['pending'] ?? 0; ?></h2>
                                </div>
                                <i class="fas fa-clock fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Completed</h6>
                                    <h2 class="mb-0"><?php echo $data['stats']['completed'] ?? 0; ?></h2>
                                </div>
                                <i class="fas fa-check-circle fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-danger">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Cancelled</h6>
                                    <h2 class="mb-0"><?php echo $data['stats']['cancelled'] ?? 0; ?></h2>
                                </div>
                                <i class="fas fa-times-circle fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Total Revenue</h6>
                                    <h2 class="mb-0">KES <?php echo number_format($data['stats']['total_revenue'] ?? 0, 0); ?></h2>
                                </div>
                                <i class="fas fa-money-bill-wave fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="/admin/plan-upgrades" class="form-inline">
                        <div class="form-group mr-2 mb-2">
                            <label for="status" class="mr-2">Status:</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">All</option>
                                <option value="pending" <?php echo ($_GET['status'] ?? '') === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="completed" <?php echo ($_GET['status'] ?? '') === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                <option value="cancelled" <?php echo ($_GET['status'] ?? '') === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </div>
                        <div class="form-group mr-2 mb-2">
                            <label for="from_date" class="mr-2">From:</label>
                            <input type="date" class="form-control" id="from_date" name="from_date" 
                                   value="<?php echo htmlspecialchars($_GET['from_date'] ?? ''); ?>">
                        </div>
                        <div class="form-group mr-2 mb-2">
                            <label for="to_date" class="mr-2">To:</label>
                            <input type="date" class="form-control" id="to_date" name="to_date" 
                                   value="<?php echo htmlspecialchars($_GET['to_date'] ?? ''); ?>">
                        </div>
                        <button type="submit" class="btn btn-primary mb-2">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="/admin/plan-upgrades" class="btn btn-secondary mb-2 ml-2">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </form>
                </div>
            </div>

            <!-- Upgrades Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Upgrade Requests</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Member</th>
                                    <th>From Package</th>
                                    <th>To Package</th>
                                    <th>Amount</th>
                                    <th>Payment Status</th>
                                    <th>Status</th>
                                    <th>Requested</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['upgrades'])): ?>
                                    <?php foreach ($data['upgrades'] as $upgrade): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($upgrade['id']); ?></td>
                                            <td>
                                                <a href="/admin/member/<?php echo $upgrade['member_id']; ?>">
                                                    <?php echo htmlspecialchars($upgrade['member_name']); ?>
                                                </a><br>
                                                <small class="text-muted"><?php echo htmlspecialchars($upgrade['membership_number']); ?></small>
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary">
                                                    <?php echo htmlspecialchars($upgrade['from_package']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-primary">
                                                    <?php echo htmlspecialchars($upgrade['to_package']); ?>
                                                </span>
                                            </td>
                                            <td>KES <?php echo number_format($upgrade['prorated_amount'], 2); ?></td>
                                            <td>
                                                <span class="badge badge-<?php 
                                                    echo $upgrade['payment_status'] === 'completed' ? 'success' : 
                                                         ($upgrade['payment_status'] === 'pending' ? 'warning' : 'danger'); 
                                                ?>">
                                                    <?php echo ucfirst($upgrade['payment_status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?php 
                                                    echo $upgrade['status'] === 'completed' ? 'success' : 
                                                         ($upgrade['status'] === 'pending' ? 'warning' : 'danger'); 
                                                ?>">
                                                    <?php echo ucfirst($upgrade['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <small><?php echo date('M d, Y', strtotime($upgrade['requested_at'])); ?></small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <?php if ($upgrade['status'] === 'pending' && $upgrade['payment_status'] === 'completed'): ?>
                                                        <button class="btn btn-success" 
                                                                onclick="processUpgrade(<?php echo $upgrade['id']; ?>, 'complete')"
                                                                title="Complete Upgrade">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button class="btn btn-danger" 
                                                                onclick="processUpgrade(<?php echo $upgrade['id']; ?>, 'cancel')"
                                                                title="Cancel & Refund">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <a href="/admin/plan-upgrades/view/<?php echo $upgrade['id']; ?>" 
                                                       class="btn btn-info" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                            No upgrade requests found
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

<script>
function processUpgrade(id, action) {
    const actionText = action === 'complete' ? 'complete' : 'cancel and refund';
    const confirmMsg = `Are you sure you want to ${actionText} this upgrade request?`;
    
    if (confirm(confirmMsg)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/plan-upgrades/${action}/${id}`;
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = 'csrf_token';
        csrfInput.value = '<?php echo $_SESSION['csrf_token'] ?? ''; ?>';
        
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function exportToCSV() {
    window.location.href = '/admin/plan-upgrades/export?<?php echo http_build_query($_GET); ?>';
}
</script>

<?php include 'admin-footer.php'; ?>
