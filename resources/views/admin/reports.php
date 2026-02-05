<?php include_once __DIR__ . '/../layouts/admin-header.php'; ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0"><i class="fas fa-chart-line me-2"></i>Reports & Analytics</h1>
    <div class="header-actions">
        <button class="btn btn-danger btn-sm" onclick="downloadReport('pdf')">
            <i class="fas fa-file-pdf me-2"></i>Export PDF
        </button>
        <button class="btn btn-success btn-sm" onclick="downloadReport('excel')">
            <i class="fas fa-file-excel me-2"></i>Export Excel
        </button>
    </div>
</div>

<!-- Reports & Analytics Tabs -->
<ul class="nav nav-tabs mb-4" id="reportsTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
            <i class="fas fa-chart-bar"></i> Overview
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="financial-tab" data-bs-toggle="tab" data-bs-target="#financial" type="button" role="tab">
            <i class="fas fa-money-bill-trend-up"></i> Financial Analytics
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="members-analytics-tab" data-bs-toggle="tab" data-bs-target="#membersAnalytics" type="button" role="tab">
            <i class="fas fa-users"></i> Member Analytics
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="claims-analytics-tab" data-bs-toggle="tab" data-bs-target="#claimsAnalytics" type="button" role="tab">
            <i class="fas fa-file-medical"></i> Claims Analytics
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="agent-performance-tab" data-bs-toggle="tab" data-bs-target="#agentPerformance" type="button" role="tab">
            <i class="fas fa-user-tie"></i> Agent Performance
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="contributions-tab" data-bs-toggle="tab" data-bs-target="#contributionsAnalytics" type="button" role="tab">
            <i class="fas fa-coins"></i> Contributions Analysis
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="reportsTabContent">

    <!-- Overview Tab -->
    <div class="tab-pane fade show active" id="overview" role="tabpanel">
        
        <!-- Stats Grid -->
        <div class="stats-row mb-4">
            <div class="stat-card primary">
                <div class="icon-wrapper">
                    <i class="fas fa-users"></i>
                </div>
                <p class="stat-value"><?php echo $totalMembers ?? 245; ?></p>
                <p class="stat-label">Total Members</p>
            </div>
            <div class="stat-card success">
                <div class="icon-wrapper">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <p class="stat-value">KES <?php echo number_format($totalRevenue ?? 490000, 0); ?></p>
                <p class="stat-label">Total Revenue</p>
            </div>
            <div class="stat-card warning">
                <div class="icon-wrapper">
                    <i class="fas fa-file-medical"></i>
                </div>
                <p class="stat-value"><?php echo $totalClaims ?? 12; ?></p>
                <p class="stat-label">Total Claims</p>
            </div>
            <div class="stat-card info">
                <div class="icon-wrapper">
                    <i class="fas fa-user-tie"></i>
                </div>
                <p class="stat-value"><?php echo $activeAgents ?? 8; ?></p>
                <p class="stat-label">Active Agents</p>
            </div>
        </div>

        <!-- Charts -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="modern-card">
                    <h3 style="font-family: 'Playfair Display', serif; margin-bottom: 1.5rem; color: #1f2937;">
                        <i class="fas fa-chart-line"></i> Revenue Trend (Last 6 Months)
                    </h3>
                    <canvas id="revenueChart" height="300"></canvas>
                </div>
            </div>
            <div class="col-md-4">
                <div class="modern-card">
                    <h3 style="font-family: 'Playfair Display', serif; margin-bottom: 1.5rem; color: #1f2937;">
                        <i class="fas fa-users"></i> Member Distribution
                    </h3>
                    <canvas id="memberChart" height="300"></canvas>
                    <div style="margin-top: 1.5rem;">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-circle" style="color: #7F3D9E;"></i> Active</span>
                            <strong><?php echo $activeMembers ?? 220; ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-circle" style="color: #10B981;"></i> Inactive</span>
                            <strong><?php echo $inactiveMembers ?? 15; ?></strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><i class="fas fa-circle" style="color: #3B82F6;"></i> Pending</span>
                            <strong><?php echo $pendingMembers ?? 10; ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Summary -->
        <div class="modern-card">
            <h3 style="font-family: 'Playfair Display', serif; margin-bottom: 1.5rem; color: #1f2937;">
                <i class="fas fa-chart-bar"></i> Quick Summary
            </h3>
            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Metric</th>
                            <th>This Month</th>
                            <th>Last Month</th>
                            <th>Change</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>New Members</strong></td>
                            <td>24</td>
                            <td>18</td>
                            <td><span class="badge bg-success">+33%</span></td>
                        </tr>
                        <tr>
                            <td><strong>Revenue Collected</strong></td>
                            <td>KES 48,000</td>
                            <td>KES 45,000</td>
                            <td><span class="badge bg-success">+6.7%</span></td>
                        </tr>
                        <tr>
                            <td><strong>Claims Processed</strong></td>
                            <td>3</td>
                            <td>2</td>
                            <td><span class="badge bg-warning">+50%</span></td>
                        </tr>
                        <tr>
                            <td><strong>Active Payments</strong></td>
                            <td>220</td>
                            <td>210</td>
                            <td><span class="badge bg-success">+4.8%</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Financial Analytics Tab -->
    <div class="tab-pane fade" id="financial" role="tabpanel">
        <div class="modern-card">
            <h3 style="font-family: 'Playfair Display', serif; margin-bottom: 1.5rem; color: #1f2937;">
                <i class="fas fa-money-bill-trend-up"></i> Financial Analytics
            </h3>
            
            <div class="stats-row mb-4">
                <div class="stat-card success">
                    <div class="icon-wrapper">
                        <i class="fas fa-arrow-trend-up"></i>
                    </div>
                    <p class="stat-value">KES <?php echo number_format($monthlyRevenue ?? 48000, 0); ?></p>
                    <p class="stat-label">Monthly Revenue</p>
                </div>
                <div class="stat-card info">
                    <div class="icon-wrapper">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <p class="stat-value">KES <?php echo number_format($outstandingBalance ?? 12000, 0); ?></p>
                    <p class="stat-label">Outstanding Balance</p>
                </div>
                <div class="stat-card warning">
                    <div class="icon-wrapper">
                        <i class="fas fa-coins"></i>
                    </div>
                    <p class="stat-value">KES <?php echo number_format($claimsPaid ?? 85000, 0); ?></p>
                    <p class="stat-label">Claims Paid</p>
                </div>
                <div class="stat-card primary">
                    <div class="icon-wrapper">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <p class="stat-value">KES <?php echo number_format($netBalance ?? 405000, 0); ?></p>
                    <p class="stat-label">Net Balance</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Collections</th>
                            <th>Claims Paid</th>
                            <th>Operating Costs</th>
                            <th>Net Income</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>January 2026</strong></td>
                            <td>KES 48,000</td>
                            <td>KES 15,000</td>
                            <td>KES 5,000</td>
                            <td>KES 28,000</td>
                            <td><span class="badge bg-success">Positive</span></td>
                        </tr>
                        <tr>
                            <td><strong>December 2025</strong></td>
                            <td>KES 45,000</td>
                            <td>KES 20,000</td>
                            <td>KES 4,500</td>
                            <td>KES 20,500</td>
                            <td><span class="badge bg-success">Positive</span></td>
                        </tr>
                        <tr>
                            <td><strong>November 2025</strong></td>
                            <td>KES 42,000</td>
                            <td>KES 10,000</td>
                            <td>KES 4,200</td>
                            <td>KES 27,800</td>
                            <td><span class="badge bg-success">Positive</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Member Analytics Tab -->
    <div class="tab-pane fade" id="membersAnalytics" role="tabpanel">
        <div class="modern-card">
            <h3 style="font-family: 'Playfair Display', serif; margin-bottom: 1.5rem; color: #1f2937;">
                <i class="fas fa-users"></i> Member Analytics
            </h3>
            
            <div class="stats-row mb-4">
                <div class="stat-card primary">
                    <div class="icon-wrapper">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <p class="stat-value"><?php echo $activeMembers ?? 220; ?></p>
                    <p class="stat-label">Active Members</p>
                </div>
                <div class="stat-card warning">
                    <div class="icon-wrapper">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <p class="stat-value"><?php echo $inactiveMembers ?? 15; ?></p>
                    <p class="stat-label">Inactive Members</p>
                </div>
                <div class="stat-card success">
                    <div class="icon-wrapper">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <p class="stat-value"><?php echo $newMembersThisMonth ?? 24; ?></p>
                    <p class="stat-label">New This Month</p>
                </div>
                <div class="stat-card info">
                    <div class="icon-wrapper">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <p class="stat-value"><?php echo $retentionRate ?? 94; ?>%</p>
                    <p class="stat-label">Retention Rate</p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="modern-card" style="background: #f9fafb;">
                        <h4 style="margin-bottom: 1rem; color: #1f2937;">Registration Trend</h4>
                        <canvas id="registrationChart" height="250"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="modern-card" style="background: #f9fafb;">
                        <h4 style="margin-bottom: 1rem; color: #1f2937;">Member by Plan Type</h4>
                        <div class="table-responsive">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>Plan</th>
                                        <th>Members</th>
                                        <th>Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Basic Plan</strong></td>
                                        <td>180</td>
                                        <td><span class="badge bg-primary">73%</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Standard Plan</strong></td>
                                        <td>50</td>
                                        <td><span class="badge bg-info">20%</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Premium Plan</strong></td>
                                        <td>15</td>
                                        <td><span class="badge bg-success">7%</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Claims Analytics Tab -->
    <div class="tab-pane fade" id="claimsAnalytics" role="tabpanel">
        <div class="modern-card">
            <h3 style="font-family: 'Playfair Display', serif; margin-bottom: 1.5rem; color: #1f2937;">
                <i class="fas fa-file-medical"></i> Claims Analytics
            </h3>
            
            <div class="stats-row mb-4">
                <div class="stat-card primary">
                    <div class="icon-wrapper">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <p class="stat-value"><?php echo $totalClaims ?? 45; ?></p>
                    <p class="stat-label">Total Claims</p>
                </div>
                <div class="stat-card success">
                    <div class="icon-wrapper">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <p class="stat-value"><?php echo $approvedClaims ?? 38; ?></p>
                    <p class="stat-label">Approved</p>
                </div>
                <div class="stat-card warning">
                    <div class="icon-wrapper">
                        <i class="fas fa-clock"></i>
                    </div>
                    <p class="stat-value"><?php echo $pendingClaims ?? 4; ?></p>
                    <p class="stat-label">Pending</p>
                </div>
                <div class="stat-card danger">
                    <div class="icon-wrapper">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <p class="stat-value"><?php echo $rejectedClaims ?? 3; ?></p>
                    <p class="stat-label">Rejected</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Claim ID</th>
                            <th>Member Name</th>
                            <th>Deceased Name</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Submitted Date</th>
                            <th>Processing Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>CLM-001</strong></td>
                            <td>John Kamau</td>
                            <td>Mary Kamau</td>
                            <td>KES 50,000</td>
                            <td><span class="badge bg-success">Approved</span></td>
                            <td>Jan 15, 2026</td>
                            <td>3 days</td>
                        </tr>
                        <tr>
                            <td><strong>CLM-002</strong></td>
                            <td>Jane Wanjiku</td>
                            <td>Peter Wanjiku</td>
                            <td>KES 50,000</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td>Jan 20, 2026</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td><strong>CLM-003</strong></td>
                            <td>David Omondi</td>
                            <td>Grace Omondi</td>
                            <td>KES 50,000</td>
                            <td><span class="badge bg-success">Approved</span></td>
                            <td>Dec 28, 2025</td>
                            <td>2 days</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Agent Performance Tab -->
    <div class="tab-pane fade" id="agentPerformance" role="tabpanel">
        <div class="modern-card">
            <h3 style="font-family: 'Playfair Display', serif; margin-bottom: 1.5rem; color: #1f2937;">
                <i class="fas fa-user-tie"></i> Agent Performance
            </h3>
            
            <div class="stats-row mb-4">
                <div class="stat-card primary">
                    <div class="icon-wrapper">
                        <i class="fas fa-users"></i>
                    </div>
                    <p class="stat-value"><?php echo $totalAgents ?? 8; ?></p>
                    <p class="stat-label">Total Agents</p>
                </div>
                <div class="stat-card success">
                    <div class="icon-wrapper">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <p class="stat-value"><?php echo $totalRecruitments ?? 156; ?></p>
                    <p class="stat-label">Total Recruitments</p>
                </div>
                <div class="stat-card info">
                    <div class="icon-wrapper">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <p class="stat-value"><?php echo $avgRecruitments ?? 19; ?></p>
                    <p class="stat-label">Avg Per Agent</p>
                </div>
                <div class="stat-card warning">
                    <div class="icon-wrapper">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <p class="stat-value"><?php echo $topAgentRecruitments ?? 42; ?></p>
                    <p class="stat-label">Top Agent</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Agent Name</th>
                            <th>Total Recruitments</th>
                            <th>This Month</th>
                            <th>Active Members</th>
                            <th>Performance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="badge bg-warning"><i class="fas fa-trophy"></i> 1</span></td>
                            <td><strong>James Mwangi</strong></td>
                            <td>42</td>
                            <td>8</td>
                            <td>40</td>
                            <td><span class="badge bg-success">Excellent</span></td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-secondary">2</span></td>
                            <td><strong>Sarah Nyambura</strong></td>
                            <td>35</td>
                            <td>6</td>
                            <td>33</td>
                            <td><span class="badge bg-success">Excellent</span></td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-secondary">3</span></td>
                            <td><strong>Peter Otieno</strong></td>
                            <td>28</td>
                            <td>5</td>
                            <td>26</td>
                            <td><span class="badge bg-info">Good</span></td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td><strong>Lucy Akinyi</strong></td>
                            <td>22</td>
                            <td>4</td>
                            <td>21</td>
                            <td><span class="badge bg-info">Good</span></td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td><strong>Michael Kariuki</strong></td>
                            <td>15</td>
                            <td>2</td>
                            <td>14</td>
                            <td><span class="badge bg-warning">Average</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Contributions Analysis Tab -->
    <div class="tab-pane fade" id="contributionsAnalytics" role="tabpanel">
        <div class="modern-card">
            <h3 style="font-family: 'Playfair Display', serif; margin-bottom: 1.5rem; color: #1f2937;">
                <i class="fas fa-coins"></i> Contributions Analysis
            </h3>
            
            <div class="stats-row mb-4">
                <div class="stat-card success">
                    <div class="icon-wrapper">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <p class="stat-value">KES <?php echo number_format($monthlyContributions ?? 48000, 0); ?></p>
                    <p class="stat-label">This Month</p>
                </div>
                <div class="stat-card primary">
                    <div class="icon-wrapper">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <p class="stat-value">KES <?php echo number_format($totalContributions ?? 490000, 0); ?></p>
                    <p class="stat-label">Total Collected</p>
                </div>
                <div class="stat-card info">
                    <div class="icon-wrapper">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <p class="stat-value"><?php echo $collectionRate ?? 92; ?>%</p>
                    <p class="stat-label">Collection Rate</p>
                </div>
                <div class="stat-card warning">
                    <div class="icon-wrapper">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <p class="stat-value">KES <?php echo number_format($overdueAmount ?? 8000, 0); ?></p>
                    <p class="stat-label">Overdue</p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="modern-card" style="background: #f9fafb;">
                        <h4 style="margin-bottom: 1rem; color: #1f2937;">Monthly Contributions Trend</h4>
                        <canvas id="contributionsChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Expected</th>
                            <th>Collected</th>
                            <th>Pending</th>
                            <th>Collection Rate</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>January 2026</strong></td>
                            <td>KES 50,000</td>
                            <td>KES 48,000</td>
                            <td>KES 2,000</td>
                            <td>96%</td>
                            <td><span class="badge bg-success">Excellent</span></td>
                        </tr>
                        <tr>
                            <td><strong>December 2025</strong></td>
                            <td>KES 48,000</td>
                            <td>KES 45,000</td>
                            <td>KES 3,000</td>
                            <td>94%</td>
                            <td><span class="badge bg-success">Good</span></td>
                        </tr>
                        <tr>
                            <td><strong>November 2025</strong></td>
                            <td>KES 45,000</td>
                            <td>KES 42,000</td>
                            <td>KES 3,000</td>
                            <td>93%</td>
                            <td><span class="badge bg-success">Good</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<style>
    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
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

    .header-actions {
        display: flex;
        gap: 12px;
    }

    .btn-export {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-export.pdf {
        background: #DC2626;
        color: white;
    }

    .btn-export.pdf:hover {
        background: #B91C1C;
        transform: translateY(-1px);
    }

    .btn-export.excel {
        background: #10B981;
        color: white;
    }

    .btn-export.excel:hover {
        background: #059669;
        transform: translateY(-1px);
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #E5E7EB;
        margin-bottom: 30px;
    }

    .filter-title {
        font-size: 16px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 20px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 10px 16px;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #7F3D9E;
        box-shadow: 0 0 0 3px rgba(127, 61, 158, 0.1);
    }

    .btn-generate {
        background: #7F3D9E;
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        width: 100%;
    }

    .btn-generate:hover {
        background: #7F3D9E;
        transform: translateY(-1px);
    }

    /* Charts Grid */
    .charts-grid {
        display: grid;
        grid-template-columns: 1.8fr 1fr;
        gap: 24px;
        margin-bottom: 30px;
    }

    /* Universal Styles */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
        box-shadow: 0 4px 12px rgba(127, 61, 158, 0.1);
        transform: translateY(-2px);
    }

    .stat-card .icon-wrapper {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        margin-bottom: 12px;
    }

    .stat-card.primary .icon-wrapper {
        background: #EDE9FE;
        color: #7F3D9E;
    }

    .stat-card.success .icon-wrapper {
        background: #D1FAE5;
        color: #10B981;
    }

    .stat-card.info .icon-wrapper {
        background: #DBEAFE;
        color: #3B82F6;
    }

    .stat-card.warning .icon-wrapper {
        background: #FEF3C7;
        color: #F59E0B;
    }

    .stat-card.danger .icon-wrapper {
        background: #FEE2E2;
        color: #EF4444;
    }

    .stat-card .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #1F2937;
        margin: 0 0 8px 0;
    }

    .stat-card .stat-label {
        font-size: 13px;
        font-weight: 600;
        color: #6B7280;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .modern-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #E5E7EB;
        margin-bottom: 24px;
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        overflow: hidden;
    }

    .modern-table thead {
        background: #7F3D9E;
    }

    .modern-table thead th {
        color: white;
        padding: 12px 16px;
        text-align: left;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .modern-table tbody td {
        padding: 12px 16px;
        border-top: 1px solid #E5E7EB;
        color: #374151;
        font-size: 14px;
    }

    .modern-table tbody tr:hover {
        background: #F9FAFB;
    }

    .chart-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #E5E7EB;
    }

    .chart-header {
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 2px solid #F3F4F6;
    }

    .chart-title {
        font-size: 18px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 4px;
    }

    .chart-subtitle {
        font-size: 13px;
        color: #9CA3AF;
    }

    .chart-container {
        height: 300px;
        position: relative;
    }

    .chart-legend {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #6B7280;
    }

    .legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    .legend-dot.primary {
        background: #7F3D9E;
    }

    .legend-dot.success {
        background: #10B981;
    }

    .legend-dot.info {
        background: #3B82F6;
    }

    /* Table Card */
    .table-card {
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
        padding-bottom: 16px;
        border-bottom: 2px solid #F3F4F6;
    }

    .table-title {
        font-size: 18px;
        font-weight: 700;
        color: #1F2937;
    }

    /* Report Table */
    .report-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .report-table thead th {
        background: #F9FAFB;
        padding: 12px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #E5E7EB;
    }

    .report-table tbody td {
        padding: 16px;
        border-bottom: 1px solid #F3F4F6;
        font-size: 14px;
        color: #1F2937;
    }

    .report-table tbody tr:hover {
        background: #F9FAFB;
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.active {
        background: #D1FAE5;
        color: #059669;
    }

    .status-badge.inactive {
        background: #FEF3C7;
        color: #D97706;
    }

    .status-badge.pending {
        background: #E5E7EB;
        color: #6B7280;
    }

    .status-badge.completed {
        background: #D1FAE5;
        color: #059669;
    }

    .status-badge.failed {
        background: #FEE2E2;
        color: #DC2626;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 64px;
        color: #E5E7EB;
        margin-bottom: 20px;
    }

    .empty-state h5 {
        font-size: 18px;
        font-weight: 700;
        color: #6B7280;
        margin-bottom: 8px;
    }

    .empty-state p {
        font-size: 14px;
        color: #9CA3AF;
    }

    @media (max-width: 1024px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
if (document.getElementById('revenueChart')) {
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['Aug 2025', 'Sep 2025', 'Oct 2025', 'Nov 2025', 'Dec 2025', 'Jan 2026'],
            datasets: [{
                label: 'Monthly Revenue',
                data: [38000, 40000, 42000, 42000, 45000, 48000],
                borderColor: '#7F3D9E',
                backgroundColor: 'rgba(127, 61, 158, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#7F3D9E',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'KES ' + value.toLocaleString();
                        }
                    },
                    grid: {
                        color: '#F3F4F6'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// Member Chart
if (document.getElementById('memberChart')) {
    const memberCtx = document.getElementById('memberChart').getContext('2d');
    const memberChart = new Chart(memberCtx, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Inactive', 'Pending'],
            datasets: [{
                data: [220, 15, 10],
                backgroundColor: ['#7F3D9E', '#10B981', '#3B82F6'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '70%'
        }
    });
}

// Registration Chart
if (document.getElementById('registrationChart')) {
    const registrationCtx = document.getElementById('registrationChart').getContext('2d');
    new Chart(registrationCtx, {
        type: 'bar',
        data: {
            labels: ['Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan'],
            datasets: [{
                label: 'New Registrations',
                data: [15, 18, 22, 20, 18, 24],
                backgroundColor: '#7F3D9E',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#F3F4F6'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// Contributions Chart
if (document.getElementById('contributionsChart')) {
    const contributionsCtx = document.getElementById('contributionsChart').getContext('2d');
    new Chart(contributionsCtx, {
        type: 'line',
        data: {
            labels: ['Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan'],
            datasets: [
                {
                    label: 'Expected',
                    data: [40000, 42000, 43000, 45000, 48000, 50000],
                    borderColor: '#6B7280',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    tension: 0.4
                },
                {
                    label: 'Collected',
                    data: [38000, 40000, 42000, 42000, 45000, 48000],
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'KES ' + value.toLocaleString();
                        }
                    },
                    grid: {
                        color: '#F3F4F6'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

function downloadReport(format) {
    alert('Downloading report in ' + format.toUpperCase() + ' format...');
    // Implement export functionality
}
</script>

<?php
include_once __DIR__ . '/../layouts/admin-footer.php';
?>
