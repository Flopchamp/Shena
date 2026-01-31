
<?php
$page = 'claims';
include VIEWS_PATH . '/layouts/member-header.php';
?>

<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-file-medical"></i> Claims Management</h2>
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header d-flex justify-content-between align-items-center bg-white">
            <h5 class="mb-0">My Claims</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#submitClaimModal">
                <i class="fas fa-plus"></i> Submit New Claim
            </button>
        </div>
        <div class="card-body">
            <?php if (!empty($claims)): ?>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Claim ID</th>
                            <th>Deceased Name</th>
                            <th>Relationship</th>
                            <th>Date of Death</th>
                            <th>Service Type</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($claims as $claim): ?>
                        <tr>
                            <td>#<?php echo str_pad($claim['id'], 4, '0', STR_PAD_LEFT); ?></td>
                            <td><?php echo htmlspecialchars($claim['deceased_name']); ?></td>
                            <td><?php echo !empty($claim['relationship_to_deceased']) ? ucfirst($claim['relationship_to_deceased']) : 'N/A'; ?></td>
                            <td><?php echo !empty($claim['date_of_death']) ? date('M j, Y', strtotime($claim['date_of_death'])) : 'N/A'; ?></td>
                            <td>
                                <?php if ($claim['service_delivery_type'] === 'cash_alternative'): ?>
                                    <span class="badge bg-warning text-dark"><i class="fas fa-money-bill"></i> Cash (KES 20,000)</span>
                                <?php else: ?>
                                    <span class="badge bg-success"><i class="fas fa-hands-helping"></i> Funeral Services</span>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge bg-<?php echo $claim['status'] === 'approved' ? 'success' : ($claim['status'] === 'rejected' ? 'danger' : 'warning'); ?>"><?php echo !empty($claim['status']) ? ucfirst(str_replace('_', ' ', $claim['status'])) : 'Pending'; ?></span></td>
                            <td><?php echo date('M j, Y', strtotime($claim['created_at'])); ?></td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick="viewClaim(<?php echo $claim['id']; ?>)">View</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <p class="text-muted text-center py-4">No claims submitted yet</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Submit Claim Modal -->
<div class="modal fade" id="submitClaimModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="/claims" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Submit New Claim</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <strong>Service-Based Claims:</strong> SHENA provides comprehensive funeral services including mortuary bills (max 14 days), body dressing, executive coffin, transportation, and equipment (lowering gear, trolley, gazebo, 100 chairs).
                    </div>
                    <h6>Deceased Information</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="deceased_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ID/Birth Certificate Number <span class="text-danger">*</span></label>
                            <input type="text" name="deceased_id_number" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Relationship to Deceased <span class="text-danger">*</span></label>
                            <select name="relationship_to_deceased" class="form-select" required>
                                <option value="">Select relationship</option>
                                <option value="self">Self</option>
                                <option value="spouse">Spouse</option>
                                <option value="parent">Parent</option>
                                <option value="child">Child</option>
                                <option value="sibling">Sibling</option>
                                <option value="dependent">Registered Dependent</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control">
                            <small class="form-text text-muted">Optional, for dependent age verification</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date of Death <span class="text-danger">*</span></label>
                            <input type="date" name="date_of_death" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Place of Death <span class="text-danger">*</span></label>
                            <input type="text" name="place_of_death" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cause of Death <span class="text-danger">*</span></label>
                        <textarea name="cause_of_death" class="form-control" rows="2" required></textarea>
                        <small class="form-text text-muted">Required for claim verification. Excluded causes include: self-medication, drug/substance abuse, criminal acts, riots/war, hazardous activities.</small>
                    </div>
                    <h6 class="mt-4">Mortuary & Service Details</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mortuary Name <span class="text-danger">*</span></label>
                            <input type="text" name="mortuary_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Number of Days in Mortuary <span class="text-danger">*</span></label>
                            <input type="number" name="mortuary_days_count" class="form-control" min="0" max="14" required>
                            <small class="form-text text-muted">Maximum 14 days covered per policy</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mortuary Bill Amount</label>
                            <input type="number" name="mortuary_bill_amount" class="form-control" step="0.01">
                            <small class="form-text text-muted">For reference and verification</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mortuary Bill Reference/Invoice #</label>
                            <input type="text" name="mortuary_bill_reference" class="form-control">
                        </div>
                    </div>
                    <h6 class="mt-4">Required Documents</h6>
                    <p class="text-muted small">
                        As per SHENA Companion policy, the following documents are mandatory:
                        Copy of ID/Birth Certificate, Letter from the Area Chief, and Mortuary Invoice.
                        Death certificate is recommended but optional.
                    </p>
                    <div class="mb-3">
                        <label class="form-label">Copy of ID / Birth Certificate (Required)</label>
                        <input type="file" name="id_copy" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Chief's Letter (Required)</label>
                        <input type="file" name="chief_letter" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mortuary Invoice (Required)</label>
                        <input type="file" name="mortuary_invoice" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Death Certificate (Optional)</label>
                        <input type="file" name="death_certificate" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Claim</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/layouts/member-footer.php'; ?>
