<?php
$page = 'claims';
include __DIR__ . '/../layouts/member-header.php';

// Sample data for demonstration
$claims = $claims ?? [];
$beneficiaries = $beneficiaries ?? [];
$activeClaims = array_filter($claims, fn($c) => $c['status'] !== 'approved' && $c['status'] !== 'rejected');
$pastClaims = array_filter($claims, fn($c) => $c['status'] === 'approved' || $c['status'] === 'rejected');
$hasBeneficiaries = !empty($beneficiaries);

// No mock data - use real data from controller
?>

<style>
main {
    padding: 0 !important;
    margin: 0 !important;
}

.claims-container {
    padding: 30px 30px 40px 25px;
    background: #F8F9FC;
    max-width: 100%;
    margin: 0;
}

.page-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #4A1468;
    margin: 0 0 30px 0;
}

.hero-card {
    background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
    border-radius: 20px;
    padding: 40px;
    color: white;
    margin-bottom: 40px;
    position: relative;
    overflow: hidden;
}

.hero-card::after {
    content: '📋';
    position: absolute;
    right: 40px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 120px;
    opacity: 0.1;
}

.hero-card h2 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0 0 12px 0;
    position: relative;
    z-index: 1;
}

.hero-card p {
    font-size: 1rem;
    margin: 0 0 25px 0;
    color: rgba(255, 255, 255, 0.9);
    max-width: 500px;
    position: relative;
    z-index: 1;
}

.submit-claim-btn {
    background: #F59E0B;
    color: #1F2937;
    border: none;
    padding: 14px 28px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s;
    position: relative;
    z-index: 1;
}

.submit-claim-btn:hover {
    background: #D97706;
    transform: translateY(-2px);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.section-header h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1F2937;
    margin: 0;
}

.tabs {
    display: flex;
    gap: 20px;
}

.tab {
    background: transparent;
    border: none;
    padding: 8px 0;
    font-size: 0.95rem;
    font-weight: 600;
    color: #6B7280;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.3s;
}

.tab.active {
    color: #7F3D9E;
    border-bottom-color: #7F3D9E;
}

.claim-card {
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    margin-bottom: 30px;
}

.claim-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 25px;
}

.claim-info h4 {
    font-size: 0.75rem;
    font-weight: 600;
    color: #6B7280;
    letter-spacing: 0.5px;
    margin: 0 0 8px 0;
}

.claim-info h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1F2937;
    margin: 0;
}

.claim-meta {
    text-align: right;
}

.claim-meta p {
    font-size: 0.75rem;
    color: #6B7280;
    margin: 0 0 8px 0;
}

.claim-meta h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1F2937;
    margin: 0 0 10px 0;
}

.status-badge {
    background: #FEF3C7;
    color: #D97706;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.progress-tracker {
    display: flex;
    justify-content: space-between;
    position: relative;
    margin: 35px 0;
}

.progress-tracker::before {
    content: '';
    position: absolute;
    top: 30px;
    left: 30px;
    right: 30px;
    height: 2px;
    background: #E5E7EB;
    z-index: 0;
}

.progress-step {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 1;
}

.step-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
    font-size: 1.3rem;
}

.step-icon.completed {
    background: #7F3D9E;
    color: white;
}

.step-icon.processing {
    background: #7F3D9E;
    color: white;
}

.step-icon.pending {
    background: #E5E7EB;
    color: #9CA3AF;
}

.step-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #1F2937;
    margin-bottom: 4px;
    text-align: center;
}

.step-date {
    font-size: 0.75rem;
    color: #6B7280;
}

.step-status {
    font-size: 0.75rem;
    color: #F59E0B;
    font-style: italic;
}

.next-step-card {
    background: #F3E8FF;
    border-left: 4px solid #7F3D9E;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    gap: 15px;
    margin-top: 25px;
}

.next-step-card i {
    color: #7F3D9E;
    font-size: 1.2rem;
    margin-top: 2px;
}

.next-step-content h4 {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1F2937;
    margin: 0 0 8px 0;
}

