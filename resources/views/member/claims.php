
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
                            <th>Date of Death</th>
                            <th>Claim Amount</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($claims as $claim): ?>
                        <tr>
                            <td>#<?php echo $claim['id']; ?></td>
                            <td><?php echo htmlspecialchars($claim['deceased_name']); ?></td>
                            <td><?php echo !empty($claim['date_of_death']) ? date('M j, Y', strtotime($claim['date_of_death'])) : 'N/A'; ?></td>
                            <td><?php echo number_format($claim['claim_amount'], 2); ?></td>
                            <td><span class="badge bg-<?php echo $claim['status'] === 'approved' ? 'success' : ($claim['status'] === 'rejected' ? 'danger' : 'warning'); ?>"><?php echo !empty($claim['status']) ? ucfirst($claim['status']) : 'Pending'; ?></span></td>
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
                    <h6>Deceased Information</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Deceased Name</label>
                            <input type="text" name="deceased_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ID Number</label>
                            <input type="text" name="deceased_id_number" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date of Death</label>
                            <input type="date" name="date_of_death" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Place of Death</label>
                            <input type="text" name="place_of_death" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cause of Death</label>
                        <textarea name="cause_of_death" class="form-control" rows="2"></textarea>
                    </div>
                    <h6 class="mt-4">Claim Details</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mortuary Name</label>
                            <input type="text" name="mortuary_name" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mortuary Bill Amount</label>
                            <input type="number" name="mortuary_bill_amount" class="form-control" step="0.01">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Claim Amount</label>
                        <input type="number" name="claim_amount" class="form-control" step="0.01" required>
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
