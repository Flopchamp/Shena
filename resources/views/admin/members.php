<?php include_once __DIR__ . '/../layouts/admin-header.php'; ?>

<style>
    /* Page Header */
    .page-header {
        margin-bottom: 24px;
    }

    .page-title {
        font-family: 'Playfair Display', serif;
        font-size: 28px;
        font-weight: 700;
        color: #1F2937;
        margin: 0 0 4px 0;
    }

    .page-subtitle {
        font-size: 13px;
        color: #9CA3AF;
        margin: 0;
    }

    /* Emergency Alert Banner */
    .emergency-alert {
        background: linear-gradient(135deg, #DC2626 0%, #EF4444 100%);
        border-radius: 12px;
        padding: 20px 24px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
    }

    .alert-content {
        display: flex;
        align-items: center;
        gap: 16px;
        flex: 1;
    }

    .alert-icon {
        width: 48px;
        height: 48px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
    }

    .alert-text h4 {
        color: white;
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .alert-badge {
        background: white;
        color: #DC2626;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
        margin-right: 8px;
    }

    .alert-text p {
        color: rgba(255, 255, 255, 0.95);
        font-size: 13px;
        margin: 0;
    }

    .alert-text strong {
        color: white;
        font-weight: 700;
    }

    .btn-process-claim {
        padding: 12px 24px;
        background: white;
        color: #DC2626;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .btn-process-claim:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Stats Grid */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #E5E7EB;
        transition: all 0.2s;
    }

    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }

    .stat-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .stat-icon.purple {
        background: #EDE9FE;
        color: #8B5CF6;
    }

    .stat-icon.pink {
        background: #FCE7F3;
        color: #EC4899;
    }

    .stat-icon.red {
        background: #FEE2E2;
        color: #EF4444;
    }

    .stat-label {
        font-size: 11px;
        color: #9CA3AF;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #1F2937;
    }

    /* Main Content Layout */
    .content-layout {
        display: grid;
        grid-template-columns: 1.8fr 1fr;
        gap: 24px;
        margin-bottom: 30px;
    }

    /* Directory Card */
    .directory-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #E5E7EB;
    }

    .directory-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .directory-title {
        font-size: 18px;
        font-weight: 700;
        color: #1F2937;
    }

    .directory-actions {
        display: flex;
        gap: 12px;
    }

    .btn-export {
        padding: 8px 16px;
        background: white;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        color: #6B7280;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .btn-export:hover {
        border-color: #8B5CF6;
        color: #8B5CF6;
    }

    .btn-new-registration {
        padding: 8px 16px;
        background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
        border: none;
        border-radius: 8px;
        color: white;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .btn-new-registration:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    /* Members Table */
    .members-table {
        width: 100%;
        border-collapse: collapse;
    }

    .members-table thead {
        border-bottom: 1px solid #E5E7EB;
    }

    .members-table th {
        text-align: left;
        padding: 12px 16px;
        font-size: 11px;
        font-weight: 700;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .members-table td {
        padding: 16px;
        font-size: 13px;
        color: #1F2937;
        border-bottom: 1px solid #F3F4F6;
    }

    .members-table tbody tr:hover {
        background: #F9FAFB;
    }

    .member-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .member-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        color: white;
        flex-shrink: 0;
    }

    .member-avatar.purple {
        background: linear-gradient(135deg, #A78BFA 0%, #8B5CF6 100%);
    }

    .member-avatar.pink {
        background: linear-gradient(135deg, #F9A8D4 0%, #EC4899 100%);
    }

    .member-avatar.red {
        background: linear-gradient(135deg, #FCA5A5 0%, #EF4444 100%);
    }

    .member-details {
        flex: 1;
    }

    .member-name {
        font-weight: 600;
        color: #1F2937;
        margin-bottom: 2px;
    }

    .member-role {
        font-size: 11px;
        color: #9CA3AF;
    }

    .package-badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        background: #F3F4F6;
        color: #6B7280;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-badge.active {
        background: #D1FAE5;
        color: #10B981;
    }

    .status-badge.grace {
        background: #EDE9FE;
        color: #8B5CF6;
    }

    .status-badge.default {
        background: #FEE2E2;
        color: #EF4444;
    }

    .contribution-info {
        display: flex;
        flex-direction: column;
    }

    .contribution-amount {
        font-weight: 600;
        color: #1F2937;
    }

    .contribution-date {
        font-size: 11px;
        color: #9CA3AF;
    }

    .contribution-overdue {
        color: #EF4444;
        font-size: 11px;
    }

    .contribution-inactive {
        color: #9CA3AF;
        font-size: 13px;
    }

    .table-pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        font-size: 13px;
        color: #6B7280;
    }

    .pagination-buttons {
        display: flex;
        gap: 8px;
    }

    .pagination-btn {
        padding: 8px 12px;
        background: white;
        border: 1px solid #E5E7EB;
        border-radius: 6px;
        color: #6B7280;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .pagination-btn:hover:not(:disabled) {
        border-color: #8B5CF6;
        color: #8B5CF6;
    }

    .pagination-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Pending Approvals */
    .approvals-section {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .approval-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #E5E7EB;
    }

    .approval-header {
        font-size: 16px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 16px;
    }

    .approval-item {
        padding: 16px;
        background: #F9FAFB;
        border-radius: 10px;
        margin-bottom: 12px;
    }

    .approval-item:last-child {
        margin-bottom: 0;
    }

    .approval-name {
        font-size: 14px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 4px;
    }

    .approval-details {
        font-size: 12px;
        color: #6B7280;
        margin-bottom: 8px;
    }

    .approval-tag {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .approval-tag.awaiting {
        background: #FEF3C7;
        color: #F59E0B;
    }

    .approval-tag.new {
        background: #FEE2E2;
        color: #EF4444;
    }

    .approval-reference {
        font-size: 11px;
        color: #9CA3AF;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .approval-code {
        font-family: monospace;
        font-size: 14px;
        font-weight: 600;
        color: #1F2937;
        margin-bottom: 12px;
    }

    .approval-input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #E5E7EB;
        border-radius: 6px;
        font-size: 13px;
        margin-bottom: 12px;
    }

    .approval-actions {
        display: flex;
        gap: 8px;
    }

    .btn-approve {
        flex: 1;
        padding: 8px 16px;
        background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
        border: none;
        border-radius: 6px;
        color: white;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-approve:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    .btn-close {
        width: 32px;
        height: 32px;
        background: transparent;
        border: 1px solid #E5E7EB;
        border-radius: 6px;
        color: #9CA3AF;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .btn-close:hover {
        background: #F3F4F6;
        color: #6B7280;
    }

    .payroll-info {
        font-size: 13px;
        color: #6B7280;
        margin-bottom: 4px;
    }

    .system-footer {
        text-align: right;
        font-size: 11px;
        color: #9CA3AF;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #F3F4F6;
    }

    .system-label {
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .system-id {
        font-family: monospace;
        font-weight: 600;
        color: #1F2937;
    }

    @media (max-width: 1200px) {
        .content-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .stats-row {
            grid-template-columns: 1fr;
        }

        .emergency-alert {
            flex-direction: column;
            gap: 16px;
        }

        .btn-process-claim {
            width: 100%;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">Member Management</h1>
    <p class="page-subtitle">Oversee registration and verify member contributions.</p>
</div>

<!-- Emergency Alert Banner -->
<div class="emergency-alert">
    <div class="alert-content">
        <div class="alert-icon">
            <i class="fas fa-bell"></i>
        </div>
        <div class="alert-text">
            <h4>
                <span class="alert-badge">EMERGENCY ALERT</span>
                Death Notification Received
            </h4>
            <p>
                Member <strong>John Doe (ID: #34592)</strong> reported. Action required for immediate last-respect services.
            </p>
        </div>
    </div>
    <button class="btn-process-claim">PROCESS CLAIM</button>
</div>

<!-- Statistics Cards -->
<div class="stats-row">
    <!-- Total Members -->
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon purple">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-label">Total Members</div>
        <div class="stat-value"><?php echo number_format($stats['total_members'] ?? 12450); ?></div>
    </div>

    <!-- Grace Period -->
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon pink">
                <i class="fas fa-hourglass-half"></i>
            </div>
        </div>
        <div class="stat-label">Grace Period</div>
        <div class="stat-value"><?php echo number_format($stats['grace_period'] ?? 284); ?></div>
    </div>

    <!-- Default Rate -->
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon red">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
        <div class="stat-label">Default Rate</div>
        <div class="stat-value"><?php echo number_format($stats['default_rate'] ?? 1.2, 1); ?>%</div>
    </div>
</div>

<!-- Main Content Layout -->
<div class="content-layout">
    <!-- Left Column: Directory Table -->
    <div>
        <div class="directory-card">
            <div class="directory-header">
                <div class="directory-title">Comprehensive Directory</div>
                <div class="directory-actions">
                    <button class="btn-export">
                        <i class="fas fa-download"></i>
                        Export CSV
                    </button>
                    <button class="btn-new-registration">
                        <i class="fas fa-user-plus"></i>
                        New Registration
                    </button>
                </div>
            </div>

            <table class="members-table">
                <thead>
                    <tr>
                        <th>MEMBER NAME</th>
                        <th>NATIONAL ID</th>
                        <th>PACKAGE</th>
                        <th>STATUS</th>
                        <th>LAST CONTRIBUTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sample_members = [
                        ['name' => 'Alice Mwangi', 'avatar' => 'AM', 'color' => 'purple', 'id' => '32456781', 'package' => 'Gold Plan', 'status' => 'active', 'contribution' => 'KES 1,500', 'date' => '12 Oct 2023'],
                        ['name' => 'Peter Kamau', 'avatar' => 'PK', 'color' => 'pink', 'id' => '28491032', 'package' => 'Standard', 'status' => 'grace', 'contribution' => 'Overdue', 'date' => 'Expired 5 Oct'],
                        ['name' => 'Samuel Otieno', 'avatar' => 'SO', 'color' => 'red', 'id' => '30129485', 'package' => 'Premium', 'status' => 'default', 'contribution' => 'Inactive', 'date' => '']
                    ];
                    
                    foreach ($sample_members as $member): 
                    ?>
                    <tr>
                        <td>
                            <div class="member-info">
                                <div class="member-avatar <?php echo $member['color']; ?>">
                                    <?php echo $member['avatar']; ?>
                                </div>
                                <div class="member-details">
                                    <div class="member-name"><?php echo $member['name']; ?></div>
                                </div>
                            </div>
                        </td>
                        <td><?php echo $member['id']; ?></td>
                        <td>
                            <span class="package-badge"><?php echo $member['package']; ?></span>
                        </td>
                        <td>
                            <span class="status-badge <?php echo $member['status']; ?>">
                                <?php echo strtoupper($member['status']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($member['status'] === 'grace'): ?>
                                <div class="contribution-info">
                                    <span class="contribution-overdue"><?php echo $member['contribution']; ?></span>
                                    <span class="contribution-overdue"><?php echo $member['date']; ?></span>
                                </div>
                            <?php elseif ($member['status'] === 'default'): ?>
                                <span class="contribution-inactive"><?php echo $member['contribution']; ?></span>
                            <?php else: ?>
                                <div class="contribution-info">
                                    <span class="contribution-amount"><?php echo $member['contribution']; ?></span>
                                    <span class="contribution-date"><?php echo $member['date']; ?></span>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="table-pagination">
                <div>VIEWING 1-3 OF 12,450 MEMBERS</div>
                <div class="pagination-buttons">
                    <button class="pagination-btn" disabled>Previous</button>
                    <button class="pagination-btn">Next</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Pending Approvals -->
    <div class="approvals-section">
        <!-- Pending Approvals Card -->
        <div class="approval-card">
            <div class="approval-header">Pending Approvals</div>
            
            <div class="approval-item">
                <div class="payroll-info">Verify M-Pesa Payroll</div>
                <div class="payroll-info">4163987</div>
            </div>
        </div>

        <!-- Grace Muli Approval -->
        <div class="approval-card">
            <div class="approval-name">Grace Muli</div>
            <div class="approval-details">Standard Plan</div>
            <div class="approval-tag awaiting">AWAITING CODE</div>
            <div class="approval-reference">M-PESA REFERENCE</div>
            <div class="approval-code">RHU2LBX6AA</div>
            <div class="approval-actions">
                <button class="btn-approve">Verify & Activate</button>
                <button class="btn-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Robert Kinyana Approval -->
        <div class="approval-card">
            <div class="approval-name">Robert Kinyana</div>
            <div class="approval-details">Gold Plan</div>
            <div class="approval-tag new">NEW APP</div>
            <div class="approval-reference">M-PESA REFERENCE</div>
            <input type="text" class="approval-input" placeholder="Enter Ref Code">
            <div class="approval-actions">
                <button class="btn-approve">Validate Code</button>
                <button class="btn-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- System Footer -->
        <div class="system-footer">
            <div class="system-label">System Integrated Payroll</div>
            <div class="system-id">4163987</div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/admin-footer.php'; ?>