.next-step-content p {
    font-size: 0.85rem;
    color: #4B5563;
    line-height: 1.6;
    margin: 0;
}

.past-claims-section {
    margin-top: 50px;
}

.past-claims-section h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #6B7280;
    letter-spacing: 1px;
    margin: 0 0 20px 0;
}

.past-claim-item {
    background: white;
    border-radius: 16px;
    padding: 20px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    transition: all 0.3s;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.past-claim-item:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.past-claim-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #D1FAE5;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #059669;
    font-size: 1.2rem;
}

.past-claim-info {
    flex: 1;
    margin: 0 20px;
}

.past-claim-info h4 {
    font-size: 1rem;
    font-weight: 700;
    color: #1F2937;
    margin: 0 0 4px 0;
}

.past-claim-info p {
    font-size: 0.85rem;
    color: #6B7280;
    margin: 0;
}

.past-claim-amount {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1F2937;
    margin-right: 15px;
}

.past-claim-arrow {
    color: #9CA3AF;
    font-size: 1.2rem;
}

.documents-sidebar {
    background: #5E2B7A;
    border-radius: 20px;
    padding: 25px;
    color: white;
    position: sticky;
    top: 20px;
}

.documents-sidebar h4 {
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 1px;
    margin: 0 0 20px 0;
    color: rgba(255, 255, 255, 0.8);
}

.document-item {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 15px;
}

.document-icon {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.document-icon.checked {
    background: #10B981;
    color: white;
    font-size: 0.7rem;
}

.document-icon.unchecked {
    background: transparent;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.document-item span {
    font-size: 0.9rem;
    color: white;
}

.document-note {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.6);
    margin-top: 20px;
    line-height: 1.5;
}

@media (max-width: 1024px) {
    .claims-container {
        padding: 20px;
    }
    
    .progress-tracker {
        flex-wrap: wrap;
    }
}

/* Modal Styling */
.modal-content {
    border-radius: 20px;
    border: none;
}

.modal-header {
    background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
    color: white;
    padding: 25px 30px;
    border-radius: 20px 20px 0 0;
    border: none;
}

.modal-header .modal-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
    opacity: 0.8;
}

.modal-header .btn-close:hover {
    opacity: 1;
}

.modal-body {
    padding: 35px 30px;
}

.modal-body h6 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1F2937;
    margin: 25px 0 15px 0;
    padding-bottom: 10px;
    border-bottom: 2px solid #E5E7EB;
}

.modal-body h6:first-child {
    margin-top: 0;
}

