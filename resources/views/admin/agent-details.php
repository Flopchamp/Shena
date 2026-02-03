<?php include_once __DIR__ . '/../layouts/admin-header.php'; ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-tie mr-2"></i>Agent Details - <?= htmlspecialchars($agent['agent_number']) ?>
        </h1>
        <div>
            <a href="/admin/agents" class="btn btn-secondary shadow-sm mr-2">
                <i class="fas fa-arrow-left mr-2"></i>Back to Agents
            </a>
            <a href="/admin/agents/edit/<?= $agent['id'] ?>" class="btn btn-warning shadow-sm">
                <i class="fas fa-edit mr-2"></i>Edit Agent
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Total Members -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Members Recruited
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $stats['total_members'] ?? 0 ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Commission Earned -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Commission
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                KES <?= number_format($stats['paid_commission'] ?? 0, 2) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Commission -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Commission
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                KES <?= number_format($stats['pending_commission'] ?? 0, 2) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Members -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Active Members
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $stats['active_members'] ?? 0 ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Agent Information Card -->
        <div class="col-xl-4 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-2"></i>Agent Information
                    </h6>
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
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-user-circle fa-5x text-gray-400"></i>
                        <h5 class="mt-3 mb-1"><?= htmlspecialchars($agent['first_name'] . ' ' . $agent['last_name']) ?></h5>
                        <p class="text-muted mb-0">Agent #<?= htmlspecialchars($agent['agent_number']) ?></p>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-2">
                        <small class="text-gray-600"><i class="fas fa-id-card mr-2"></i>National ID:</small>
                        <div class="font-weight-bold"><?= htmlspecialchars($agent['national_id']) ?></div>
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-gray-600"><i class="fas fa-phone mr-2"></i>Phone:</small>
                        <div class="font-weight-bold">
                            <a href="tel:<?= htmlspecialchars($agent['phone']) ?>"><?= htmlspecialchars($agent['phone']) ?></a>
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-gray-600"><i class="fas fa-envelope mr-2"></i>Email:</small>
                        <div class="font-weight-bold">
                            <a href="mailto:<?= htmlspecialchars($agent['email']) ?>"><?= htmlspecialchars($agent['email']) ?></a>
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-gray-600"><i class="fas fa-map-marker-alt mr-2"></i>County:</small>
                        <div class="font-weight-bold"><?= htmlspecialchars($agent['county'] ?? 'N/A') ?></div>
                    </div>
                    
                    <?php if (!empty($agent['address'])): ?>
                    <div class="mb-2">
                        <small class="text-gray-600"><i class="fas fa-home mr-2"></i>Address:</small>
                        <div class="font-weight-bold"><?= htmlspecialchars($agent['address']) ?></div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mb-2">
                        <small class="text-gray-600"><i class="fas fa-percentage mr-2"></i>Commission Rate:</small>
                        <div class="font-weight-bold"><?= htmlspecialchars($agent['commission_rate']) ?>%</div>
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-gray-600"><i class="fas fa-calendar-alt mr-2"></i>Registered:</small>
                        <div class="font-weight-bold"><?= date('M j, Y', strtotime($agent['registration_date'])) ?></div>
                    </div>
                    
                    <hr>
                    
                    <!-- Action Buttons -->
                    <div class="mt-3">
                        <?php if ($agent['status'] === 'active'): ?>
                            <form method="POST" action="/admin/agents/status/<?= $agent['id'] ?>" class="d-inline">
                                <input type="hidden" name="status" value="suspended">
                                <button type="submit" class="btn btn-warning btn-block mb-2" onclick="return confirm('Suspend this agent?')">
                                    <i class="fas fa-ban mr-2"></i>Suspend Agent
                                </button>
                            </form>
                        <?php elseif ($agent['status'] === 'suspended'): ?>
                            <form method="POST" action="/admin/agents/status/<?= $agent['id'] ?>" class="d-inline">
                                <input type="hidden" name="status" value="active">
                                <button type="submit" class="btn btn-success btn-block mb-2" onclick="return confirm('Activate this agent?')">
                                    <i class="fas fa-check mr-2"></i>Activate Agent
                                </button>
                            </form>
                        <?php endif; ?>
                        
                        <a href="/admin/agents/edit/<?= $agent['id'] ?>" class="btn btn-primary btn-block">
                            <i class="fas fa-edit mr-2"></i>Edit Details
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bank Details Card -->
            <?php if (!empty($agent['bank_account']) || !empty($agent['bank_name'])): ?>
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-university mr-2"></i>Bank Details
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($agent['bank_name'])): ?>
                    <div class="mb-2">
                        <small class="text-gray-600">Bank Name:</small>
                        <div class="font-weight-bold"><?= htmlspecialchars($agent['bank_name']) ?></div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($agent['bank_account'])): ?>
                    <div class="mb-2">
                        <small class="text-gray-600">Account Number:</small>
                        <div class="font-weight-bold"><?= htmlspecialchars($agent['bank_account']) ?></div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($agent['bank_branch'])): ?>
                    <div class="mb-2">
                        <small class="text-gray-600">Branch:</small>
                        <div class="font-weight-bold"><?= htmlspecialchars($agent['bank_branch']) ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Right Column - Commissions and Members -->
        <div class="col-xl-8 mb-4">
            <!-- Recent Commissions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-money-bill-wave mr-2"></i>Commission History
                    </h6>
                    <a href="/admin/commissions?agent_id=<?= $agent['id'] ?>" class="btn btn-sm btn-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Member</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Commission</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($commissions)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-inbox fa-2x text-gray-300 mb-2"></i>
                                            <p class="text-muted mb-0">No commission records yet</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach (array_slice($commissions, 0, 10) as $commission): ?>
                                        <tr>
                                            <td><?= date('M j, Y', strtotime($commission['created_at'])) ?></td>
                                            <td>
                                                <a href="/admin/members/view/<?= $commission['member_id'] ?>">
                                                    <?= htmlspecialchars($commission['member_number'] ?? 'N/A') ?>
                                                </a>
                                            </td>
                                            <td><?= ucfirst(str_replace('_', ' ', $commission['commission_type'])) ?></td>
                                            <td>KES <?= number_format($commission['amount'], 2) ?></td>
                                            <td class="text-success">
                                                <strong>KES <?= number_format($commission['commission_amount'], 2) ?></strong>
                                            </td>
                                            <td>
                                                <?php
                                                $statusClass = [
                                                    'pending' => 'warning',
                                                    'approved' => 'info',
                                                    'paid' => 'success',
                                                    'rejected' => 'danger'
                                                ][$commission['status']] ?? 'secondary';
                                                ?>
                                                <span class="badge badge-<?= $statusClass ?>">
                                                    <?= ucfirst($commission['status']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Members -->
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users mr-2"></i>Recent Members Recruited
                    </h6>
                    <a href="/admin/members?agent_id=<?= $agent['id'] ?>" class="btn btn-sm btn-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Member #</th>
                                    <th>Name</th>
                                    <th>Package</th>
                                    <th>Status</th>
                                    <th>Registered</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($stats['recent_members'])): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="fas fa-user-slash fa-2x text-gray-300 mb-2"></i>
                                            <p class="text-muted mb-0">No members recruited yet</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($stats['recent_members'] as $member): ?>
                                        <tr>
                                            <td>
                                                <a href="/admin/members/view/<?= $member['id'] ?>">
                                                    <?= htmlspecialchars($member['member_number']) ?>
                                                </a>
                                            </td>
                                            <td><?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?></td>
                                            <td>
                                                <span class="badge badge-info">
                                                    <?= ucfirst($member['package']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                $statusClass = [
                                                    'active' => 'success',
                                                    'inactive' => 'secondary',
                                                    'grace_period' => 'warning',
                                                    'defaulted' => 'danger'
                                                ][$member['status']] ?? 'secondary';
                                                ?>
                                                <span class="badge badge-<?= $statusClass ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $member['status'])) ?>
                                                </span>
                                            </td>
                                            <td><?= date('M j, Y', strtotime($member['registration_date'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/admin-footer.php'; ?>
