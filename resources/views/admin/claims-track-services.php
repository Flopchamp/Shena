<?php
/**
 * Admin Service Delivery Tracking View
 * Track and manage service delivery for approved claims
 */
require_once __DIR__ . '/../layouts/admin-header.php';
?>

<style>
    .track-services-page {
        background: #F9FAFB;
        border-radius: 18px;
        padding: 24px;
    }

    .track-services-page .page-header {
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 16px;
        padding: 20px 24px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
        margin-bottom: 24px;
    }

    .track-services-page .card {
        border: 1px solid #E5E7EB;
        border-radius: 16px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .track-services-page .section-header {
        background: #FFFFFF;
        color: #111827;
        border-bottom: 1px solid #E5E7EB;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .track-services-page .section-header h5 {
        margin: 0;
        font-weight: 700;
        color: #111827;
    }

    .track-services-page .section-header small {
        color: #6B7280;
        font-weight: 600;
    }

    .track-services-page .table th {
        width: 35%;
        color: #6B7280;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .track-services-page .table td {
        color: #111827;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .track-services-page .progress-card {
        background: linear-gradient(135deg, #EEF2FF 0%, #F5F3FF 100%);
    }

    .track-services-page .service-list {
        display: grid;
        gap: 12px;
    }

    .track-services-page .service-item {
        border: 1px solid #E5E7EB;
        border-radius: 14px;
        padding: 16px;
        background: #FFFFFF;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .track-services-page .service-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08);
    }

    .track-services-page .service-item.completed {
        border-color: #BBF7D0;
        background: #F0FDF4;
    }

    .track-services-page .service-item h6 {
        font-weight: 700;
        color: #111827;
    }

    .track-services-page .btn-modern {
        border-radius: 10px;
        padding: 10px 16px;
        font-weight: 600;
    }

    .track-services-page .badge {
        border-radius: 999px;
        padding: 6px 12px;
        font-weight: 600;
    }

    .track-services-page .alert-modern {
        border-radius: 12px;
        border: 1px solid #E5E7EB;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .track-services-page {
            padding: 16px;
        }
    }
</style>

<div class="container-fluid py-4 track-services-page">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="page-header d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Service Delivery Tracking</h2>
                    <p class="text-muted mb-0">Claim #<?= e($claim['id'] ?? '') ?> - <?= e($claim['deceased_name'] ?? '') ?></p>
                </div>
                <a href="/admin/claims" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Claims
                </a>
            </div>

            <?php if (isset($_SESSION['success']) || isset($_SESSION['error'])): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const flashMessages = [
                            <?php if (isset($_SESSION['success'])): ?>{ type: 'success', message: <?php echo json_encode($_SESSION['success']); ?> },<?php unset($_SESSION['success']); endif; ?>
                            <?php if (isset($_SESSION['error'])): ?>{ type: 'error', message: <?php echo json_encode($_SESSION['error']); ?> },<?php unset($_SESSION['error']); endif; ?>
                        ];

                        flashMessages.forEach(function(flash) {
                            if (window.ShenaApp && typeof ShenaApp.showNotification === 'function') {
                                ShenaApp.showNotification(flash.message, flash.type, 5000);
                                return;
                            }
                            alert(flash.message);
                        });
                    });
                </script>
            <?php endif; ?>

            <div class="row">
                <!-- Claim Information Card -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header section-header">
                            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Claim Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th>Claim ID:</th>
                                    <td><?= e($claim['id'] ?? '') ?></td>
                                </tr>
                                <tr>
                                    <th>Member:</th>
                                    <td><?= e(($claim['first_name'] ?? '') . ' ' . ($claim['last_name'] ?? '')) ?></td>
                                </tr>
                                <tr>
                                    <th>Deceased:</th>
                                    <td><?= e($claim['deceased_name'] ?? '') ?></td>
                                </tr>
                                <tr> 
                                    <th>Date of Death:</th>
                                    <td><?= !empty($claim['date_of_death']) ? formatDate($claim['date_of_death']) : 'N/A' ?></td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge bg-<?= ($claim['status'] ?? '') === 'approved' ? 'success' : 'info' ?>">
                                            <?= ucfirst(str_replace('_', ' ', e($claim['status'] ?? ''))) ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Service Type:</th>
                                    <td>
                                        <?php if (($claim['service_delivery_type'] ?? '') === 'cash_alternative'): ?>
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
                                    <td><?= !empty($claim['services_delivery_date']) ? formatDate($claim['services_delivery_date']) : 'Not set' ?></td>
                                </tr>
                                <tr>
                                    <th>Mortuary Days:</th>
                                    <td><?= e($claim['mortuary_days_count'] ?? 0) ?> days (max 14)</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Completion Progress Card -->
                    <div class="card progress-card">
                        <div class="card-header section-header">
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
                                <div class="alert-modern" style="background: #ECFDF3; color: #065F46;">
                                    <i class="fas fa-check-circle"></i> All services completed!
                                </div>
                                <form method="POST" action="/admin/claims/complete" id="complete-claim-form">
                                    <input type="hidden" name="claim_id" value="<?= $claim['id'] ?>">
                                    <button type="button" onclick="confirmCompleteClaim()" class="btn btn-success btn-lg w-100 btn-modern">
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
                        <div class="card-header section-header">
                            <div>
                                <h5 class="mb-0"><i class="fas fa-tasks"></i> Service Delivery Checklist</h5>
                                <small>Per SHENA Companion Policy - Section 3</small>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (empty($checklist)): ?>
                                <div class="alert-modern" style="background: #FFFBEB; color: #92400E;">
                                    <i class="fas fa-exclamation-triangle"></i> No service checklist found. This may be a cash alternative claim.
                                </div>
                            <?php else: ?>
                                <div class="service-list">
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
                                        <div class="service-item <?= $item['completed'] ? 'completed' : '' ?>">
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
                                                        <button type="button" class="btn btn-sm btn-primary btn-modern" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#completeServiceModal"
                                                                data-service-type="<?= e($item['service_type']) ?>"
                                                                data-service-label="<?= e($serviceLabels[$item['service_type']]) ?>">
                                                            <i class="fas fa-check"></i> Mark Completed
                                                        </button>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">Completed</span>
                                                        <?php if ($item['service_notes']): ?>
                                                            <button type="button" class="btn btn-sm btn-info btn-modern" 
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
            <form method="POST" action="/admin/claims/track/<?= e($claim['id'] ?? '') ?>">
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

// Confirm Complete Claim
function confirmCompleteClaim() {
    ShenaApp.confirmAction(
        'Mark this claim as completed? This finalizes all service deliveries and closes the claim.',
        function() {
            document.getElementById('complete-claim-form').submit();
        },
        null,
        { type: 'success', title: 'Complete Claim', confirmText: 'Yes, Complete' }
    );
}
</script>

<?php require_once __DIR__ . '/../layouts/admin-footer.php'; ?>
