<?php
$page = 'claims';
$pageTitle = 'Claims Management';
$pageSubtitle = 'Process and manage member insurance claims';
include VIEWS_PATH . '/layouts/dashboard-header.php';

// Calculate stats
$pending_count = count(array_filter($claims ?? [], fn($c) => $c['status'] === 'pending'));
$approved_count = count(array_filter($claims ?? [], fn($c) => $c['status'] === 'approved'));
$rejected_count = count(array_filter($claims ?? [], fn($c) => $c['status'] === 'rejected'));
$total_value = array_sum(array_column($claims ?? [], 'claim_amount'));
?>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--gradient-warning);">
            <i class="bi bi-clock-fill"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value"><?php echo number_format($pending_count); ?></div>
            <div class="stat-label">Pending Claims</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--gradient-success);">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value"><?php echo number_format($approved_count); ?></div>
            <div class="stat-label">Approved</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--gradient-danger);">
            <i class="bi bi-x-circle-fill"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value"><?php echo number_format($rejected_count); ?></div>
            <div class="stat-label">Rejected</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--gradient-info);">
            <i class="bi bi-cash-stack"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value">KES <?php echo number_format($total_value); ?></div>
            <div class="stat-label">Total Value</div>
        </div>
    </div>
</div>

<!-- Search and Filter Card -->
<div class="card" style="margin-top: 2rem;">
    <div class="card-header">
        <h4 style="margin: 0;"><i class="bi bi-funnel-fill"></i> Filter Claims</h4>
    </div>
    <div class="card-body">
        <form method="GET" action="/admin/claims" style="display: grid; grid-template-columns: 2fr 1fr auto auto; gap: 1rem; align-items: end;">
            <div class="form-group" style="margin: 0;">
                <label class="form-label" for="search">Search Claims</label>
                <input type="text" 
                       id="search" 
                       name="search" 
                       class="form-control" 
                       placeholder="Member name or claim ID" 
                       value="<?php echo htmlspecialchars($search ?? ''); ?>">
            </div>
            
            <div class="form-group" style="margin: 0;">
                <label class="form-label" for="status">Status</label>
                <select id="status" name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" <?php echo ($status ?? '') === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="approved" <?php echo ($status ?? '') === 'approved' ? 'selected' : ''; ?>>Approved</option>
                    <option value="rejected" <?php echo ($status ?? '') === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                    <option value="processing" <?php echo ($status ?? '') === 'processing' ? 'selected' : ''; ?>>Processing</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i> Search
            </button>
            
            <a href="/admin/claims" class="btn btn-outline">
                <i class="bi bi-x-circle"></i> Clear
            </a>
        </form>
    </div>
</div>

