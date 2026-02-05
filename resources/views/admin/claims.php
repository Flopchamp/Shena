<?php
// Mock data - will be from controller
$pendingClaims = $pendingClaims ?? 3;
$processedClaims = $processedClaims ?? 87;
$approvedClaims = $approvedClaims ?? 82;
$rejectedClaims = $rejectedClaims ?? 5;
$totalClaimAmount = $totalClaimAmount ?? 8500000;
?>
<?php include_once __DIR__ . '/../layouts/admin-header.php'; ?>

<style>
    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 4px;
    }

    .page-subtitle {
        font-size: 14px;
        color: #9CA3AF;
    }

    /* Alert Banner */
    .alert-banner {
        background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
        border-radius: 12px;
        padding: 20px 24px;
        margin-bottom: 24px;
        display: <?php echo $pendingClaims > 0 ? 'flex' : 'none'; ?>;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.02); }
    }

    .alert-content {
        display: flex;
        align-items: center;
        gap: 16px;
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

    .alert-text p {
        color: rgba(255, 255, 255, 0.95);
        font-size: 13px;
        margin: 0;
    }

    .btn-alert-action {
        padding: 12px 24px;
        background: white;
        color: #DC2626;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-alert-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap:20px;
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
        margin-bottom: 16px;
    }

    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .stat-icon.red {
        background: linear-gradient(135deg, #FEE2E2 0%, #FECACA 100%);
        color: #EF4444;
    }

    .stat-icon.green {
        background: linear-gradient(135deg, #D1FAE5 0%, #A7F3D0 100%);
        color: #10B981;
    }

    .stat-icon.orange {
        background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
        color: #F59E0B;
    }

    .stat-icon.purple {
        background: linear-gradient(135deg, #EDE9FE 0%, #DDD6FE 100%);
        color: #7F3D9E;
    }

    .stat-label {
        font-size: 13px;
        color: #9CA3AF;
        font-weight: 500;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 4px;
    }

    .stat-subtext {
        font-size: 12px;
        color: #6B7280;
    }

    /* Tabs */
    .tabs-container {
        background: white;
        border-radius: 12px;
        border: 1px solid #E5E7EB;
        overflow: hidden;
    }

    .tabs-header {
        display: flex;
        border-bottom: 1px solid #E5E7EB;
        padding: 0;
        background: #F9FAFB;
    }

    .tab-btn {
        padding: 16px 24px;
        border: none;
        background: transparent;
        color: #6B7280;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border-bottom: 2px solid transparent;
        position: relative;
    }

    .tab-btn:hover {
        color: #7F3D9E;
        background: rgba(127, 61, 158, 0.05);
    }

    .tab-btn.active {
        color: #7F3D9E;
        background: white;
        border-bottom-color: #7F3D9E;
    }

    .tab-badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        margin-left: 8px;
    }

    .tab-badge.red {
        background: #FEE2E2;
        color: #DC2626;
    }

    .tab-badge.green {
        background: #D1FAE5;
        color: #059669;
    }

    .tab-content {
        display: none;
        padding: 24px;
    }

    .tab-content.active {
        display: block;
    }

    /* Table Styles */
    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .search-box {
        position: relative;
        width: 300px;
    }

    .search-box input {
        width: 100%;
        padding: 10px 16px 10px 40px;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        font-size: 14px;
    }

    .search-box i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9CA3AF;
    }

    .filter-group {
        display: flex;
        gap: 12px;
    }

    .filter-btn {
        padding: 10px 16px;
        border: 1px solid #E5E7EB;
        background: white;
        color: #6B7280;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .filter-btn:hover {
        border-color: #7F3D9E;
        color: #7F3D9E;
    }

    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .custom-table thead {
        background: #F9FAFB;
    }

    .custom-table th {
        padding: 12px 16px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #E5E7EB;
    }

    .custom-table td {
        padding: 16px;
        border-bottom: 1px solid #F3F4F6;
        font-size: 14px;
        color: #1F2937;
    }

    .custom-table tbody tr {
        transition: background 0.2s;
    }

    .custom-table tbody tr:hover {
        background: #F9FAFB;
    }

    /* Status Badges */
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.pending {
        background: #FEF3C7;
        color: #F59E0B;
    }

    .status-badge.approved {
        background: #D1FAE5;
        color: #059669;
    }

    .status-badge.rejected {
        background: #FEE2E2;
        color: #DC2626;
    }

    .status-badge.processing {
        background: #DBEAFE;
        color: #3B82F6;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-view {
        background: #EDE9FE;
        color: #7F3D9E;
    }

    .btn-view:hover {
        background: #DDD6FE;
    }

    .btn-approve {
        background: #D1FAE5;
        color: #059669;
    }

    .btn-approve:hover {
        background: #A7F3D0;
    }

    .btn-reject {
        background: #FEE2E2;
        color: #DC2626;
    }

    .btn-reject:hover {
        background: #FECACA;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        background: #F3F4F6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 32px;
        color: #9CA3AF;
    }

    .empty-title {
        font-size: 18px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 8px;
    }

    .empty-text {
        font-size: 14px;
        color: #9CA3AF;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .tabs-header {
            overflow-x: auto;
        }

        .table-header {
            flex-direction: column;
            gap: 12px;
        }

        .search-box {
            width: 100%;
        }

        .custom-table {
            font-size: 12px;
        }

        .custom-table td {
            padding: 12px;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Claims Management</h1>
        <p class="page-subtitle">Process and manage all member claims</p>
    </div>
</div>

<!-- Alert for Unprocessed Claims -->
<div class="alert-banner">
    <div class="alert-content">
        <div class="alert-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="alert-text">
            <h4>Urgent: Pending Claims Require Attention</h4>
            <p><strong><?php echo $pendingClaims; ?> claim<?php echo $pendingClaims > 1 ? 's are' : ' is'; ?></strong> awaiting your review and processing</p>
        </div>
    </div>
    <button class="btn-alert-action" onclick="showTab('pending')">
        Process Now
    </button>
</div>

<!-- Claims Analytics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon red">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <span class="stat-label">Pending Claims</span>
        </div>
        <div class="stat-value"><?php echo $pendingClaims; ?></div>
        <div class="stat-subtext">Awaiting processing</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
            <span class="stat-label">Approved Claims</span>
        </div>
        <div class="stat-value"><?php echo $approvedClaims; ?></div>
        <div class="stat-subtext">Successfully processed</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon orange">
                <i class="fas fa-times-circle"></i>
            </div>
            <span class="stat-label">Rejected Claims</span>
        </div>
        <div class="stat-value"><?php echo $rejectedClaims; ?></div>
        <div class="stat-subtext">Did not meet criteria</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon purple">
                <i class="fas fa-coins"></i>
            </div>
            <span class="stat-label">Total Amount</span>
        </div>
        <div class="stat-value">KSh <?php echo number_format($totalClaimAmount); ?></div>
        <div class="stat-subtext">Paid out to date</div>
    </div>
</div>

<!-- Claims Tabs -->
<div class="tabs-container">
    <div class="tabs-header">
        <button class="tab-btn active" onclick="showTab('pending')" id="tab-pending">
            <i class="fas fa-hourglass-half"></i>
            Pending Claims
            <span class="tab-badge red"><?php echo $pendingClaims; ?></span>
        </button>
        <button class="tab-btn" onclick="showTab('all')" id="tab-all">
            <i class="fas fa-list"></i>
            All Claims
        </button>
        <button class="tab-btn" onclick="showTab('completed')" id="tab-completed">
            <i class="fas fa-check-circle"></i>
            Completed Claims
            <span class="tab-badge green"><?php echo $approvedClaims; ?></span>
        </button>
    </div>

    <!-- Pending Claims Tab -->
    <div class="tab-content active" id="content-pending">
        <div class="table-header">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search claims by member name or ID...">
            </div>
            <div class="filter-group">
                <button class="filter-btn">
                    <i class="fas fa-filter"></i>
                    Filter
                </button>
                <button class="filter-btn">
                    <i class="fas fa-download"></i>
                    Export
                </button>
            </div>
        </div>

        <table class="custom-table">
            <thead>
                <tr>
                    <th>Claim ID</th>
                    <th>Member Name</th>
                    <th>Deceased Name</th>
                    <th>Plan</th>
                    <th>Amount</th>
                    <th>Date Submitted</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Sample pending claim -->
                <tr>
                    <td><strong>#CLM-2026-001</strong></td>
                    <td>John Doe</td>
                    <td>Jane Doe</td>
                    <td>Plan A</td>
                    <td><strong>KSh 100,000</strong></td>
                    <td>Feb 5, 2026</td>
                    <td><span class="status-badge pending">Pending</span></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-action btn-view">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                            <button class="btn-action btn-approve">
                                <i class="fas fa-check"></i>
                                Approve
                            </button>
                            <button class="btn-action btn-reject">
                                <i class="fas fa-times"></i>
                                Reject
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- All Claims Tab -->
    <div class="tab-content" id="content-all">
        <div class="table-header">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search all claims...">
            </div>
            <div class="filter-group">
                <button class="filter-btn">
                    <i class="fas fa-filter"></i>
                    Filter
                </button>
                <button class="filter-btn">
                    <i class="fas fa-download"></i>
                    Export
                </button>
            </div>
        </div>

        <table class="custom-table">
            <thead>
                <tr>
                    <th>Claim ID</th>
                    <th>Member Name</th>
                    <th>Deceased Name</th>
                    <th>Plan</th>
                    <th>Amount</th>
                    <th>Date Submitted</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Sample claims -->
                <tr>
                    <td><strong>#CLM-2026-001</strong></td>
                    <td>John Doe</td>
                    <td>Jane Doe</td>
                    <td>Plan A</td>
                    <td><strong>KSh 100,000</strong></td>
                    <td>Feb 5, 2026</td>
                    <td><span class="status-badge pending">Pending</span></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-action btn-view">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>#CLM-2026-002</strong></td>
                    <td>Mary Smith</td>
                    <td>Peter Smith</td>
                    <td>Plan B</td>
                    <td><strong>KSh 200,000</strong></td>
                    <td>Feb 3, 2026</td>
                    <td><span class="status-badge approved">Approved</span></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-action btn-view">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Completed Claims Tab -->
    <div class="tab-content" id="content-completed">
        <div class="table-header">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search completed claims...">
            </div>
            <div class="filter-group">
                <button class="filter-btn">
                    <i class="fas fa-filter"></i>
                    Filter
                </button>
                <button class="filter-btn">
                    <i class="fas fa-download"></i>
                    Export PDF
                </button>
            </div>
        </div>

        <table class="custom-table">
            <thead>
                <tr>
                    <th>Claim ID</th>
                    <th>Member Name</th>
                    <th>Deceased Name</th>
                    <th>Plan</th>
                    <th>Amount Paid</th>
                    <th>Date Completed</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>#CLM-2026-002</strong></td>
                    <td>Mary Smith</td>
                    <td>Peter Smith</td>
                    <td>Plan B</td>
                    <td><strong>KSh 200,000</strong></td>
                    <td>Feb 4, 2026</td>
                    <td><span class="status-badge approved">Approved</span></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-action btn-view">
                                <i class="fas fa-eye"></i>
                                View Details
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });

    // Show selected tab
    document.getElementById('content-' + tabName).classList.add('active');
    document.getElementById('tab-' + tabName).classList.add('active');
}
</script>

<?php include_once __DIR__ . '/../layouts/admin-footer.php'; ?>
