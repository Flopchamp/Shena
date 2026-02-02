<?php 
$page = 'beneficiaries';
include __DIR__ . '/../layouts/member-header.php';

$beneficiaries = $beneficiaries ?? [];
$maxBeneficiaries = 5;
$availableSlots = $maxBeneficiaries - count($beneficiaries);
?>

<style>
.beneficiaries-container {
    padding: 30px 30px 40px 25px;
    background: #F8F9FC;
    max-width: 100%;
    margin: 0 0 0 0;
}

main {
    padding: 0 !important;
    margin: 0 !important;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
}

.page-title h1 {
    font-size: 1.75rem;
    font-weight: 700;
    color: #4A1468;
    margin: 0;
}

.search-bar {
    position: relative;
    width: 300px;
}

.search-bar input {
    width: 100%;
    padding: 10px 16px 10px 40px;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    font-size: 0.9rem;
    outline: none;
}

.search-bar i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #9CA3AF;
}

/* Main 2/3 and 1/3 Layout */
.content-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
    align-items: start;
}

.content-area {
    width: 100%;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.section-header h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #4A1468;
    margin: 0;
}

.add-dependent-btn {
    background: #7F3D9E;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
    transition: all 0.3s;
}

.add-dependent-btn:hover {
    background: #6B2D8A;
    transform: translateY(-1px);
}

.section-description {
    font-size: 0.9rem;
    color: #6B7280;
    margin: 0 0 30px 0;
}

.beneficiaries-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.beneficiary-card {
    background: white;
    border-radius: 16px;
    padding: 24px 28px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    position: relative;
}

.beneficiary-header {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 18px;
}

.beneficiary-avatar {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: linear-gradient(135deg, #EC4899, #F472B6);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.4rem;
    flex-shrink: 0;
}

.beneficiary-avatar.male {
    background: linear-gradient(135deg, #3B82F6, #60A5FA);
}

.beneficiary-info h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1F2937;
    margin: 0 0 6px 0;
}

.beneficiary-info p {
    font-size: 0.85rem;
    color: #6B7280;
    margin: 0 0 4px 0;
}

.age-bracket {
    font-size: 0.8rem;
    color: #9CA3AF;
}

.status-badge-card {
    position: absolute;
    top: 24px;
    right: 28px;
}

.active-badge-card {
    background: #D1FAE5;
    color: #059669;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 700;
}

.waiting-badge-card {
    background: #FEF3C7;
    color: #D97706;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 700;
}

.edit-details-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #7F3D9E;
    background: transparent;
    border: none;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    padding: 8px 0;
    transition: all 0.3s;
}

.edit-details-btn:hover {
    color: #6B2D8A;
}

.add-member-card {
    background: white;
    border: 2px dashed #D1D5DB;
    border-radius: 16px;
    padding: 30px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 200px;
    cursor: pointer;
    transition: all 0.3s;
}

.add-member-card:hover {
    border-color: #7F3D9E;
    background: #F9FAFB;
}

.add-icon {
    width: 60px;
    height: 60px;
    background: #F3F4F6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
}

.add-icon i {
    font-size: 1.8rem;
    color: #9CA3AF;
}

.add-member-card h4 {
    font-size: 1rem;
    font-weight: 600;
    color: #1F2937;
    margin: 0 0 6px 0;
}

.add-member-card p {
    font-size: 0.85rem;
    color: #6B7280;
    margin: 0;
}

/* Right Sidebar (1/3) */
.right-sidebar {
    display: flex;
    flex-direction: column;
    gap: 16px;
    position: sticky;
    top: 20px;
}

.coverage-policy-card {
    background: white;
    border-radius: 20px;
    padding: 30px;
    border-left: 4px solid #7F20B0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.policy-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 25px;
}

.policy-icon {
    width: 32px;
    height: 32px;
    background: #F59E0B;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.policy-icon i {
    color: white;
    font-size: 1rem;
}

.policy-header h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1F2937;
    margin: 0;
}

.policy-section {
    margin-bottom: 25px;
}

.policy-section:last-child {
    margin-bottom: 0;
}

.policy-section h4 {
    font-size: 0.75rem;
    font-weight: 700;
    color: #6B7280;
    letter-spacing: 1px;
    margin: 0 0 12px 0;
}

.policy-section p {
    font-size: 0.85rem;
    color: #4B5563;
    line-height: 1.6;
    margin: 0;
}

.benefit-limit {
    background: #F3E8FF;
    padding: 20px;
    border-radius: 12px;
    text-align: center;
    margin-top: 25px;
}

.benefit-limit h4 {
    font-size: 0.75rem;
    font-weight: 700;
    color: #7F3D9E;
    letter-spacing: 1px;
    margin: 0 0 8px 0;
}

.benefit-limit h2 {
    font-size: 2rem;
    font-weight: 700;
    color: #7F3D9E;
    margin: 0 0 4px 0;
}

.benefit-limit p {
    font-size: 0.8rem;
    color: #9333EA;
    margin: 0;
}

.need-help-card {
    background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
    border-radius: 20px;
    padding: 30px;
    color: white;
}

.need-help-card h3 {
    font-size: 1.3rem;
    font-weight: 700;
    margin: 0 0 12px 0;
}

.need-help-card p {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.9);
    line-height: 1.5;
    margin: 0 0 20px 0;
}

.chat-btn {
    background: #F59E0B;
    color: #1F2937;
    border: none;
    padding: 14px 0;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.95rem;
    cursor: pointer;
    width: 100%;
    transition: all 0.3s;
}