<!-- Claims Table -->
<div class="card" style="margin-top: 2rem;">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h4 style="margin: 0;"><i class="bi bi-file-medical-fill"></i> Claims List</h4>
            <div style="display: flex; gap: 0.5rem;">
                <button class="btn btn-success btn-sm" onclick="window.location.href='/admin/export/claims'">
                    <i class="bi bi-download"></i> Export
                </button>
                <button class="btn btn-primary btn-sm" onclick="openModal('newClaimModal')">
                    <i class="bi bi-plus-circle-fill"></i> New Claim
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <?php if (!empty($claims)): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Claim ID</th>
                        <th>Member</th>
                        <th>Deceased Info</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($claims as $claim): ?>
                    <tr>
                        <td>
                            <span style="font-family: var(--font-mono); font-weight: 600; color: var(--primary-purple);">
                                #<?php echo str_pad($claim['id'], 4, '0', STR_PAD_LEFT); ?>
                            </span>
                        </td>
                        <td>
                            <div>
                                <div style="font-weight: 600; color: var(--secondary-violet);">
                                    <?php echo htmlspecialchars($claim['first_name'] . ' ' . $claim['last_name']); ?>
                                </div>
                                <div style="font-size: 0.75rem; color: var(--medium-grey);">
                                    <?php echo htmlspecialchars($claim['member_number']); ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div style="font-weight: 600;">
                                    <?php echo htmlspecialchars($claim['deceased_name']); ?>
                                </div>
                                <?php if (!empty($claim['relationship_to_deceased'])): ?>
                                <div style="font-size: 0.75rem; color: var(--medium-grey);">
                                    <?php echo ucfirst($claim['relationship_to_deceased']); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: var(--secondary-violet); font-family: var(--font-mono);">
                                KES <?php echo number_format($claim['claim_amount'], 2); ?>
                            </div>
                        </td>
                        <td>
                            <?php
                            $statusClass = match($claim['status']) {
                                'pending' => 'badge-warning',
                                'approved' => 'badge-success',
                                'rejected' => 'badge-danger',
                                'processing' => 'badge-info',
                                default => 'badge-secondary'
                            };
                            ?>
                            <span class="badge <?php echo $statusClass; ?>">
                                <?php echo ucfirst($claim['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($claim['submitted_at'] ?? $claim['created_at'])); ?></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-info" 
                                        onclick="viewClaim(<?php echo $claim['id']; ?>)"
                                        title="View Details">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                                
                                <?php if ($claim['status'] === 'pending'): ?>
                                <form method="POST" action="/admin/claim/approve" style="display: inline;" onsubmit="return confirm('Approve this claim?')">
                                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                                    <input type="hidden" name="claim_id" value="<?php echo $claim['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </button>
                                </form>
                                <form method="POST" action="/admin/claim/reject" style="display: inline;" onsubmit="return confirm('Reject this claim?')">
                                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                                    <input type="hidden" name="claim_id" value="<?php echo $claim['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" title="Reject">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Store claim data for modal -->
                    <script>
                        window.claimData = window.claimData || {};
                        window.claimData[<?php echo $claim['id']; ?>] = {
                            claim_id: "#<?php echo str_pad($claim['id'], 4, '0', STR_PAD_LEFT); ?>",
                            member_name: "<?php echo htmlspecialchars($claim['first_name'] . ' ' . $claim['last_name']); ?>",
                            member_number: "<?php echo htmlspecialchars($claim['member_number']); ?>",
                            deceased_name: "<?php echo htmlspecialchars($claim['deceased_name']); ?>",
                            relationship: "<?php echo ucfirst($claim['relationship_to_deceased'] ?? 'N/A'); ?>",
                            date_of_death: "<?php echo !empty($claim['date_of_death']) ? date('F d, Y', strtotime($claim['date_of_death'])) : 'N/A'; ?>",
                            place_of_death: "<?php echo htmlspecialchars($claim['place_of_death'] ?? 'N/A'); ?>",
                            cause_of_death: "<?php echo htmlspecialchars($claim['cause_of_death'] ?? 'N/A'); ?>",
                            claim_amount: "KES <?php echo number_format($claim['claim_amount'], 2); ?>",
                            status: "<?php echo ucfirst($claim['status']); ?>",
                            submitted_at: "<?php echo date('F d, Y', strtotime($claim['submitted_at'] ?? $claim['created_at'])); ?>",
                            notes: "<?php echo htmlspecialchars($claim['admin_notes'] ?? 'No notes'); ?>"
                        };
                    </script>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="bi bi-file-medical" style="font-size: 4rem; color: var(--light-grey); margin-bottom: 1rem;"></i>
            <h3 style="color: var(--medium-grey); margin-bottom: 0.5rem;">No Claims Found</h3>
            <p style="color: var(--medium-grey);">
                <?php echo !empty($search) ? 'Try adjusting your search criteria.' : 'Claims will appear here once submitted by members.'; ?>
            </p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Claim Details Modal -->
<div class="modal" id="claimModal">
    <div class="modal-content" style="max-width: 900px;">
        <div class="modal-header">
            <h3 id="modalClaimId" style="margin: 0;"></h3>
            <button class="modal-close" onclick="closeModal('claimModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <!-- Member & Claim Information -->
                <div>
                    <h4 style="color: var(--secondary-violet); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="bi bi-person-fill"></i> Member Information
                    </h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Member Name:</label>
                            <span id="modalMemberName"></span>
                        </div>
                        <div class="info-item">
                            <label>Member Number:</label>
                            <span id="modalMemberNumber"></span>
                        </div>
                        <div class="info-item">
                            <label>Claim Status:</label>
                            <span id="modalStatus"></span>
                        </div>
                        <div class="info-item">
                            <label>Claim Amount:</label>
                            <span id="modalAmount"></span>
                        </div>
                        <div class="info-item">
                            <label>Submitted Date:</label>
                            <span id="modalSubmitted"></span>
                        </div>
                    </div>
                </div>
                
                <!-- Deceased Information -->
                <div>
                    <h4 style="color: var(--secondary-violet); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="bi bi-file-medical-fill"></i> Deceased Information
                    </h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Deceased Name:</label>
                            <span id="modalDeceasedName"></span>
                        </div>
                        <div class="info-item">
                            <label>Relationship:</label>
                            <span id="modalRelationship"></span>
                        </div>
                        <div class="info-item">
                            <label>Date of Death:</label>
                            <span id="modalDateOfDeath"></span>
                        </div>
                        <div class="info-item">
                            <label>Place of Death:</label>
                            <span id="modalPlaceOfDeath"></span>
                        </div>
                        <div class="info-item">
                            <label>Cause of Death:</label>
                            <span id="modalCauseOfDeath"></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Admin Notes -->
            <div style="margin-top: 2rem;">
                <h4 style="color: var(--secondary-violet); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="bi bi-sticky-fill"></i> Admin Notes
                </h4>
                <div style="background: var(--soft-grey); padding: 1rem; border-radius: var(--radius-md);">
                    <p id="modalNotes" style="margin: 0; color: var(--secondary-violet);"></p>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('claimModal')">Close</button>
        </div>
    </div>
</div>

<!-- New Claim Modal -->
<div class="modal" id="newClaimModal">
    <div class="modal-content" style="max-width: 700px;">
        <div class="modal-header">
            <h3 style="margin: 0;">Submit New Claim</h3>
            <button class="modal-close" onclick="closeModal('newClaimModal')">&times;</button>
        </div>
        <form method="POST" action="/admin/claim/create">
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                
                <div class="form-group">
                    <label class="form-label" for="member_id">Select Member</label>
                    <select id="member_id" name="member_id" class="form-select" required>
                        <option value="">Choose a member...</option>
                        <?php if (!empty($all_members)): ?>
                            <?php foreach ($all_members as $member): ?>
                            <option value="<?php echo $member['id']; ?>">
                                <?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name'] . ' (' . $member['member_number'] . ')'); ?>
                            </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="deceased_name">Deceased Name</label>
                    <input type="text" id="deceased_name" name="deceased_name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="relationship_to_deceased">Relationship</label>
                    <select id="relationship_to_deceased" name="relationship_to_deceased" class="form-select" required>
                        <option value="">Select relationship...</option>
                        <option value="self">Self</option>
                        <option value="spouse">Spouse</option>
                        <option value="parent">Parent</option>
                        <option value="child">Child</option>
                        <option value="sibling">Sibling</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="date_of_death">Date of Death</label>
                    <input type="date" id="date_of_death" name="date_of_death" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="claim_amount">Claim Amount (KES)</label>
                    <input type="number" id="claim_amount" name="claim_amount" class="form-control" min="0" step="0.01" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('newClaimModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle-fill"></i> Submit Claim
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function viewClaim(claimId) {
    const claim = window.claimData[claimId];
    if (!claim) return;
    
    document.getElementById('modalClaimId').textContent = claim.claim_id;
    document.getElementById('modalMemberName').textContent = claim.member_name;
    document.getElementById('modalMemberNumber').textContent = claim.member_number;
    document.getElementById('modalStatus').textContent = claim.status;
    document.getElementById('modalAmount').textContent = claim.claim_amount;
    document.getElementById('modalSubmitted').textContent = claim.submitted_at;
    document.getElementById('modalDeceasedName').textContent = claim.deceased_name;
    document.getElementById('modalRelationship').textContent = claim.relationship;
    document.getElementById('modalDateOfDeath').textContent = claim.date_of_death;
    document.getElementById('modalPlaceOfDeath').textContent = claim.place_of_death;
    document.getElementById('modalCauseOfDeath').textContent = claim.cause_of_death;
    document.getElementById('modalNotes').textContent = claim.notes;
    
    openModal('claimModal');
}
</script>

<?php include VIEWS_PATH . '/layouts/dashboard-footer.php'; ?>
