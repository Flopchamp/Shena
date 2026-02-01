<?php include_once 'admin-header.php'; ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-tie mr-2"></i>Agent Management
        </h1>
        <a href="/admin/agents/create" class="btn btn-primary shadow-sm">
            <i class="fas fa-user-plus mr-2"></i>Register New Agent
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Total Agents -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Agents
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count($agents ?? []) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Agents -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Agents
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count(array_filter($agents ?? [], fn($a) => $a['status'] === 'active')) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Members Recruited -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Members
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= array_sum(array_column($agents ?? [], 'total_members')) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Commissions -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Commission
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                KES <?= number_format(array_sum(array_column($agents ?? [], 'pending_commission')), 2) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter mr-2"></i>Filter Agents
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="/admin/agents" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label font-weight-bold">Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="suspended" <?= ($filters['status'] ?? '') === 'suspended' ? 'selected' : '' ?>>Suspended</option>
                        <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label font-weight-bold">Search</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search by name, agent number, email, or phone" 
                           value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-search mr-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Agents Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list mr-2"></i>Agents List
            </h6>
            <small class="text-muted"><?= count($agents ?? []) ?> agent(s) found</small>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="agentsTable">
                    <thead class="thead-light">
                        <tr>
                            <th><i class="fas fa-hashtag mr-1"></i>Agent Number</th>
                            <th><i class="fas fa-user mr-1"></i>Name</th>
                            <th><i class="fas fa-phone mr-1"></i>Phone</th>
                            <th><i class="fas fa-envelope mr-1"></i>Email</th>
                            <th><i class="fas fa-users mr-1"></i>Members</th>
                            <th><i class="fas fa-money-bill mr-1"></i>Pending Commission</th>
                            <th><i class="fas fa-toggle-on mr-1"></i>Status</th>
                            <th><i class="fas fa-cogs mr-1"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($agents)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-user-slash fa-3x text-gray-300 mb-3"></i>
                                    <p class="text-muted mb-0">No agents found</p>
                                    <a href="/admin/agents/create" class="btn btn-sm btn-primary mt-2">
                                        <i class="fas fa-plus mr-1"></i>Register First Agent
                                    </a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($agents as $agent): ?>
                                <tr>
                                    <td>
                                        <strong class="text-primary"><?= htmlspecialchars($agent['agent_number']) ?></strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="mr-2">
                                                <i class="fas fa-user-circle fa-lg text-gray-400"></i>
                                            </div>
                                            <div>
                                                <?= htmlspecialchars($agent['first_name'] . ' ' . $agent['last_name']) ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="tel:<?= htmlspecialchars($agent['phone']) ?>" class="text-decoration-none">
                                            <?= htmlspecialchars($agent['phone']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="mailto:<?= htmlspecialchars($agent['email']) ?>" class="text-decoration-none">
                                            <?= htmlspecialchars($agent['email']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge badge-info badge-pill">
                                            <?= $agent['total_members'] ?? 0 ?> member(s)
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="text-success">KES <?= number_format($agent['pending_commission'] ?? 0, 2) ?></strong>
                                    </td>
                                    <td>
                                        <?php
                                        $statusConfig = [
                                            'active' => ['class' => 'success', 'icon' => 'check-circle'],
                                            'suspended' => ['class' => 'warning', 'icon' => 'pause-circle'],
                                            'inactive' => ['class' => 'secondary', 'icon' => 'times-circle']
                                        ];
                                        $config = $statusConfig[$agent['status']] ?? ['class' => 'secondary', 'icon' => 'question-circle'];
                                        ?>
                                        <span class="badge badge-<?= $config['class'] ?>">
                                            <i class="fas fa-<?= $config['icon'] ?> mr-1"></i><?= ucfirst($agent['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/admin/agents/view/<?= $agent['id'] ?>" 
                                               class="btn btn-sm btn-info" 
                                               title="View Details"
                                               data-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="/admin/agents/edit/<?= $agent['id'] ?>" 
                                               class="btn btn-sm btn-warning" 
                                               title="Edit Agent"
                                               data-toggle="tooltip">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ($agent['status'] === 'active'): ?>
                                                <form method="POST" action="/admin/agents/status/<?= $agent['id'] ?>" style="display: inline;">
                                                    <input type="hidden" name="status" value="suspended">
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-secondary" 
                                                            title="Suspend Agent"
                                                            data-toggle="tooltip"
                                                            onclick="return confirm('Suspend this agent?')">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                </form>
                                            <?php elseif ($agent['status'] === 'suspended'): ?>
                                                <form method="POST" action="/admin/agents/status/<?= $agent['id'] ?>" style="display: inline;">
                                                    <input type="hidden" name="status" value="active">
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-success" 
                                                            title="Activate Agent"
                                                            data-toggle="tooltip"
                                                            onclick="return confirm('Activate this agent?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Initialize DataTable if jQuery DataTables is available
    if ($.fn.DataTable) {
        $('#agentsTable').DataTable({
            "pageLength": 25,
            "order": [[0, "desc"]],
            "language": {
                "search": "Search agents:",
                "lengthMenu": "Show _MENU_ agents per page"
            }
        });
    }
});
</script>

<?php include_once 'admin-footer.php'; ?>
