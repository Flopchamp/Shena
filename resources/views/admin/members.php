<?php
$page = 'members';
$pageTitle = 'Members Management';
$pageSubtitle = 'Manage member registrations, status, and information';
include VIEWS_PATH . '/layouts/dashboard-header.php';
?>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--gradient-primary);">
            <i class="bi bi-people-fill"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value"><?php echo number_format($total_members ?? 0); ?></div>
            <div class="stat-label">Total Members</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--gradient-success);">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value"><?php echo number_format($active_members ?? 0); ?></div>
            <div class="stat-label">Active Members</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--gradient-warning);">
            <i class="bi bi-clock-fill"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value"><?php echo number_format($pending_members ?? 0); ?></div>
            <div class="stat-label">Pending Approval</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--gradient-danger);">
            <i class="bi bi-pause-circle-fill"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value"><?php echo number_format($inactive_members ?? 0); ?></div>
            <div class="stat-label">Inactive</div>
        </div>
    </div>
</div>

<!-- Search and Filter Card -->
<div class="card" style="margin-top: 2rem;">
    <div class="card-header">
        <h4 style="margin: 0;"><i class="bi bi-search"></i> Search & Filter</h4>
    </div>
    <div class="card-body">
        <form method="GET" action="/admin/members" style="display: grid; grid-template-columns: 2fr 1fr auto auto; gap: 1rem; align-items: end;">
            <div class="form-group" style="margin: 0;">
                <label class="form-label" for="search">Search Members</label>
                <input type="text" 
                       id="search" 
                       name="search" 
                       class="form-control" 
                       placeholder="Name, email, or member number" 
                       value="<?php echo htmlspecialchars($search ?? ''); ?>">
            </div>
            
            <div class="form-group" style="margin: 0;">
                <label class="form-label" for="status">Status</label>
                <select id="status" name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" <?php echo ($status ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="pending" <?php echo ($status ?? '') === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="inactive" <?php echo ($status ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    <option value="suspended" <?php echo ($status ?? '') === 'suspended' ? 'selected' : ''; ?>>Suspended</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i> Search
            </button>
            
            <a href="/admin/members" class="btn btn-outline">
                <i class="bi bi-x-circle"></i> Clear
            </a>
        </form>
    </div>
</div>

