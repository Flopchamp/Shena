<?php include_once __DIR__ . '/../layouts/admin-header.php'; ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0"><i class="fas fa-user-tie me-2"></i>Agent Management</h1>
    <div class="header-actions">
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAgentModal">
            <i class="fas fa-user-plus me-2"></i>Add Agent
        </button>
        <button class="btn btn-success btn-sm" onclick="exportAgentData()">
            <i class="fas fa-file-excel me-2"></i>Export Data
        </button>
    </div>
</div>

<!-- Agent Management Tabs -->
<ul class="nav nav-tabs mb-4" id="agentTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics" type="button" role="tab">
            <i class="fas fa-chart-line"></i> Analytics
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="agents-list-tab" data-bs-toggle="tab" data-bs-target="#agentsList" type="button" role="tab">
            <i class="fas fa-list"></i> All Agents
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="commissions-tab" data-bs-toggle="tab" data-bs-target="#commissions" type="button" role="tab">
            <i class="fas fa-money-check-alt"></i> Process Commission
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="resources-tab" data-bs-toggle="tab" data-bs-target="#resources" type="button" role="tab">
            <i class="fas fa-folder-open"></i> Agent Resources
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="agentTabContent">

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

    /* Emergency Alert */
    .emergency-alert {
        background: linear-gradient(135deg, #7F3D9E 0%, #B91C1C 100%);
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 12px rgba(127, 61, 158, 0.2);
    }

    .alert-content {
        display: flex;
        align-items: center;
        gap: 16px;
        flex: 1;
    }

    .alert-icon {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
    }

    .alert-text {
        flex: 1;
    }

    .alert-badge {
        background: rgba(255, 255, 255, 0.9);
        color: #DC2626;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
        display: inline-block;
    }

    .alert-title {
        color: white;
        font-size: 15px;
        font-weight: 700;
        margin: 0;
    }

    .alert-description {
        color: rgba(255, 255, 255, 0.9);
        font-size: 12px;
        margin: 0;
    }

    .alert-button {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .alert-button:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Stats Grid */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
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
        color: #7F3D9E;
    }

    .stat-icon.yellow {
        background: #FEF3C7;
        color: #F59E0B;
    }

    .stat-icon.green {
        background: #D1FAE5;
        color: #10B981;
    }

    .stat-icon.blue {
        background: #DBEAFE;
        color: #3B82F6;
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

    .stat-change {
        font-size: 12px;
        color: #10B981;
        margin-top: 4px;
    }

    .stat-change.down {
        color: #EF4444;
    }

    /* Main Content Layout */
    .content-layout {
        display: grid;
        grid-template-columns: 1.8fr 1fr;
        gap: 24px;
        margin-bottom: 30px;
    }

    /* Regional Growth Card */
    .growth-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #E5E7EB;
    }

    .growth-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .growth-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 18px;
        font-weight: 700;
        color: #1F2937;
    }

    .growth-subtitle {
        font-size: 12px;
        color: #9CA3AF;
        margin-top: 2px;
    }

    .growth-filters {
        display: flex;
        gap: 8px;
    }

    .filter-btn {
        padding: 6px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid #E5E7EB;
        background: white;
        color: #6B7280;
        cursor: pointer;
        transition: all 0.2s;
    }

    .filter-btn.active {
        background: #7F3D9E;
        color: white;
        border-color: #7F3D9E;
    }

    .map-container {
        background: #F9FAFB;
        border-radius: 12px;
        height: 280px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
    }

    .map-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        position: absolute;
        animation: pulse 2s infinite;
    }

    .map-dot.purple {
        background: #7F3D9E;
        top: 45%;
        left: 48%;
    }

    .map-dot.orange {
        background: #F97316;
        top: 60%;
        left: 52%;
    }

    .map-dot.yellow {
        background: #F59E0B;
        top: 30%;
        left: 45%;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.5);
            opacity: 0.5;
        }
    }

    .map-icon {
        font-size: 120px;
        color: #E5E7EB;
    }

    .growth-metrics {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    .metric-item {
        text-align: center;
        padding: 12px;
        background: #F9FAFB;
        border-radius: 8px;
    }

    .metric-label {
        font-size: 10px;
        color: #9CA3AF;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .metric-value {
        font-size: 14px;
        font-weight: 700;
        color: #1F2937;
    }

    /* Payout Queue */
    .payout-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #E5E7EB;
    }

    .payout-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .payout-title {
        font-size: 18px;
        font-weight: 700;
        color: #1F2937;
    }

    .payout-badge {
        background: #FEF3C7;
        color: #F59E0B;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
    }

    .payout-item {
        padding: 16px;
        background: #F9FAFB;
        border-radius: 10px;
        margin-bottom: 12px;
    }

    .payout-agent {
        font-size: 14px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 4px;
    }

    .payout-amount {
        font-size: 12px;
        color: #8B5CF6;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .payout-info {
        font-size: 11px;
        color: #6B7280;
        margin-bottom: 12px;
    }

    .payout-button {
        width: 100%;
        background: #7F3D9E;
        color: white;
        border: none;
        padding: 8px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .payout-button:hover {
        background: #7F3D9E;
    }

    .payout-button.audit {
        background: #F3F4F6;
        color: #6B7280;
    }

    .payout-button.audit:hover {
        background: #E5E7EB;
    }

    /* Agents Table */
    .agents-table-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #E5E7EB;
        margin-bottom: 30px;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .table-title {
        font-size: 18px;
        font-weight: 700;
        color: #1F2937;
    }

    .table-actions {
        display: flex;
        gap: 12px;
    }

    .table-btn {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid #E5E7EB;
        background: white;
        color: #6B7280;
    }

    .table-btn.primary {
        background: #F59E0B;
        color: white;
        border-color: #F59E0B;
    }

    .table-btn:hover {
        background: #F3F4F6;
    }

    .table-btn.primary:hover {
        background: #D97706;
    }

    .agents-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .agents-table thead th {
        background: #7F3D9E;
        color: white;
        padding: 14px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .agents-table thead th:first-child {
        border-radius: 8px 0 0 0;
    }

    .agents-table thead th:last-child {
        border-radius: 0 8px 0 0;
    }

    .agents-table tbody td {
        padding: 16px;
        border-bottom: 1px solid #F3F4F6;
        font-size: 13px;
        color: #1F2937;
    }

    .agents-table tbody tr:hover {
        background: #F9FAFB;
    }

    .agent-profile {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .agent-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #7F3D9E;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
    }

    .agent-info {
        flex: 1;
    }

    .agent-name {
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 2px;
    }

    .agent-number {
        font-size: 11px;
        color: #9CA3AF;
    }

    .portfolio-badge {
        background: #FEF3C7;
        color: #F59E0B;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 18px;
        font-weight: 700;
    }

    .status-indicator {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        color: #10B981;
    }

    .status-indicator.down {
        color: #EF4444;
    }

    .earnings-value {
        font-weight: 700;
        color: #7F3D9E;
    }

    .action-btn {
        padding: 6px 12px;
        border-radius: 6px;
        border: 1px solid #E5E7EB;
        background: white;
        color: #6B7280;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .action-btn:hover {
        background: #7F3D9E;
        color: white;
        border-color: #7F3D9E;
    }

    /* Top Performers */
    .performers-card {
        background: linear-gradient(135deg, #7F3D9E 0%, #7F3D9E 100%);
        border-radius: 12px;
        padding: 24px;
        color: white;
        margin-bottom: 30px;
    }

    .performers-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .performers-title {
        font-size: 20px;
        font-weight: 700;
    }

    .performers-subtitle {
        font-size: 12px;
        opacity: 0.9;
        margin-top: 2px;
    }

    .trophy-icon {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .performers-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    .performer-item {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .performer-rank {
        width: 36px;
        height: 36px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
    }

    .performer-name {
        font-weight: 700;
        font-size: 14px;
    }

    .performer-stat {
        font-size: 11px;
        opacity: 0.9;
    }

    @media (max-width: 1200px) {
        .content-layout {
            grid-template-columns: 1fr;
        }

        .performers-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .stats-row {
            grid-template-columns: 1fr;
        }

        .growth-metrics {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">Agent & Commission Center</h1>
    <p class="page-subtitle">Field operations & payout management</p>
</div>

<!-- Emergency Alert -->
<div class="emergency-alert">
    <div class="alert-content">
        <div class="alert-icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="alert-text">
            <span class="alert-badge">SYSTEM NOTIFICATION</span>
            <div class="alert-title">Death Claim Alert: Member ID: #D-9022</div>
            <div class="alert-description">Assigned Agent: Martin Maguza. Immediate verification required for funeral payout</div>
        </div>
    </div>
    <button class="alert-button">COORDINATE AGENT</button>
</div>

<!-- Statistics Cards -->
<div class="stats-row">
    <!-- Total Field Agents -->
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon purple">
                <i class="fas fa-user-tie"></i>
            </div>
        </div>
        <div class="stat-label">Total Field Agents</div>
        <div class="stat-value">482</div>
        <div class="stat-change">+24 New</div>
    </div>

    <!-- Pending Commissions -->
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon yellow">
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>
        <div class="stat-label">Pending Commissions</div>
        <div class="stat-value">KES 142.5K</div>
        <div class="stat-change">8 Pending</div>
    </div>

    <!-- Monthly Accounts -->
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-label">Monthly Accounts</div>
        <div class="stat-value">1,240</div>
        <div class="stat-change">Single View</div>
    </div>

    <!-- Assigned Portfolios -->
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon blue">
                <i class="fas fa-briefcase"></i>
            </div>
        </div>
        <div class="stat-label">Assigned Portfolios</div>
        <div class="stat-value">8,902</div>
        <div class="stat-change">Top Performer</div>
    </div>
</div>

<!-- Main Content Layout -->
<div class="content-layout">
    <!-- Regional Agent Growth -->
    <div>
        <div class="growth-card">
            <div class="growth-header">
                <div>
                    <div class="growth-title">
                        <i class="fas fa-chart-line" style="color: #F59E0B;"></i>
                        <span>Regional Agent Growth</span>
                    </div>
                    <div class="growth-subtitle">Heatmap of recruitment and active agents indexed by region</div>
                </div>
                <div class="growth-filters">
                    <button class="filter-btn active">Monthly</button>
                    <button class="filter-btn">Quarterly</button>
                </div>
            </div>

            <div class="map-container">
                <i class="fas fa-map-marked-alt map-icon"></i>
                <div class="map-dot purple"></div>
                <div class="map-dot orange"></div>
                <div class="map-dot yellow"></div>
            </div>

            <div class="growth-metrics">
                <div class="metric-item">
                    <div class="metric-label">Top Region</div>
                    <div class="metric-value">Nairobi Makua</div>
                </div>
                <div class="metric-item">
                    <div class="metric-label">Perfect Growth</div>
                    <div class="metric-value">Coast Region</div>
                </div>
                <div class="metric-item">
                    <div class="metric-label">Active Regions</div>
                    <div class="metric-value">KES 843,000</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payout Queue -->
    <div>
        <div class="payout-card">
            <div class="payout-header">
                <div class="payout-title">Payout Queue</div>
                <span class="payout-badge">NEEDS REVIEW</span>
            </div>

            <!-- Sarah Williams -->
            <div class="payout-item">
                <div class="payout-agent">Sarah Williams</div>
                <div class="payout-amount">KES 12,461</div>
                <div class="payout-info">Agent: AGT-3826<br>8 Portfolios cleared</div>
                <button class="payout-button">Approve Payout</button>
            </div>

            <!-- Robert King'era -->
            <div class="payout-item">
                <div class="payout-agent">Robert King'era</div>
                <div class="payout-amount">KES 9,200</div>
                <div class="payout-info">Agent: AGT-4567<br>6 Portfolios cleared</div>
                <button class="payout-button audit">Audit</button>
            </div>
        </div>
    </div>
</div>

<!-- Registered Field Agents Table -->
<div class="agents-table-card">
    <div class="table-header">
        <div class="table-title">Registered Field Agents</div>
        <div class="table-actions">
            <button class="table-btn">
                <i class="fas fa-download"></i> Export Data
            </button>
            <button class="table-btn primary">
                <i class="fas fa-user-plus"></i> Register New Agent
            </button>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table class="agents-table">
            <thead>
                <tr>
                    <th>Agent Details</th>
                    <th>Region</th>
                    <th>Active Portfolios</th>
                    <th>New Recruits</th>
                    <th>Pending Earnings</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- David Ndungu -->
                <tr>
                    <td>
                        <div class="agent-profile">
                            <div class="agent-avatar">DM</div>
                            <div class="agent-info">
                                <div class="agent-name">David Ndungu</div>
                                <div class="agent-number">AGT-2891</div>
                            </div>
                        </div>
                    </td>
                    <td>Nairobi<br>Makua</td>
                    <td><span class="portfolio-badge">432</span></td>
                    <td>
                        <span class="status-indicator">
                            <i class="fas fa-arrow-up"></i>
                        </span>
                    </td>
                    <td><span class="earnings-value">KES 22,450</span></td>
                    <td><button class="action-btn">Details</button></td>
                </tr>

                <!-- Jane Mwema -->
                <tr>
                    <td>
                        <div class="agent-profile">
                            <div class="agent-avatar" style="background: #F59E0B;">JM</div>
                            <div class="agent-info">
                                <div class="agent-name">Jane Mwema</div>
                                <div class="agent-number">AGT-3456</div>
                            </div>
                        </div>
                    </td>
                    <td>Kitsumu<br>Region</td>
                    <td><span class="portfolio-badge">388</span></td>
                    <td>
                        <span class="status-indicator">
                            <i class="fas fa-arrow-up"></i>
                        </span>
                    </td>
                    <td><span class="earnings-value">KES 8,960</span></td>
                    <td><button class="action-btn">Details</button></td>
                </tr>

                <!-- Andrew Gichuki -->
                <tr>
                    <td>
                        <div class="agent-profile">
                            <div class="agent-avatar" style="background: #10B981;">AG</div>
                            <div class="agent-info">
                                <div class="agent-name">Andrew Gichuki</div>
                                <div class="agent-number">AGT-4823</div>
                            </div>
                        </div>
                    </td>
                    <td>Mombasa<br>Region</td>
                    <td><span class="portfolio-badge">4.0</span></td>
                    <td>
                        <span class="status-indicator down">
                            <i class="fas fa-arrow-down"></i> -1
                        </span>
                    </td>
                    <td><span class="earnings-value">KES 0.00</span></td>
                    <td><button class="action-btn">Details</button></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="text-align: center; padding: 16px; color: #9CA3AF; font-size: 13px;">
        Displaying 3 OF 482 AGENTS
        <div style="margin-top: 12px; display: flex; gap: 8px; justify-content: center;">
            <button style="padding: 6px 12px; border: 1px solid #E5E7EB; background: white; border-radius: 6px; cursor: pointer;">Previous</button>
            <button style="padding: 6px 12px; border: 1px solid #E5E7EB; background: white; border-radius: 6px; cursor: pointer;">Next Page</button>
        </div>
    </div>
</div>

<!-- Monthly Top Performers -->
<div class="performers-card">
    <div class="performers-header">
        <div>
            <div class="performers-title">Monthly Top Performers</div>
            <div class="performers-subtitle">Elite agents driving SHENA's growth mission</div>
        </div>
        <div class="trophy-icon">
            <i class="fas fa-trophy"></i>
        </div>
    </div>

    <div class="performers-grid">
        <!-- 1st Place -->
        <div class="performer-item">
            <div class="performer-rank">1</div>
            <div>
                <div class="performer-name">David Ndungu</div>
                <div class="performer-stat">24 new portfolio accounts</div>
            </div>
        </div>

        <!-- 2nd Place -->
        <div class="performer-item">
            <div class="performer-rank">2</div>
            <div>
                <div class="performer-name">Ann Kamau</div>
                <div class="performer-stat">18 new portfolio accounts</div>
            </div>
        </div>

        <!-- 3rd Place -->
        <div class="performer-item">
            <div class="performer-rank">3</div>
            <div>
                <div class="performer-name">Sam Olouch</div>
                <div class="performer-stat">15 new portfolio accounts</div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/admin-footer.php'; ?>
