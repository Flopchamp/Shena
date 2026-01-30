<?php
/**
 * Admin Service Delivery Tracking View
 * Track and manage service delivery for approved claims
 */
require_once __DIR__ . '/admin-header.php';
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">Service Delivery Tracking</h2>
                    <p class="text-muted mb-0">Claim #<?= e($claim->id) ?> - <?= e($claim->deceased_name) ?></p>
                </div>
                <a href="/admin/claims" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Claims
                </a>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <?= e($_SESSION['success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <?= e($_SESSION['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="row">
                <!-- Claim Information Card -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Claim Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th>Claim ID:</th>
                                    <td><?= e($claim->id) ?></td>
                                </tr>
                                <tr>
                                    <th>Member:</th>
                                    <td><?= e($claim->first_name . ' ' . $claim->last_name) ?></td>
                                </tr>
                                <tr>
                                    <th>Deceased:</th>
                                    <td><?= e($claim->deceased_name) ?></td>
                                </tr>
                                <tr>
                                    <th>Date of Death:</th>
                                    <td><?= formatDate($claim->date_of_death) ?></td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge bg-<?= $claim->status === 'approved' ? 'success' : 'info' ?>">
                                            <?= ucfirst(str_replace('_', ' ', e($claim->status))) ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Service Type:</th>
                                    <td>
                                        <?php if ($claim->service_delivery_type === 'cash_alternative'): ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-money-bill"></i> Cash Alternative
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-hands-helping"></i> Standard Services
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Delivery Date:</th>
                                    <td><?= $claim->services_delivery_date ? formatDate($claim->services_delivery_date) : 'Not set' ?></td>
                                </tr>
                                <tr>
                                    <th>Mortuary Days:</th>
                                    <td><?= e($claim->mortuary_days_count) ?> days (max 14)</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Completion Progress Card -->
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Overall Progress</h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="progress-circle mb-3" style="position: relative; width: 150px; height: 150px; margin: 0 auto;">
                                <svg width="150" height="150">
                                    <circle cx="75" cy="75" r="65" fill="none" stroke="#e9ecef" stroke-width="10"/>
                                    <circle cx="75" cy="75" r="65" fill="none" stroke="#0dcaf0" stroke-width="10"
                                            stroke-dasharray="<?= ($completion_percentage / 100) * 408.4 ?> 408.4"
                                            transform="rotate(-90 75 75)" stroke-linecap="round"/>
                                </svg>
                                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                    <h2 class="mb-0"><?= $completion_percentage ?>%</h2>
                                    <small class="text-muted">Complete</small>
                                </div>
                            </div>
                            
                            <?php if ($completion_percentage == 100): ?>
                                <div class="alert alert-success mt-3">
                                    <i class="fas fa-check-circle"></i> All services completed!
                                </div>
                                <form method="POST" action="/admin/claims/complete">
                                    <input type="hidden" name="claim_id" value="<?= $claim->id ?>">
                                    <button type="submit" class="btn btn-success btn-lg w-100" onclick="return confirm('Mark this claim as completed?')">
                                        <i class="fas fa-check-double"></i> Complete Claim
                                    </button>
                                </form>
                            <?php else: ?>
                                <p class="text-muted mb-0">
                                    <?= count(array_filter($checklist, fn($c) => $c['completed'])) ?> of <?= count($checklist) ?> services completed
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Service Checklist Card -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-tasks"></i> Service Delivery Checklist</h5>
                            <small>Per SHENA Companion Policy - Section 3</small>
                        </div>
                        <div class="card-body">
                            <?php if (empty($checklist)): ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> No service checklist found. This may be a cash alternative claim.
                                </div>
                            <?php else: ?>
                                <div class="list-group">
                                    <?php 
                                    $serviceIcons = [
                                        'mortuary_bill' => 'fas fa-hospital',
                                        'body_dressing' => 'fas fa-user-tie',
                                        'coffin' => 'fas fa-box',
                                        'transportation' => 'fas fa-truck',
                                        'equipment' => 'fas fa-tools'
                                    ];
                                    $serviceLabels = [
                                        'mortuary_bill' => 'Mortuary Bill Payment',
                                        'body_dressing' => 'Body Dressing',
                                        'coffin' => 'Executive Coffin Delivery',
                                        'transportation' => 'Transportation Arranged',
                                        'equipment' => 'Equipment Delivered (Lowering gear, trolley, gazebo, 100 chairs)'
                                    ];
                                    
                                    foreach ($checklist as $item): 
                                    ?>
                                        <div class="list-group-item <?= $item['completed'] ? 'list-group-item-success' : '' ?>">
                                            <div class="row align-items-center">
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            <?php if ($item['completed']): ?>
                                                                <i class="fas fa-check-circle text-success fa-2x"></i>
                                                            <?php else: ?>
                                                                <i class="far fa-circle text-muted fa-2x"></i>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1">
                                                                <i class="<?= $serviceIcons[$item['service_type']] ?>"></i>
                                                                <?= $serviceLabels[$item['service_type']] ?>
                                                            </h6>
                                                            <?php if ($item['completed']): ?>
                                                                <small class="text-muted">
                                                                    Completed by <?= e($item['first_name'] . ' ' . $item['last_name']) ?>
                                                                    on <?= formatDateTime($item['completed_at']) ?>
                                                                </small>
                                                            <?php else: ?>
                                                                <small class="text-muted">Pending completion</small>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 text-end">
                                                    <?php if (!$item['completed']): ?>
                                                        <button type="button" class="btn btn-sm btn-primary" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#completeServiceModal"
                                                                data-service-type="<?= e($item['service_type']) ?>"
                                                                data-service-label="<?= e($serviceLabels[$item['service_type']]) ?>">
                                                            <i class="fas fa-check"></i> Mark Completed
                                                        </button>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">Completed</span>
                                                        <?php if ($item['service_notes']): ?>
                                                            <button type="button" class="btn btn-sm btn-info" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#viewNotesModal"
                                                                    data-notes="<?= e($item['service_notes']) ?>"
                                                                    data-service-label="<?= e($serviceLabels[$item['service_type']]) ?>">
                                                                <i class="fas fa-eye"></i> View Notes
                                                            </button>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Complete Service Modal -->
<div class="modal fade" id="completeServiceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/admin/claims/track/<?= $claim->id ?>">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-check-circle"></i> Complete Service</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="service_type" id="modal_service_type">
                    <input type="hidden" name="completed" value="1">
                    
                    <div class="alert alert-info">
                        <strong id="modal_service_label"></strong> will be marked as completed.
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Service Notes (Optional)</label>
                        <textarea name="service_notes" class="form-control" rows="3" 
                                  placeholder="Add any notes about this service delivery..."></textarea>
                        <small class="text-muted">e.g., Invoice number, delivery details, special arrangements</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i> Mark as Completed
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Notes Modal -->
<div class="modal fade" id="viewNotesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fas fa-sticky-note"></i> Service Notes</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6 id="notes_service_label"></h6>
                <div class="alert alert-light" id="notes_content"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Handle Complete Service Modal
document.getElementById('completeServiceModal').addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const serviceType = button.getAttribute('data-service-type');
    const serviceLabel = button.getAttribute('data-service-label');
    
    document.getElementById('modal_service_type').value = serviceType;
    document.getElementById('modal_service_label').textContent = serviceLabel;
});

// Handle View Notes Modal
document.getElementById('viewNotesModal').addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const notes = button.getAttribute('data-notes');
    const serviceLabel = button.getAttribute('data-service-label');
    
    document.getElementById('notes_service_label').textContent = serviceLabel;
    document.getElementById('notes_content').textContent = notes;
});
</script>

<?php require_once __DIR__ . '/admin-footer.php'; ?>
