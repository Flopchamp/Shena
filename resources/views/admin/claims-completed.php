<?php
/**
 * Admin View: Completed Claims with Service Delivery Summary
 */
require_once __DIR__ . '/admin-header.php';
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">Completed Claims</h2>
                    <p class="text-muted mb-0">Claims where all services have been delivered</p>
                </div>
                <a href="/admin/claims" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to All Claims
                </a>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <?= e($_SESSION['success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <!-- Completed Claims Table -->
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-check-double"></i> Completed Claims Summary</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($claims)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Claim ID</th>
                                        <th>Member</th>
                                        <th>Deceased</th>
                                        <th>Service Type</th>
                                        <th>Submitted Date</th>
                                        <th>Completed Date</th>
                                        <th>Duration</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($claims as $claim): ?>
                                        <?php
                                        $submitted = new DateTime($claim['created_at']);
                                        $completed = new DateTime($claim['completed_at'] ?? $claim['updated_at']);
                                        $duration = $submitted->diff($completed);
                                        ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-success">#<?= str_pad($claim['id'], 4, '0', STR_PAD_LEFT) ?></span>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong><?= e($claim['first_name'] . ' ' . $claim['last_name']) ?></strong><br>
                                                    <small class="text-muted"><?= e($claim['member_number']) ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong><?= e($claim['deceased_name']) ?></strong><br>
                                                    <small class="text-muted"><?= ucfirst($claim['relationship_to_deceased']) ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if ($claim['service_delivery_type'] === 'cash_alternative'): ?>
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-money-bill"></i> Cash KES 20,000
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-hands-helping"></i> Full Services
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= formatDate($claim['created_at']) ?></td>
                                            <td><?= formatDate($claim['completed_at'] ?? $claim['updated_at']) ?></td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?= $duration->days ?> day<?= $duration->days != 1 ? 's' : '' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-info" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#viewClaimModal<?= $claim['id'] ?>">
                                                    <i class="fas fa-eye"></i> View Details
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Claim Details Modal -->
                                        <div class="modal fade" id="viewClaimModal<?= $claim['id'] ?>" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-success text-white">
                                                        <h5 class="modal-title">
                                                            <i class="fas fa-check-circle"></i> Completed Claim Details
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <h6 class="font-weight-bold">Claim Information</h6>
                                                                <table class="table table-sm table-borderless">
                                                                    <tr>
                                                                        <th>Claim ID:</th>
                                                                        <td>#<?= str_pad($claim['id'], 4, '0', STR_PAD_LEFT) ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Status:</th>
                                                                        <td><span class="badge bg-success">Completed</span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Submitted:</th>
                                                                        <td><?= formatDateTime($claim['created_at']) ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Completed:</th>
                                                                        <td><?= formatDateTime($claim['completed_at'] ?? $claim['updated_at']) ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Processing Time:</th>
                                                                        <td><?= $duration->days ?> days</td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h6 class="font-weight-bold">Deceased Information</h6>
                                                                <table class="table table-sm table-borderless">
                                                                    <tr>
                                                                        <th>Name:</th>
                                                                        <td><?= e($claim['deceased_name']) ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Relationship:</th>
                                                                        <td><?= ucfirst($claim['relationship_to_deceased']) ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Date of Death:</th>
                                                                        <td><?= formatDate($claim['date_of_death']) ?></td>
                                                                    </tr>
                                                                    <?php if ($claim['place_of_death']): ?>
                                                                    <tr>
                                                                        <th>Place of Death:</th>
                                                                        <td><?= e($claim['place_of_death']) ?></td>
                                                                    </tr>
                                                                    <?php endif; ?>
                                                                </table>
                                                            </div>
                                                        </div>

                                                        <?php if ($claim['service_delivery_type'] === 'cash_alternative'): ?>
                                                            <!-- Cash Alternative Details -->
                                                            <div class="alert alert-warning">
                                                                <h6 class="alert-heading"><i class="fas fa-money-bill"></i> Cash Alternative Delivered</h6>
                                                                <p><strong>Amount:</strong> KES 20,000</p>
                                                                <?php if ($claim['cash_alternative_reason']): ?>
                                                                    <p><strong>Reason:</strong> <?= e($claim['cash_alternative_reason']) ?></p>
                                                                <?php endif; ?>
                                                                <?php if ($claim['cash_alternative_payment_date']): ?>
                                                                    <p><strong>Payment Date:</strong> <?= formatDate($claim['cash_alternative_payment_date']) ?></p>
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php else: ?>
                                                            <!-- Service Checklist -->
                                                            <h6 class="font-weight-bold">Services Delivered:</h6>
                                                            <div class="list-group mb-3">
                                                                <div class="list-group-item <?= $claim['mortuary_bill_settled'] ? 'list-group-item-success' : '' ?>">
                                                                    <i class="fas fa-check-circle text-success"></i> Mortuary Bill Payment
                                                                    (<?= $claim['mortuary_days_count'] ?? 14 ?> days)
                                                                </div>
                                                                <div class="list-group-item <?= $claim['body_dressing_completed'] ? 'list-group-item-success' : '' ?>">
                                                                    <i class="fas fa-check-circle text-success"></i> Body Dressing
                                                                </div>
                                                                <div class="list-group-item <?= $claim['coffin_delivered'] ? 'list-group-item-success' : '' ?>">
                                                                    <i class="fas fa-check-circle text-success"></i> Executive Coffin
                                                                </div>
                                                                <div class="list-group-item <?= $claim['transportation_arranged'] ? 'list-group-item-success' : '' ?>">
                                                                    <i class="fas fa-check-circle text-success"></i> Transportation
                                                                </div>
                                                                <div class="list-group-item <?= $claim['equipment_delivered'] ? 'list-group-item-success' : '' ?>">
                                                                    <i class="fas fa-check-circle text-success"></i> Equipment (Lowering gear, trolley, gazebo, 100 chairs)
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>

                                                        <?php if ($claim['admin_notes']): ?>
                                                            <div class="alert alert-info">
                                                                <h6 class="alert-heading">Admin Notes</h6>
                                                                <p class="mb-0"><?= nl2br(e($claim['admin_notes'])) ?></p>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No completed claims yet</h5>
                            <p class="text-muted">Completed claims will appear here once all services are delivered.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/admin-footer.php'; ?>
