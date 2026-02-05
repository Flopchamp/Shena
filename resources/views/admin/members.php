<?php include_once __DIR__ . '/../layouts/admin-header.php'; ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0"><i class="fas fa-users me-2"></i>Member Management</h1>
    <div class="header-actions">
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#registerMemberModal">
            <i class="fas fa-user-plus me-2"></i>Register Member
        </button>
        <button class="btn btn-success btn-sm" onclick="exportMemberData()">
            <i class="fas fa-file-excel me-2"></i>Export Data
        </button>
    </div>
</div>

<!-- Alert Messages -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Member Management Tabs -->
<ul class="nav nav-tabs mb-4" id="memberTabs" role="tablist">
    <li class="nav-item">
        <button class="nav-link active" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats" type="button">
            <i class="fas fa-chart-pie"></i> Statistics
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" id="members-list-tab" data-bs-toggle="tab" data-bs-target="#membersList" type="button">
            <i class="fas fa-list"></i> All Members
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" id="approvals-tab" data-bs-toggle="tab" data-bs-target="#approvals" type="button">
            <i class="fas fa-clock"></i> Pending Approvals
            <span class="badge bg-danger ms-1">3</span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" id="maturity-tab" data-bs-toggle="tab" data-bs-target="#maturity" type="button">
            <i class="fas fa-hourglass-half"></i> Maturity Tracking
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" id="defaulters-tab" data-bs-toggle="tab" data-bs-target="#defaulters" type="button">
            <i class="fas fa-exclamation-triangle"></i> Defaulters
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content">
    
    <!-- Statistics Tab -->
    <div class="tab-pane fade show active" id="stats">
        <!-- Stats Row with Modern Card Design -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon purple">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-label">Total Members</div>
                <div class="stat-value"><?php echo number_format($stats['total_members'] ?? 0); ?></div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+2.8% from last month</span>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon green">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>
                <div class="stat-label">Active Contributors</div>
                <div class="stat-value"><?php echo number_format($stats['active_contributors'] ?? 0); ?></div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+1.5% from last month</span>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon yellow">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="stat-label">Pending Approvals</div>
                <div class="stat-value"><?php echo number_format($stats['pending_approvals'] ?? 0); ?></div>
                <div class="stat-change">
                    <i class="fas fa-info-circle"></i>
                    <span>Requires Action</span>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon red">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
                <div class="stat-label">Defaulters</div>
                <div class="stat-value"><?php echo number_format($stats['defaulters'] ?? 0); ?></div>
                <div class="stat-change negative">
                    <i class="fas fa-arrow-down"></i>
                    <span>-0.5% from last month</span>
                </div>
            </div>
        </div>
        
        <!-- Charts Row -->
        <div class="content-layout">
            <div class="growth-card">
                <div class="growth-header">
                    <div>
                        <div class="growth-title">
                            <i class="fas fa-chart-line"></i>
                            Member Growth Trend
                        </div>
                        <div class="growth-subtitle">Monthly registration performance</div>
                    </div>
                    <div class="growth-filters">
                        <button class="filter-btn active">6M</button>
                        <button class="filter-btn">1Y</button>
                        <button class="filter-btn">All</button>
                    </div>
                </div>
                <canvas id="memberGrowthChart" height="300"></canvas>
            </div>
            
            <div class="growth-card">
                <div class="growth-header">
                    <div>
                        <div class="growth-title">
                            <i class="fas fa-chart-pie"></i>
                            Status Distribution
                        </div>
                        <div class="growth-subtitle">Current member status breakdown</div>
                    </div>
                </div>
                <canvas id="statusPieChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- All Members List Tab -->
    <div class="tab-pane fade" id="membersList">
        <div class="members-table-card">
            <div class="table-header">
                <div class="table-title">Comprehensive Member Directory</div>
                <div class="table-actions">
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" id="memberSearch" placeholder="Search by name, member number, ID, phone...">
                    </div>
                    <select class="filter-select" id="statusFilter">
                        <option value="all">All Status</option>
                        <option value="active">Active</option>
                        <option value="pending">Pending</option>
                        <option value="inactive">Inactive</option>
                        <option value="defaulted">Defaulted</option>
                        <option value="grace_period">Grace Period</option>
                    </select>
                    <select class="filter-select" id="packageFilter">
                        <option value="all">All Packages</option>
                        <option value="individual">Individual</option>
                        <option value="couple">Couple</option>
                        <option value="family">Family</option>
                    </select>
                    <button class="btn-export" onclick="exportMemberData()">
                        <i class="fas fa-download"></i>
                        Export CSV
                    </button>
                </div>
            </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Member</th>
                                <th>Contact</th>
                                <th>Package</th>
                                <th>Status</th>
                                <th>Maturity</th>
                                <th>Coverage</th>
                                <th>Last Payment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($members) && is_array($members)): ?>
                                <?php foreach ($members as $member): ?>
                                <tr class="member-row">
                                    <td>
                                        <div class="member-info">
                                            <div class="member-avatar purple">
                                                <?php echo strtoupper(substr($member['first_name'] ?? 'N', 0, 1) . substr($member['last_name'] ?? 'A', 0, 1)); ?>
                                            </div>
                                            <div class="member-details">
                                                <div class="member-name"><?php echo htmlspecialchars(($member['first_name'] ?? '') . ' ' . ($member['last_name'] ?? '')); ?></div>
                                                <div class="member-number"><?php echo htmlspecialchars($member['member_number'] ?? 'N/A'); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="contact-info">
                                            <div class="phone-number"><?php echo htmlspecialchars($member['phone'] ?? 'N/A'); ?></div>
                                            <div class="email-address"><?php echo htmlspecialchars($member['email'] ?? ''); ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="package-badge"><?php echo htmlspecialchars(ucfirst($member['package_type'] ?? 'N/A')); ?></span>
                                    </td>
                                    <td>
                                        <?php
                                        $status = $member['status'] ?? 'pending';
                                        $statusColors = [
                                            'active' => 'active',
                                            'pending' => 'pending',
                                            'inactive' => 'inactive',
                                            'defaulted' => 'defaulted',
                                            'grace_period' => 'grace'
                                        ];
                                        $statusClass = $statusColors[$status] ?? 'inactive';
                                        ?>
                                        <span class="status-badge <?php echo $statusClass; ?>"><?php echo ucfirst(str_replace('_', ' ', $status)); ?></span>
                                    </td>
                                    <td>
                                        <?php
                                        $maturityEnds = $member['maturity_ends'] ?? null;
                                        if ($maturityEnds && $maturityEnds != '0000-00-00') {
                                            $maturityDate = new DateTime($maturityEnds);
                                            $today = new DateTime();
                                            if ($today < $maturityDate) {
                                                $diff = $today->diff($maturityDate);
                                                echo '<span class="maturity-badge pending"><i class="fas fa-hourglass-half"></i> ' . $diff->days . ' days</span>';
                                            } else {
                                                echo '<span class="maturity-badge completed"><i class="fas fa-check"></i> Matured</span>';
                                            }
                                        } else {
                                            echo '<span class="maturity-badge not-set">Not Set</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $coverageEnds = $member['coverage_ends'] ?? null;
                                        if ($coverageEnds && $coverageEnds != '0000-00-00') {
                                            $coverageDate = new DateTime($coverageEnds);
                                            $today = new DateTime();
                                            $diff = $today->diff($coverageDate);
                                            if ($today < $coverageDate) {
                                                echo '<div class="coverage-info active"><i class="fas fa-shield-alt"></i> ' . $diff->days . ' days</div>';
                                            } else {
                                                echo '<div class="coverage-info expired"><i class="fas fa-exclamation-circle"></i> Expired</div>';
                                            }
                                        } else {
                                            echo '<div class="coverage-info not-set">N/A</div>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $lastPayment = $member['last_payment_date'] ?? null;
                                        if ($lastPayment && $lastPayment != '0000-00-00 00:00:00') {
                                            echo '<div class="payment-date">' . date('M d, Y', strtotime($lastPayment)) . '</div>';
                                        } else {
                                            echo '<div class="payment-date no-payment">No payments</div>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="/admin/member/<?php echo $member['id']; ?>" class="action-btn view" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if ($status === 'pending' || $status === 'inactive'): ?>
                                                <button class="action-btn activate" onclick="activateMember(<?php echo $member['id']; ?>)" title="Activate">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            <?php endif; ?>
                                            <?php if ($status === 'active'): ?>
                                                <button class="action-btn deactivate" onclick="deactivateMember(<?php echo $member['id']; ?>)" title="Deactivate">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="empty-state">
                                        <div class="empty-icon">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div class="empty-text">No members found</div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="table-pagination">
                    <div class="pagination-info">Showing <?php echo count($members ?? []); ?> members</div>
                    <div class="pagination-buttons">
                        <button class="pagination-btn" disabled>Previous</button>
                        <button class="pagination-btn active">1</button>
                        <button class="pagination-btn">2</button>
                        <button class="pagination-btn">3</button>
                        <button class="pagination-btn">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Approvals Tab -->
    <div class="tab-pane fade" id="approvals">
        <div class="members-table-card">
            <div class="table-header">
                <div>
                    <h3 class="table-title"><i class="fas fa-clock" style="margin-right: 8px;"></i>Members Awaiting Activation</h3>
                    <p class="table-subtitle">Verify registration fee payment before activating (KES 200 required per policy Section 5)</p>
                </div>
            </div>
            
            <div class="policy-alert">
                <i class="fas fa-info-circle"></i>
                <div>
                    <strong>Policy Section 5:</strong> Registration fee of KES 200 must be verified before member activation
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>MEMBER DETAILS</th>
                            <th>PACKAGE</th>
                            <th>REGISTRATION DATE</th>
                            <th>PAYMENT STATUS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $pendingMembers = array_filter($members ?? [], function($m) {
                            return ($m['status'] ?? '') === 'pending';
                        });
                        ?>
                        <?php if (!empty($pendingMembers)): ?>
                            <?php foreach ($pendingMembers as $member): ?>
                            <tr class="member-row">
                                <td>
                                    <div class="member-info">
                                        <div class="member-avatar purple">
                                            <?php echo strtoupper(substr($member['first_name'] ?? 'N', 0, 1) . substr($member['last_name'] ?? 'A', 0, 1)); ?>
                                        </div>
                                        <div class="member-details">
                                            <div class="member-name"><?php echo htmlspecialchars(($member['first_name'] ?? '') . ' ' . ($member['last_name'] ?? '')); ?></div>
                                            <div class="member-number"><?php echo htmlspecialchars($member['member_number'] ?? 'N/A'); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="package-badge"><?php echo htmlspecialchars(ucfirst($member['package_type'] ?? 'N/A')); ?></span></td>
                                <td><?php echo date('M d, Y', strtotime($member['created_at'] ?? 'now')); ?></td>
                                <td>
                                    <span class="payment-status pending">
                                        <i class="fas fa-exclamation-circle"></i> Verify Payment
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn activate" onclick="activateMember(<?php echo $member['id']; ?>)" title="Activate Member">
                                            <i class="fas fa-check-circle"></i> Activate
                                        </button>
                                        <a href="/admin/member/<?php echo $member['id']; ?>" class="action-btn view" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="empty-text">No pending approvals</div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>

    <!-- Maturity Tracking Tab -->
    <div class="tab-pane fade" id="maturity">
        <div class="members-table-card">
            <div class="table-header">
                <div>
                    <h3 class="table-title"><i class="fas fa-hourglass-half" style="margin-right: 8px;"></i>Maturity Period Tracking</h3>
                    <p class="table-subtitle">4 months for ages 1-80, 5 months for ages 81-100 (Policy Section 7)</p>
                </div>
            </div>
            
            <div class="policy-alert">
                <i class="fas fa-info-circle"></i>
                <div>
                    <strong>Policy Section 7:</strong> Maturity period is 4 months for members aged 1-80 years, and 5 months for members aged 81-100 years
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>MEMBER</th>
                            <th>AGE</th>
                            <th>PACKAGE</th>
                            <th>JOINED DATE</th>
                            <th>MATURITY PERIOD</th>
                            <th>DAYS REMAINING</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $today = new DateTime();
                        $membersInMaturity = array_filter($members ?? [], function($m) use ($today) {
                            if (empty($m['maturity_ends']) || $m['maturity_ends'] == '0000-00-00') return false;
                            $maturityDate = new DateTime($m['maturity_ends']);
                            return $today < $maturityDate;
                        });
                        ?>
                        <?php if (!empty($membersInMaturity)): ?>
                            <?php foreach ($membersInMaturity as $member): ?>
                            <?php
                            $maturityDate = new DateTime($member['maturity_ends']);
                            $diff = $today->diff($maturityDate);
                            $daysRemaining = $diff->days;
                            $age = isset($member['date_of_birth']) ? (new DateTime())->diff(new DateTime($member['date_of_birth']))->y : 'N/A';
                            ?>
                            <tr class="member-row">
                                <td>
                                    <div class="member-info">
                                        <div class="member-avatar purple">
                                            <?php echo strtoupper(substr($member['first_name'] ?? 'N', 0, 1) . substr($member['last_name'] ?? 'A', 0, 1)); ?>
                                        </div>
                                        <div class="member-details">
                                            <div class="member-name"><?php echo htmlspecialchars(($member['first_name'] ?? '') . ' ' . ($member['last_name'] ?? '')); ?></div>
                                            <div class="member-number"><?php echo htmlspecialchars($member['member_number'] ?? ''); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="age-badge"><?php echo $age; ?> years</span></td>
                                <td><span class="package-badge"><?php echo htmlspecialchars(ucfirst($member['package_type'] ?? '')); ?></span></td>
                                <td><?php echo date('M d, Y', strtotime($member['created_at'] ?? 'now')); ?></td>
                                <td><span class="period-badge"><?php echo $age > 80 ? '5 months' : '4 months'; ?></span></td>
                                <td>
                                    <?php if ($daysRemaining <= 7): ?>
                                        <span class="maturity-badge completed"><i class="fas fa-clock"></i> <?php echo $daysRemaining; ?> days</span>
                                    <?php elseif ($daysRemaining <= 30): ?>
                                        <span class="maturity-badge pending"><i class="fas fa-hourglass-half"></i> <?php echo $daysRemaining; ?> days</span>
                                    <?php else: ?>
                                        <span class="maturity-badge not-set"><i class="fas fa-calendar"></i> <?php echo $daysRemaining; ?> days</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="status-badge pending">In Maturity</span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-hourglass-half"></i>
                                    </div>
                                    <div class="empty-text">No members in maturity period</div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>

    <!-- Defaulters Tab -->
    <div class="tab-pane fade" id="defaulters">
        <div class="members-table-card">
            <div class="table-header">
                <div>
                    <h3 class="table-title"><i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>Defaulted Members</h3>
                    <p class="table-subtitle">Members who missed monthly contributions beyond grace period</p>
                </div>
            </div>
            
            <div class="policy-alert warning">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <strong>Policy Section 11:</strong> Defaulted members must pay arrears to reactivate. Upon reactivation, a new 4-month maturity period begins.
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>MEMBER</th>
                            <th>PACKAGE</th>
                            <th>LAST PAYMENT</th>
                            <th>MONTHS OVERDUE</th>
                            <th>OUTSTANDING AMOUNT</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $defaultedMembers = array_filter($members ?? [], function($m) {
                            return ($m['status'] ?? '') === 'defaulted';
                        });
                        ?>
                        <?php if (!empty($defaultedMembers)): ?>
                            <?php foreach ($defaultedMembers as $member): ?>
                            <tr class="member-row">
                                <td>
                                    <div class="member-info">
                                        <div class="member-avatar purple">
                                            <?php echo strtoupper(substr($member['first_name'] ?? 'N', 0, 1) . substr($member['last_name'] ?? 'A', 0, 1)); ?>
                                        </div>
                                        <div class="member-details">
                                            <div class="member-name"><?php echo htmlspecialchars(($member['first_name'] ?? '') . ' ' . ($member['last_name'] ?? '')); ?></div>
                                            <div class="member-number"><?php echo htmlspecialchars($member['member_number'] ?? ''); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="package-badge"><?php echo htmlspecialchars(ucfirst($member['package_type'] ?? 'N/A')); ?></span></td>
                                <td>
                                    <?php
                                    $lastPayment = $member['last_payment_date'] ?? null;
                                    if ($lastPayment && $lastPayment != '0000-00-00 00:00:00') {
                                        echo '<div class="payment-date">' . date('M d, Y', strtotime($lastPayment)) . '</div>';
                                    } else {
                                        echo '<div class="payment-date no-payment">No payments</div>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <span class="overdue-badge"><i class="fas fa-exclamation-circle"></i> 3+ months</span>
                                </td>
                                <td>
                                    <div class="outstanding-amount">
                                        KES <?php echo number_format($member['outstanding_amount'] ?? 0); ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="/admin/member/<?php echo $member['id']; ?>" class="action-btn view" title="View Details">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <button class="action-btn reminder" onclick="sendReminder(<?php echo $member['id']; ?>)" title="Send Reminder">
                                            <i class="fas fa-envelope"></i> Remind
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="empty-text">No defaulted members</div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
    
</div>

<style>
/* Stats Grid - Universal Modern Design */
.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
    max-width: 100%;
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

.stat-icon.green {
    background: #D1FAE5;
    color: #10B981;
}

.stat-icon.yellow {
    background: #FEF3C7;
    color: #F59E0B;
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

.stat-change {
    font-size: 12px;
    color: #6B7280;
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.stat-change.positive {
    color: #10B981;
}

.stat-change.negative {
    color: #EF4444;
}

/* Content Layout */
.content-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 30px;
}

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

.filter-btn:hover:not(.active) {
    background: #F9FAFB;
}

/* Members Table Card */
.members-table-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    border: 1px solid #E5E7EB;
    max-width: 100%;
    overflow: hidden;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 16px;
}

.table-title {
    font-size: 18px;
    font-weight: 700;
    color: #1F2937;
}

.table-actions {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
}

.search-wrapper {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #9CA3AF;
    font-size: 14px;
}

.search-input {
    padding: 8px 16px 8px 40px;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    font-size: 13px;
    min-width: 250px;
}

.search-input:focus {
    outline: none;
    border-color: #7F3D9E;
    box-shadow: 0 0 0 3px rgba(127, 61, 158, 0.1);
}

.filter-select {
    padding: 8px 16px;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    font-size: 13px;
    background: white;
    color: #1F2937;
    cursor: pointer;
}

.filter-select:focus {
    outline: none;
    border-color: #7F3D9E;
}

.btn-export {
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    border: 1px solid #E5E7EB;
    background: white;
    color: #6B7280;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-export:hover {
    background: #7F3D9E;
    color: white;
    border-color: #7F3D9E;
}

/* Table Styling */
.table-responsive {
    overflow-x: auto;
    max-width: 100%;
    -webkit-overflow-scrolling: touch;
}

.table {
    width: 100%;
    min-width: 800px;
    border-collapse: separate;
    border-spacing: 0;
}

.table thead th {
    background: #7F3D9E;
    color: white;
    padding: 14px 16px;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
}

.table thead th:first-child {
    border-radius: 8px 0 0 0;
}

.table thead th:last-child {
    border-radius: 0 8px 0 0;
}

.table tbody td {
    padding: 16px;
    border-bottom: 1px solid #F3F4F6;
    font-size: 13px;
    color: #1F2937;
}

.member-row:hover {
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
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    color: white;
}

.member-avatar.purple {
    background: #7F3D9E;
}

.member-details {
    flex: 1;
}

.member-name {
    font-weight: 700;
    color: #1F2937;
    margin-bottom: 2px;
}

.member-number {
    font-size: 11px;
    color: #9CA3AF;
}

.contact-info {
    font-size: 13px;
}

.phone-number {
    color: #1F2937;
    margin-bottom: 2px;
}

.email-address {
    font-size: 11px;
    color: #9CA3AF;
}

.package-badge {
    background: #DBEAFE;
    color: #3B82F6;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
}

.status-badge {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
}

.status-badge.active {
    background: #D1FAE5;
    color: #10B981;
}

.status-badge.pending {
    background: #FEF3C7;
    color: #F59E0B;
}

.status-badge.inactive {
    background: #F3F4F6;
    color: #6B7280;
}

.status-badge.defaulted {
    background: #FEE2E2;
    color: #EF4444;
}

.status-badge.grace {
    background: #DBEAFE;
    color: #3B82F6;
}

.maturity-badge {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.maturity-badge.pending {
    background: #FEF3C7;
    color: #F59E0B;
}

.maturity-badge.completed {
    background: #D1FAE5;
    color: #10B981;
}

.maturity-badge.not-set {
    background: #F3F4F6;
    color: #9CA3AF;
}

.coverage-info {
    font-size: 12px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.coverage-info.active {
    color: #10B981;
}

.coverage-info.expired {
    color: #EF4444;
}

.coverage-info.not-set {
    color: #9CA3AF;
}

.payment-date {
    font-size: 12px;
    color: #1F2937;
}

.payment-date.no-payment {
    color: #9CA3AF;
}

.action-buttons {
    display: flex;
    gap: 8px;
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
    text-decoration: none;
}

.action-btn:hover {
    background: #7F3D9E;
    color: white;
    border-color: #7F3D9E;
}

.action-btn.activate:hover {
    background: #10B981;
    border-color: #10B981;
}

.action-btn.deactivate:hover {
    background: #EF4444;
    border-color: #EF4444;
}

.table-pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #E5E7EB;
}

.pagination-info {
    font-size: 13px;
    color: #9CA3AF;
}

.pagination-buttons {
    display: flex;
    gap: 8px;
}

.pagination-btn {
    padding: 6px 12px;
    border: 1px solid #E5E7EB;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    transition: all 0.2s;
}

.pagination-btn:hover:not(.active):not(:disabled) {
    background: #F9FAFB;
}

.pagination-btn.active {
    background: #7F3D9E;
    color: white;
    border-color: #7F3D9E;
}

.pagination-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    font-size: 48px;
    color: #E5E7EB;
    margin-bottom: 16px;
}

.empty-text {
    font-size: 16px;
    color: #9CA3AF;
}

/* Policy Alert */
.policy-alert {
    background: #EFF6FF;
    border: 1px solid #BFDBFE;
    border-radius: 8px;
    padding: 16px;
    display: flex;
    gap: 12px;
    margin-bottom: 24px;
    font-size: 13px;
    color: #1E40AF;
}

.policy-alert.warning {
    background: #FEF3C7;
    border-color: #FDE047;
    color: #92400E;
}

.policy-alert i {
    font-size: 16px;
    margin-top: 2px;
}

.table-subtitle {
    font-size: 12px;
    color: #9CA3AF;
    margin-top: 4px;
    margin-bottom: 0;
}

/* Additional Badge Styles */
.payment-status {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.payment-status.pending {
    background: #FEF3C7;
    color: #F59E0B;
}

.age-badge {
    background: #F3F4F6;
    color: #6B7280;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}

.period-badge {
    background: #DBEAFE;
    color: #3B82F6;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
}

.overdue-badge {
    background: #FEE2E2;
    color: #EF4444;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.outstanding-amount {
    font-size: 14px;
    font-weight: 700;
    color: #EF4444;
}

.action-btn.reminder:hover {
    background: #F59E0B;
    border-color: #F59E0B;
    color: white;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .content-layout {
        grid-template-columns: 1fr;
    }
    
    .stats-row {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }
}

@media (max-width: 768px) {
    .stats-row {
        grid-template-columns: 1fr;
    }
    
    .table-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .table-actions {
        width: 100%;
        flex-direction: column;
    }
    
    .search-input, .filter-select {
        width: 100%;
    }
    
    .members-table-card {
        padding: 16px;
    }
    
    .table {
        min-width: 600px;
    }
}
</style>

<script>
function activateMember(memberId) {
    if (confirm('Activate this member? This will verify registration fee payment and set coverage period.')) {
        fetch('/admin/member/' + memberId + '/activate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to activate member');
            }
        })
        .catch(error => {
            alert('Error: ' + error.message);
        });
    }
}

function deactivateMember(memberId) {
    if (confirm('Deactivate this member? They will lose access to benefits.')) {
        fetch('/admin/member/' + memberId + '/deactivate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to deactivate member');
            }
        })
        .catch(error => {
            alert('Error: ' + error.message);
        });
    }
}

function sendReminder(memberId) {
    if (confirm('Send payment reminder to this member?')) {
        // TODO: Implement reminder functionality
        alert('Reminder sent successfully!');
    }
}

function exportMemberData() {
    window.location.href = '/admin/members/export';
}

// Search functionality
document.getElementById('memberSearch')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#membersList tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});
 
// Filter functionality
document.getElementById('statusFilter')?.addEventListener('change', function(e) {
    const status = e.target.value;
    const rows = document.querySelectorAll('#membersList tbody tr');
    
    rows.forEach(row => {
        if (status === 'all') {
            row.style.display = '';
        } else {
            const statusBadge = row.querySelector('.badge');
            const rowStatus = statusBadge ? statusBadge.textContent.toLowerCase() : '';
            row.style.display = rowStatus.includes(status.replace('_', ' ')) ? '' : 'none';
        }
    });
});
</script>

<?php include_once __DIR__ . '/../layouts/admin-footer.php'; ?>