.modal-body .form-label {
    font-size: 0.9rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.modal-body .form-control,
.modal-body .form-select {
    border: 1.5px solid #E5E7EB;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 0.95rem;
    transition: all 0.3s;
}

.modal-body .form-control:focus,
.modal-body .form-select:focus {
    border-color: #7F3D9E;
    box-shadow: 0 0 0 3px rgba(127, 61, 158, 0.1);
}

.modal-body .alert-info {
    background: #F0F9FF;
    border-left: 4px solid #3B82F6;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 25px;
}

.modal-body .alert-info i {
    color: #3B82F6;
}

.modal-body .text-muted {
    font-size: 0.8rem;
    color: #6B7280;
    margin-top: 4px;
}

.modal-footer {
    padding: 20px 30px;
    border-top: 1px solid #E5E7EB;
    border-radius: 0 0 20px 20px;
}

.modal-footer .btn {
    padding: 12px 30px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.95rem;
}

.modal-footer .btn-secondary {
    background: #F3F4F6;
    color: #374151;
    border: none;
}

.modal-footer .btn-secondary:hover {
    background: #E5E7EB;
}

.modal-footer .btn-primary {
    background: #7F3D9E;
    border: none;
}

.modal-footer .btn-primary:hover {
    background: #6B2D8A;
}
</style>

<div class="claims-container">
    <div style="display: grid; grid-template-columns: 1fr 200px; gap: 30px; align-items: start;">
        <div>
            <h1 class="page-title">Claims Center</h1>
            
            <!-- Hero Card -->
            <div class="hero-card">
                <h2>Report a Loss</h2>
                <p>We understand this is a difficult time. Our team is here to support you with a fast and compassionate claims process.</p>
                <button class="submit-claim-btn" data-bs-toggle="modal" data-bs-target="#submitClaimModal">
                    <i class="fas fa-plus-circle"></i> Submit New Burial Claim
                </button>
            </div>
            
            <!-- Track My Claims -->
            <div class="section-header">
                <h3>Track My Claims</h3>
                <div class="tabs">
                    <button class="tab active" data-tab="all">All Claims</button>
                    <button class="tab" data-tab="active">Active (<?php echo count($activeClaims); ?>)</button>
                    <button class="tab" data-tab="past">Past (<?php echo count($pastClaims); ?>)</button>
                </div>
            </div>
            
            <!-- Active Claims -->
            <div class="claims-section" data-section="active">
                <?php if (!empty($activeClaims)): ?>
                    <?php foreach ($activeClaims as $claim): ?>
                    <div class="claim-card">
                        <div class="claim-header">
                            <div class="claim-info">
                                <h4>Claim ID: <?php echo htmlspecialchars($claim['id']); ?></h4>
                                <h3><?php echo htmlspecialchars($claim['deceased_name'] ?? 'Unknown'); ?> (<?php echo htmlspecialchars($claim['relationship'] ?? 'N/A'); ?>)</h3>
                                <p>Date of Death: <?php echo htmlspecialchars($claim['date_of_death'] ?? 'N/A'); ?></p>
                                <p>Place of Death: <?php echo htmlspecialchars($claim['place_of_death'] ?? 'N/A'); ?></p>
                            </div>
                            <div class="claim-meta">
                                <p>STATUS</p>
                                <span class="status-badge"><?php echo strtoupper(htmlspecialchars($claim['status'] ?? 'submitted')); ?></span>
                                <p style="margin-top: 10px; font-size: 0.8rem; color: #6B7280;">
                                    Submitted: <?php echo htmlspecialchars(date('M d, Y', strtotime($claim['created_at'] ?? 'now'))); ?>
                                </p>
                            </div>
                        </div>

                        <!-- Next Step -->
                        <div class="next-step-card">
                            <i class="fas fa-info-circle"></i>
                            <div class="next-step-content">
                                <h4>Next Step:</h4>
                                <p>Your claim has been submitted and is under review. SHENA will contact you within 1-3 business days.</p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="claim-card" style="text-align: center; color: #6B7280;">
                        <p style="margin: 0;">No active claims at the moment.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Past Claims -->
            <div class="past-claims-section claims-section" data-section="past">
                <h3>PAST CLAIMS</h3>
                <?php if (!empty($pastClaims)): ?>
                    <?php foreach ($pastClaims as $claim): ?>
                    <div class="past-claim-item">
                        <div class="past-claim-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="past-claim-info">
                            <h4><?php echo htmlspecialchars($claim['deceased_name'] ?? 'Unknown'); ?> (<?php echo htmlspecialchars($claim['relationship'] ?? 'N/A'); ?>)</h4>
                            <p>Claim ID: <?php echo htmlspecialchars($claim['id']); ?> • Paid <?php echo htmlspecialchars($claim['paid_date'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="past-claim-amount">KES <?php echo number_format($claim['payout'] ?? 0, 2); ?></div>
                        <div class="past-claim-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="color: #6B7280;">No past claims to show.</div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Documents Sidebar -->
        <div class="documents-sidebar">
            <h4>3 ESSENTIAL DOCUMENTS</h4>
            <div class="document-item">
                <div class="document-icon checked">
                    <i class="fas fa-check"></i>
                </div>
                <span>ID Copy (Deceased)</span>
            </div>
            <div class="document-item">
                <div class="document-icon unchecked"></div>
                <span>Chief's Letter</span>
            </div>
            <div class="document-item">
                <div class="document-icon unchecked"></div>
                <span>Mortuary Invoice</span>
            </div>
            <p class="document-note">These must be submitted for any claim to be processed</p>
        </div>
    </div>
</div>

<!-- Submit Claim Modal -->
<div class="modal fade" id="submitClaimModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="/claims" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-file-medical"></i> Submit New Burial Claim</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <strong>Service-Based Claims:</strong> SHENA provides comprehensive funeral services including mortuary bills (max 14 days), body dressing, executive coffin, transportation, and equipment.
                    </div>
                    
                    <h6><i class="fas fa-user"></i> Deceased Information</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="deceased_name" class="form-control" placeholder="Enter full name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ID/Birth Certificate Number <span class="text-danger">*</span></label>
                            <input type="text" name="deceased_id_number" class="form-control" placeholder="Enter ID number" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Beneficiary <span class="text-danger">*</span></label>
                            <select name="beneficiary_id" class="form-select" required <?php echo !$hasBeneficiaries ? 'disabled' : ''; ?>>
                                <option value="">Select beneficiary</option>
                                <?php foreach ($beneficiaries as $beneficiary): ?>
                                    <option value="<?php echo (int)$beneficiary['id']; ?>">
                                        <?php echo htmlspecialchars($beneficiary['full_name'] . ' (' . $beneficiary['relationship'] . ')'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (!$hasBeneficiaries): ?>
                                <small class="text-muted">Add a beneficiary before submitting a claim.</small>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date of Death <span class="text-danger">*</span></label>
                            <input type="date" name="date_of_death" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Place of Death <span class="text-danger">*</span></label>
                        <input type="text" name="place_of_death" class="form-control" placeholder="City, Hospital, or Location" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cause of Death <span class="text-danger">*</span></label>
                        <textarea name="cause_of_death" class="form-control" rows="2" placeholder="Brief description of cause" required></textarea>
                        <small class="text-muted">Excluded: self-medication, drug abuse, criminal acts, riots/war, hazardous activities</small>
                    </div>
                    
                    <h6><i class="fas fa-hospital"></i> Mortuary & Service Details</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mortuary Name <span class="text-danger">*</span></label>
                            <input type="text" name="mortuary_name" class="form-control" placeholder="Name of mortuary" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Days in Mortuary <span class="text-danger">*</span></label>
                            <input type="number" name="mortuary_days_count" class="form-control" min="0" max="14" placeholder="Max 14 days" required>
                            <small class="text-muted">Maximum 14 days covered per policy</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mortuary Bill Amount (KES) <span class="text-danger">*</span></label>
                        <input type="number" name="mortuary_bill_amount" class="form-control" min="0" step="0.01" placeholder="0.00" required>
                    </div>
                    
                    <h6><i class="fas fa-paperclip"></i> Required Documents</h6>
                    <p class="text-muted small mb-3">
                        <i class="fas fa-exclamation-circle text-warning"></i> The following 3 documents are mandatory for claim processing
                    </p>
                    <div class="mb-3">
                        <label class="form-label">1. Copy of ID / Birth Certificate <span class="text-danger">*</span></label>
                        <input type="file" name="id_copy" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">2. Chief's Letter <span class="text-danger">*</span></label>
                        <input type="file" name="chief_letter" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">3. Mortuary Invoice <span class="text-danger">*</span></label>
                        <input type="file" name="mortuary_invoice" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Death Certificate <span class="badge bg-secondary">Optional</span></label>
                        <input type="file" name="death_certificate" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" <?php echo !$hasBeneficiaries ? 'disabled' : ''; ?>><i class="fas fa-check-circle"></i> Submit Claim</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.tab').forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');

        const selected = this.dataset.tab;
        document.querySelectorAll('.claims-section').forEach(section => {
            if (selected === 'all') {
                section.style.display = '';
                return;
            }

            section.style.display = section.dataset.section === selected ? '' : 'none';
        });
    });
});
</script>

<?php include __DIR__ . '/../layouts/member-footer.php'; ?>