.chat-btn:hover {
    background: #D97706;
}

@media (max-width: 1024px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
    
    .beneficiaries-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="beneficiaries-container">
    <div class="page-header">
        <div class="page-title">
            <h1>Manage Dependents & Beneficiaries</h1>
        </div>
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search dependents...">
        </div>
    </div>

    <div class="content-grid">
        <div class="content-area">
            <div class="section-header">
                <h2>Your Covered Family</h2>
                <button class="add-dependent-btn" data-bs-toggle="modal" data-bs-target="#addBeneficiaryModal">
                    <i class="fas fa-user-plus"></i> Add New Dependent
                </button>
            </div>
            <p class="section-description">Review and manage your policy beneficiaries and dependents.</p>
            
            <div class="beneficiaries-grid">
                <?php if (!empty($beneficiaries)): ?>
                    <?php foreach ($beneficiaries as $index => $beneficiary): ?>
                        <div class="beneficiary-card">
                            <div class="status-badge-card">
                                <span class="<?php echo ($beneficiary['is_active'] ?? true) ? 'active-badge-card' : 'waiting-badge-card'; ?>">
                                    <?php echo ($beneficiary['is_active'] ?? true) ? 'ACTIVE' : 'WAITING'; ?>
                                </span>
                            </div>
                            <div class="beneficiary-header">
                                <div class="beneficiary-avatar <?php echo in_array(strtolower($beneficiary['relationship'] ?? ''), ['son', 'father', 'brother']) ? 'male' : ''; ?>">
                                    <?php echo strtoupper(substr($beneficiary['full_name'] ?? 'U', 0, 1)); ?>
                                </div>
                                <div class="beneficiary-info">
                                    <h3><?php echo htmlspecialchars($beneficiary['full_name'] ?? 'Unknown'); ?></h3>
                                    <p><?php echo htmlspecialchars(ucfirst($beneficiary['relationship'] ?? 'Relation')); ?></p>
                                    <p class="age-bracket">Age Bracket: <?php echo htmlspecialchars($beneficiary['age_bracket'] ?? '35-40 years'); ?></p>
                                </div>
                            </div>
                            <button class="edit-details-btn" onclick="editBeneficiary(<?php echo $beneficiary['id']; ?>)">
                                <i class="fas fa-edit"></i> Edit Details
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="beneficiary-card">
                        <div class="status-badge-card">
                            <span class="active-badge-card">ACTIVE</span>
                        </div>
                        <div class="beneficiary-header">
                            <div class="beneficiary-avatar">J</div>
                            <div class="beneficiary-info">
                                <h3>Jane Doe</h3>
                                <p>Spouse</p>
                                <p class="age-bracket">Age Bracket: 35-40 years</p>
                            </div>
                        </div>
                        <button class="edit-details-btn">
                            <i class="fas fa-edit"></i> Edit Details
                        </button>
                    </div>
                    
                    <div class="beneficiary-card">
                        <div class="status-badge-card">
                            <span class="active-badge-card">ACTIVE</span>
                        </div>
                        <div class="beneficiary-header">
                            <div class="beneficiary-avatar male">L</div>
                            <div class="beneficiary-info">
                                <h3>Leo Doe</h3>
                                <p>Son</p>
                                <p class="age-bracket">Age Bracket: 5-10 years</p>
                            </div>
                        </div>
                        <button class="edit-details-btn">
                            <i class="fas fa-edit"></i> Edit Details
                        </button>
                    </div>
                    
                    <div class="beneficiary-card">
                        <div class="status-badge-card">
                            <span class="waiting-badge-card">WAITING</span>
                        </div>
                        <div class="beneficiary-header">
                            <div class="beneficiary-avatar">M</div>
                            <div class="beneficiary-info">
                                <h3>Mary Smith</h3>
                                <p>Mother-in-law</p>
                                <p class="age-bracket">Age Bracket: 65-70 years</p>
                            </div>
                        </div>
                        <button class="edit-details-btn">
                            <i class="fas fa-edit"></i> Edit Details
                        </button>
                    </div>
                <?php endif; ?>
                
                <div class="add-member-card" data-bs-toggle="modal" data-bs-target="#addBeneficiaryModal">
                    <div class="add-icon">
                        <i class="fas fa-plus"></i>
                    </div>
                    <h4>Add Member</h4>
                    <p>Available slots: <?php echo $availableSlots; ?></p>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="right-sidebar">
            <div class="coverage-policy-card">
                <div class="policy-header">
                    <div class="policy-icon">
                        <i class="fas fa-exclamation"></i>
                    </div>
                    <h3>Coverage Policy</h3>
                </div>
                
                <div class="policy-section">
                    <h4>NUCLEAR FAMILY</h4>
                    <p>Includes spouse and up to 4 biological or legally adopted children under 21 years. Full coverage applies after a 3-month waiting period.</p>
                </div>
                
                <div class="policy-section">
                    <h4>EXTENDED FAMILY</h4>
                    <p>Includes parents, siblings, or in-laws. Maximum of 2 extended members allowed per policy. A 6-month waiting period applies for natural causes.</p>
                </div>
                
                <div class="benefit-limit">
                    <h4>BENEFIT LIMIT</h4>
                    <h2>$15,000</h2>
                    <p>per member</p>
                </div>
            </div>
            
            <div class="need-help-card">
                <h3>Need Help?</h3>
                <p>Unsure about relationship proof documents? Chat with our support team.</p>
                <button class="chat-btn">START CHAT</button>
            </div>
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

<?php include __DIR__ . '/../layouts/member-footer.php'; ?>
