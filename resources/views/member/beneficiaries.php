
<?php include VIEWS_PATH . '/layouts/member-header.php'; ?>


<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-users"></i> Beneficiary Management</h2>
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header d-flex justify-content-between align-items-center bg-white">
            <h5 class="mb-0">My Beneficiaries</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addBeneficiaryModal">
                <i class="fas fa-plus"></i> Add Beneficiary
            </button>
        </div>
        <div class="card-body">
            <?php if (!empty($beneficiaries)): ?>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Relationship</th>
                            <th>ID Number</th>
                            <th>Phone</th>
                            <th>Percentage</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($beneficiaries as $beneficiary): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($beneficiary['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($beneficiary['relationship']); ?></td>
                            <td><?php echo htmlspecialchars($beneficiary['id_number']); ?></td>
                            <td><?php echo htmlspecialchars($beneficiary['phone_number'] ?? 'N/A'); ?></td>
                            <td><?php echo $beneficiary['percentage']; ?>%</td>
                            <td><span class="badge bg-<?php echo $beneficiary['is_active'] ? 'success' : 'secondary'; ?>"><?php echo $beneficiary['is_active'] ? 'Active' : 'Inactive'; ?></span></td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editBeneficiary(<?php echo $beneficiary['id']; ?>)">Edit</button>
                                <form method="POST" action="/beneficiaries/delete" style="display:inline;">
                                    <input type="hidden" name="beneficiary_id" value="<?php echo $beneficiary['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this beneficiary?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <p class="text-muted text-center py-4">No beneficiaries added yet</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Edit Beneficiary Modal -->
<div class="modal fade" id="editBeneficiaryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/beneficiaries/update">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Beneficiary</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="beneficiary_id" id="editBeneficiaryId">
                    <div class="mb-3">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="full_name" id="editFullName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Relationship *</label>
                        <input type="text" name="relationship" id="editRelationship" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ID Number *</label>
                        <input type="text" name="id_number" id="editIdNumber" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone_number" id="editPhoneNumber" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Percentage (%) *</label>
                        <input type="number" name="percentage" id="editPercentage" class="form-control" min="1" max="100" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Beneficiary</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Beneficiary Modal -->
<div class="modal fade" id="addBeneficiaryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/beneficiaries" id="addBeneficiaryForm">
                <div class="modal-header">
                    <h5 class="modal-title">Add Beneficiary</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <div class="mb-3">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Relationship *</label>
                        <input type="text" name="relationship" class="form-control" placeholder="e.g., Spouse, Child, Parent" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ID Number *</label>
                        <input type="text" name="id_number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone_number" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Percentage (%) *</label>
                        <input type="number" name="percentage" class="form-control" min="1" max="100" value="100" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Beneficiary</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editBeneficiary(id) {
    const beneficiaries = <?php echo json_encode($beneficiaries ?? []); ?>;
    const beneficiary = beneficiaries.find(b => b.id == id);
    if (!beneficiary) return;
    
    document.getElementById('editBeneficiaryId').value = beneficiary.id;
    document.getElementById('editFullName').value = beneficiary.full_name;
    document.getElementById('editRelationship').value = beneficiary.relationship;
    document.getElementById('editIdNumber').value = beneficiary.id_number;
    document.getElementById('editPhoneNumber').value = beneficiary.phone_number || '';
    document.getElementById('editPercentage').value = beneficiary.percentage;
    
    new bootstrap.Modal(document.getElementById('editBeneficiaryModal')).show();
}
</script>

<?php include VIEWS_PATH . '/layouts/member-footer.php'; ?>