<!-- Members Table -->
<div class="card" style="margin-top: 2rem;">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h4 style="margin: 0;"><i class="bi bi-table"></i> Members List</h4>
            <button class="btn btn-success btn-sm" onclick="window.location.href='/admin/export/members'">
                <i class="bi bi-download"></i> Export CSV
            </button>
        </div>
    </div>
    <div class="card-body">
        <?php if (!empty($members)): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Member #</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Package</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($members as $member): ?>
                    <tr>
                        <td>
                            <span style="font-family: var(--font-mono); font-weight: 600; color: var(--primary-purple);">
                                <?php echo htmlspecialchars($member['member_number'] ?? $member['member_id']); ?>
                            </span>
                        </td>
                        <td>
                            <div>
                                <div style="font-weight: 600; color: var(--secondary-violet);">
                                    <?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?>
                                </div>
                                <?php if (!empty($member['id_number'])): ?>
                                <div style="font-size: 0.75rem; color: var(--medium-grey);">
                                    ID: <?php echo htmlspecialchars($member['id_number']); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div style="font-size: 0.875rem;">
                                <div><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($member['email']); ?></div>
                                <div style="color: var(--medium-grey);"><i class="bi bi-phone"></i> <?php echo htmlspecialchars($member['phone']); ?></div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-info">
                                <?php echo ucfirst($member['package'] ?? 'Individual'); ?>
                            </span>
                        </td>
                        <td>
                            <?php
                            $statusClass = match($member['status']) {
                                'active' => 'badge-success',
                                'pending' => 'badge-warning',
                                'inactive' => 'badge-secondary',
                                'suspended' => 'badge-danger',
                                default => 'badge-secondary'
                            };
                            ?>
                            <span class="badge <?php echo $statusClass; ?>">
                                <?php echo ucfirst($member['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($member['registration_date'] ?? $member['created_at'])); ?></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-info" 
                                        onclick="viewMember(<?php echo $member['id']; ?>)"
                                        title="View Details">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                                
                                <?php if ($member['status'] === 'pending'): ?>
                                <form method="POST" action="/admin/member/activate" style="display: inline;" onsubmit="return confirm('Activate this member?')">
                                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                                    <input type="hidden" name="member_id" value="<?php echo $member['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-success" title="Activate">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </button>
                                </form>
                                <?php elseif ($member['status'] === 'active'): ?>
                                <form method="POST" action="/admin/member/suspend" style="display: inline;" onsubmit="return confirm('Suspend this member?')">
                                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                                    <input type="hidden" name="member_id" value="<?php echo $member['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-warning" title="Suspend">
                                        <i class="bi bi-pause-circle-fill"></i>
                                    </button>
                                </form>
                                <?php else: ?>
                                <form method="POST" action="/admin/member/activate" style="display: inline;" onsubmit="return confirm('Activate this member?')">
                                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                                    <input type="hidden" name="member_id" value="<?php echo $member['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-success" title="Activate">
                                        <i class="bi bi-play-circle-fill"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Hidden details for modal (stored as data attribute) -->
                    <script>
                        window.memberData = window.memberData || {};
                        window.memberData[<?php echo $member['id']; ?>] = {
                            member_number: "<?php echo htmlspecialchars($member['member_number'] ?? $member['member_id']); ?>",
                            name: "<?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?>",
                            email: "<?php echo htmlspecialchars($member['email']); ?>",
                            phone: "<?php echo htmlspecialchars($member['phone']); ?>",
                            id_number: "<?php echo htmlspecialchars($member['id_number'] ?? 'N/A'); ?>",
                            date_of_birth: "<?php echo !empty($member['date_of_birth']) ? date('M d, Y', strtotime($member['date_of_birth'])) : 'N/A'; ?>",
                            address: "<?php echo htmlspecialchars($member['address'] ?? 'N/A'); ?>",
                            package: "<?php echo ucfirst($member['package'] ?? 'Individual'); ?>",
                            status: "<?php echo ucfirst($member['status']); ?>",
                            monthly_contribution: "KES <?php echo number_format($member['monthly_contribution'] ?? 500, 2); ?>",
                            registration_date: "<?php echo date('F d, Y', strtotime($member['registration_date'] ?? $member['created_at'])); ?>",
                            next_of_kin: "<?php echo htmlspecialchars($member['next_of_kin'] ?? 'N/A'); ?>",
                            next_of_kin_phone: "<?php echo htmlspecialchars($member['next_of_kin_phone'] ?? 'N/A'); ?>",
                            next_of_kin_relationship: "<?php echo htmlspecialchars($member['next_of_kin_relationship'] ?? 'N/A'); ?>"
                        };
                    </script>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="bi bi-people" style="font-size: 4rem; color: var(--light-grey); margin-bottom: 1rem;"></i>
            <h3 style="color: var(--medium-grey); margin-bottom: 0.5rem;">No Members Found</h3>
            <p style="color: var(--medium-grey);">
                <?php echo !empty($search) ? 'Try adjusting your search criteria.' : 'Members will appear here once they register.'; ?>
            </p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Member Details Modal -->
<div class="modal" id="memberModal">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h3 id="modalMemberName" style="margin: 0;"></h3>
            <button class="modal-close" onclick="closeModal('memberModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <!-- Personal Information -->
                <div>
                    <h4 style="color: var(--secondary-violet); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="bi bi-person-fill"></i> Personal Information
                    </h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Member Number:</label>
                            <span id="modalMemberNumber"></span>
                        </div>
                        <div class="info-item">
                            <label>Full Name:</label>
                            <span id="modalFullName"></span>
                        </div>
                        <div class="info-item">
                            <label>Email:</label>
                            <span id="modalEmail"></span>
                        </div>
                        <div class="info-item">
                            <label>Phone:</label>
                            <span id="modalPhone"></span>
                        </div>
                        <div class="info-item">
                            <label>ID Number:</label>
                            <span id="modalIdNumber"></span>
                        </div>
                        <div class="info-item">
                            <label>Date of Birth:</label>
                            <span id="modalDob"></span>
                        </div>
                        <div class="info-item">
                            <label>Address:</label>
                            <span id="modalAddress"></span>
                        </div>
                    </div>
                </div>
                
                <!-- Membership Information -->
                <div>
                    <h4 style="color: var(--secondary-violet); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="bi bi-card-checklist"></i> Membership Details
                    </h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Package:</label>
                            <span id="modalPackage"></span>
                        </div>
                        <div class="info-item">
                            <label>Status:</label>
                            <span id="modalStatus"></span>
                        </div>
                        <div class="info-item">
                            <label>Monthly Contribution:</label>
                            <span id="modalContribution"></span>
                        </div>
                        <div class="info-item">
                            <label>Registration Date:</label>
                            <span id="modalRegDate"></span>
                        </div>
                    </div>
                    
                    <h4 style="color: var(--secondary-violet); margin: 1.5rem 0 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="bi bi-people-fill"></i> Next of Kin
                    </h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Name:</label>
                            <span id="modalNokName"></span>
                        </div>
                        <div class="info-item">
                            <label>Phone:</label>
                            <span id="modalNokPhone"></span>
                        </div>
                        <div class="info-item">
                            <label>Relationship:</label>
                            <span id="modalNokRelation"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('memberModal')">Close</button>
        </div>
    </div>
</div>

<style>
.info-grid {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.info-item label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--medium-grey);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item span {
    font-size: 0.9375rem;
    color: var(--secondary-violet);
}
</style>

<script>
function viewMember(memberId) {
    const member = window.memberData[memberId];
    if (!member) return;
    
    document.getElementById('modalMemberName').textContent = member.name;
    document.getElementById('modalMemberNumber').textContent = member.member_number;
    document.getElementById('modalFullName').textContent = member.name;
    document.getElementById('modalEmail').textContent = member.email;
    document.getElementById('modalPhone').textContent = member.phone;
    document.getElementById('modalIdNumber').textContent = member.id_number;
    document.getElementById('modalDob').textContent = member.date_of_birth;
    document.getElementById('modalAddress').textContent = member.address;
    document.getElementById('modalPackage').textContent = member.package;
    document.getElementById('modalStatus').textContent = member.status;
    document.getElementById('modalContribution').textContent = member.monthly_contribution;
    document.getElementById('modalRegDate').textContent = member.registration_date;
    document.getElementById('modalNokName').textContent = member.next_of_kin;
    document.getElementById('modalNokPhone').textContent = member.next_of_kin_phone;
    document.getElementById('modalNokRelation').textContent = member.next_of_kin_relationship;
    
    openModal('memberModal');
}
</script>

<?php include VIEWS_PATH . '/layouts/dashboard-footer.php'; ?>
