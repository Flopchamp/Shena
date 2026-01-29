<?php include __DIR__ . '/../layouts/admin-header.php'; ?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Agent Management</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="/admin/agents/create" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Register New Agent
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="alert alert-<?= $_SESSION['flash_type'] ?? 'info' ?> alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['flash_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
    <?php endif; ?>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="/admin/agents" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="suspended" <?= ($filters['status'] ?? '') === 'suspended' ? 'selected' : '' ?>>Suspended</option>
                        <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search by name, agent number, or phone" 
                           value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Agents Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Agent Number</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Total Members</th>
                            <th>Pending Commission</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($agents)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <p class="text-muted">No agents found</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($agents as $agent): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($agent['agent_number']) ?></strong>
                                    </td>
                                    <td><?= htmlspecialchars($agent['first_name'] . ' ' . $agent['last_name']) ?></td>
                                    <td><?= htmlspecialchars($agent['phone']) ?></td>
                                    <td><?= htmlspecialchars($agent['email']) ?></td>
                                    <td>
                                        <span class="badge bg-info"><?= $agent['total_members'] ?? 0 ?></span>
                                    </td>
                                    <td>KES <?= number_format($agent['pending_commission'] ?? 0, 2) ?></td>
                                    <td>
                                        <?php
                                        $statusClass = [
                                            'active' => 'success',
                                            'suspended' => 'warning',
                                            'inactive' => 'secondary'
                                        ][$agent['status']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?= $statusClass ?>">
                                            <?= ucfirst($agent['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="/admin/agents/view/<?= $agent['id'] ?>" 
                                           class="btn btn-sm btn-info" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/admin/agents/edit/<?= $agent['id'] ?>" 
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
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

<?php include __DIR__ . '/../layouts/admin-footer.php'; ?>
