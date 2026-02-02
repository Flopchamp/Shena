<?php 
$page = 'members'; 
include __DIR__ . '/../layouts/agent-header.php';

// Sample data - replace with actual database queries
$member = [
    'id' => 1,
    'member_number' => 'SH-882910',
    'first_name' => 'Thabo',
    'last_name' => 'Mbeki',
    'initials' => 'TM',
    'status' => 'ACTIVE',
    'location' => 'Gauteng, Johannesburg',
    'joined_date' => 'Jan 2023',
    'phone' => '+27 82 445 9901',
    'email' => 't.mbeki@gmail.com',
    'plan_name' => 'Family Premium Plus',
    'monthly_premium' => 450.00
];

$dependents = [
    [
        'initials' => 'LM',
        'name' => 'Lerato Mbeki',
        'relation' => 'Spouse',
        'id_number' => '850212 5543 081',
        'age' => 38
    ],
    [
        'initials' => 'SM',
        'name' => 'Sello Mbeki',
        'relation' => 'Child',
        'id_number' => '120815 5122 084',
        'age' => 11
    ],
    [
        'initials' => 'NM',
        'name' => 'Neo Mbeki',
        'relation' => 'Child',
        'id_number' => '150310 5991 083',
        'age' => 8
    ],
    [
        'initials' => 'AM',
        'name' => 'Anna Mbeki',
        'relation' => 'Parent',
        'id_number' => '620481 5012 082',
        'age' => 61
    ]
];

$payment_history = [
    [
        'month' => 'October 2023',
        'amount' => 450.00,
        'status' => 'ON-TIME',
        'status_class' => 'success',
        'description' => 'Paid via Debit Order'
    ],
    [
        'month' => 'September 2023',
        'amount' => 450.00,
        'status' => 'LATE',
        'status_class' => 'warning',
        'description' => 'Paid via EFT'
    ],
    [
        'month' => 'August 2023',
        'amount' => 450.00,
        'status' => 'ON-TIME',
        'status_class' => 'success',
        'description' => 'Paid via Debit Order'
    ],
    [
        'month' => 'July 2023',
        'amount' => 450.00,
        'status' => 'ON-TIME',
        'status_class' => 'success',
        'description' => 'Paid via Cash'
    ]
];
?>

<style>
/* Member Details Page Styles */
.member-details-container {
    padding: 30px 30px 40px 25px;
    background: #F8F9FA;
    min-height: calc(100vh - 80px);
}

/* Breadcrumb */
.breadcrumb-nav {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
}

.btn-back {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    border: 1px solid #E5E7EB;
    background: white;
    color: #6B7280;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-back:hover {
    background: #F9FAFB;
    border-color: #D1D5DB;
}

.breadcrumb-text {
    font-size: 14px;
    color: #6B7280;
}

.breadcrumb-text strong {
    color: #1F2937;
}

/* Member Header Card */
.member-header-card {
    background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
    border-radius: 16px;
    padding: 32px;
    margin-bottom: 24px;
    box-shadow: 0 4px 6px rgba(127, 32, 176, 0.2);
    position: relative;
    overflow: hidden;
}

.member-header-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 50%;
}

.member-header-content {
    display: flex;
    align-items: center;
    gap: 24px;
    position: relative;
    z-index: 1;
}

.member-avatar-large {
    width: 120px;
    height: 120px;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.95);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Playfair Display', serif;
    font-size: 48px;
    font-weight: 700;
    color: #7F20B0;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.member-info-header {
    flex: 1;
}

.member-name-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}

.member-name-header {
    font-family: 'Playfair Display', serif;
    font-size: 36px;
    font-weight: 700;
    color: white;
    margin: 0;
}

.status-badge-header {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    background: #059669;
    color: white;
}

.status-badge-header i {
    font-size: 8px;
}

.member-meta {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 16px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    color: rgba(255, 255, 255, 0.9);
    font-size: 14px;
}

.meta-item i {
    color: rgba(255, 255, 255, 0.7);
}

.member-actions {
    display: flex;
    gap: 12px;
    margin-left: auto;
}

.btn-initiate-claim,
.btn-assist-payment {
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
}

.btn-initiate-claim {
    background: rgba(255, 255, 255, 0.95);
    color: #7F20B0;
}

.btn-initiate-claim:hover {
    background: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-assist-payment {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.btn-assist-payment:hover {
    background: rgba(255, 255, 255, 0.25);
}

/* Main Grid */
.details-grid {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 24px;
}

/* Dependent List Section */
.dependent-list-card {
    background: white;
    border-radius: 16px;
    padding: 32px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.section-header-details {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid #E5E7EB;
}

.section-title-details {
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-icon-details {
    color: #7F20B0;
    font-size: 20px;
}

.section-title-details h3 {
    font-family: 'Playfair Display', serif;
    font-size: 20px;
    font-weight: 700;
    color: #1F2937;
    margin: 0;
}

.section-subtitle {
    font-size: 12px;
    color: #9CA3AF;
    margin-top: 2px;
}

.btn-add-dependent {
    color: #7F20B0;
    font-size: 14px;
    font-weight: 600;
    background: none;
    border: none;
    cursor: pointer;
    text-decoration: underline;
}

/* Dependent Table */
.dependent-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.dependent-table thead th {
    font-size: 11px;
    font-weight: 700;
    color: #9CA3AF;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid #E5E7EB;
}

.dependent-table tbody tr {
    transition: background-color 0.2s;
}

.dependent-table tbody tr:hover {
    background: #F9FAFB;
}

.dependent-table tbody td {
    padding: 16px;
    border-bottom: 1px solid #F3F4F6;
}

.dependent-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.dependent-avatar {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 14px;
    flex-shrink: 0;
}

.dependent-name {
    font-size: 14px;
    font-weight: 600;
    color: #1F2937;
}

.dependent-relation {
    font-size: 13px;
    color: #6B7280;
}

.dependent-id {
    font-size: 13px;
    color: #6B7280;
    font-family: monospace;
}

.dependent-age {
    font-size: 14px;
    color: #4B5563;
}

/* Contact and Plan Section */
.contact-plan-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}

.info-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.info-card-header {
    font-size: 11px;
    font-weight: 700;
    color: #9CA3AF;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 16px;
}

.info-item {
    margin-bottom: 16px;
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 11px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
}

.info-label i {
    color: #9CA3AF;
}

.info-value {
    font-size: 15px;
    font-weight: 600;
    color: #1F2937;
}

.plan-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
}

.plan-icon {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
}

/* Payment History Section */
.payment-history-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    margin-bottom: 24px;
}

.payment-history-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid #E5E7EB;
}

.payment-icon {
    color: #059669;
    font-size: 20px;
}

.payment-history-header h3 {
    font-size: 18px;
    font-weight: 700;
    color: #1F2937;
    margin: 0;
}

.payment-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 16px 0;
    border-bottom: 1px solid #F3F4F6;
}

.payment-item:last-child {
    border-bottom: none;
}

.payment-info h6 {
    font-size: 14px;
    font-weight: 600;
    color: #1F2937;
    margin: 0 0 4px 0;
}

.payment-info p {
    font-size: 12px;
    color: #9CA3AF;
    margin: 0;
}

.payment-amount-status {
    text-align: right;
}

.payment-amount {
    font-size: 16px;
    font-weight: 700;
    color: #1F2937;
    margin-bottom: 4px;
}

.payment-status-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 700;
}

.payment-status-badge.success {
    background: #D1FAE5;
    color: #059669;
}

.payment-status-badge.warning {
    background: #FEF3C7;
    color: #D97706;
}

.download-statement {
    text-align: center;
    padding-top: 16px;
    margin-top: 16px;
    border-top: 1px solid #E5E7EB;
}

.btn-download-statement {
    color: #7F20B0;
    font-size: 14px;
    font-weight: 600;
    background: none;
    border: none;
    cursor: pointer;
    text-decoration: underline;
}

/* Support Tip Card */
.support-tip-card {
    background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
    border-radius: 16px;
    padding: 24px;
    color: white;
    box-shadow: 0 4px 12px rgba(127, 32, 176, 0.3);
}

.support-tip-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}

.support-tip-icon {
    width: 32px;
    height: 32px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.support-tip-header h4 {
    font-size: 16px;
    font-weight: 700;
    margin: 0;
}

.support-tip-text {
    font-size: 13px;
    line-height: 1.6;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 16px;
}

.btn-view-playbook {
    background: white;
    color: #7F20B0;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 13px;
    width: 100%;
    cursor: pointer;
    transition: all 0.2s;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-view-playbook:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
}

/* Responsive */
@media (max-width: 1200px) {
    .details-grid {
        grid-template-columns: 1fr;
    }

    .contact-plan-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .member-details-container {
        padding: 20px 15px;
    }

    .member-header-card {
        padding: 24px;
    }

    .member-header-content {
        flex-direction: column;
        text-align: center;
    }

    .member-actions {
        margin-left: 0;
        width: 100%;
        flex-direction: column;
    }

    .btn-initiate-claim,
    .btn-assist-payment {
        width: 100%;
        justify-content: center;
    }

    .member-avatar-large {
        width: 100px;
        height: 100px;
        font-size: 40px;
    }

    .member-name-header {
        font-size: 28px;
    }

    .member-meta {
        flex-direction: column;
        gap: 8px;
    }

    .dependent-table {
        display: block;
        overflow-x: auto;
    }
}
</style>

<div class="member-details-container">
    <!-- Breadcrumb -->
    <div class="breadcrumb-nav">
        <button class="btn-back" onclick="window.history.back()">
            <i class="fas fa-arrow-left"></i>
        </button>
        <span class="breadcrumb-text">Portfolio → <strong>Member Details</strong></span>
    </div>

    <!-- Member Header Card -->
    <div class="member-header-card">
        <div class="member-header-content">
            <div class="member-avatar-large"><?php echo $member['initials']; ?></div>
            <div class="member-info-header">
                <div class="member-name-row">
                    <h1 class="member-name-header"><?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?></h1>
                    <span class="status-badge-header">
                        <i class="fas fa-circle"></i>
                        <?php echo $member['status']; ?>
                    </span>
                </div>
                <div class="member-meta">
                    <span class="meta-item">
                        <i class="fas fa-id-card"></i>
                        <?php echo $member['member_number']; ?>
                    </span>
                    <span class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <?php echo $member['location']; ?>
                    </span>
                    <span class="meta-item">
                        <i class="fas fa-calendar"></i>
                        Joined <?php echo $member['joined_date']; ?>
                    </span>
                </div>
            </div>
            <div class="member-actions">
                <button class="btn-initiate-claim">
                    <i class="fas fa-file-medical"></i>
                    Initiate Claim
                </button>
                <button class="btn-assist-payment">
                    <i class="fas fa-hand-holding-usd"></i>
                    Assist Payment
                </button>
            </div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="details-grid">
        <!-- Left Column -->
        <div>
            <!-- Dependent List -->
            <div class="dependent-list-card">
                <div class="section-header-details">
                    <div class="section-title-details">
                        <i class="fas fa-users section-icon-details"></i>
                        <div>
                            <h3>Dependent List</h3>
                            <p class="section-subtitle">4 family members covered under this policy</p>
                        </div>
                    </div>
                    <button class="btn-add-dependent">Add Dependent</button>
                </div>

                <table class="dependent-table">
                    <thead>
                        <tr>
                            <th>NAME</th>
                            <th>RELATION</th>
                            <th>ID NUMBER</th>
                            <th>AGE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dependents as $dependent): ?>
                        <tr>
                            <td>
                                <div class="dependent-info">
                                    <div class="dependent-avatar"><?php echo $dependent['initials']; ?></div>
                                    <span class="dependent-name"><?php echo htmlspecialchars($dependent['name']); ?></span>
                                </div>
                            </td>
                            <td class="dependent-relation"><?php echo $dependent['relation']; ?></td>
                            <td class="dependent-id"><?php echo $dependent['id_number']; ?></td>
                            <td class="dependent-age"><?php echo $dependent['age']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Contact Information & Plan Details -->
            <div class="contact-plan-grid">
                <!-- Contact Information -->
                <div class="info-card">
                    <div class="info-card-header">Contact Information</div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-phone"></i>
                            Mobile Phone
                        </div>
                        <div class="info-value"><?php echo $member['phone']; ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-envelope"></i>
                            Email Address
                        </div>
                        <div class="info-value"><?php echo $member['email']; ?></div>
                    </div>
                </div>

                <!-- Plan Details -->
                <div class="info-card">
                    <div class="info-card-header">Plan Details</div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-shield-alt"></i>
                            Active Plan
                        </div>
                        <div class="plan-badge">
                            <div class="plan-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="info-value"><?php echo $member['plan_name']; ?></div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-money-bill-wave"></i>
                            Monthly Premium
                        </div>
                        <div class="info-value">R <?php echo number_format($member['monthly_premium'], 2); ?> / month</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div>
            <!-- Payment History -->
            <div class="payment-history-card">
                <div class="payment-history-header">
                    <i class="fas fa-history payment-icon"></i>
                    <h3>Payment History</h3>
                </div>

                <?php foreach ($payment_history as $payment): ?>
                <div class="payment-item">
                    <div class="payment-info">
                        <h6><?php echo $payment['month']; ?></h6>
                        <p><?php echo $payment['description']; ?></p>
                    </div>
                    <div class="payment-amount-status">
                        <div class="payment-amount">R <?php echo number_format($payment['amount'], 2); ?></div>
                        <span class="payment-status-badge <?php echo $payment['status_class']; ?>">
                            <?php echo $payment['status']; ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>

                <div class="download-statement">
                    <button class="btn-download-statement">Download Full Statement</button>
                </div>
            </div>

            <!-- Support Tip -->
            <div class="support-tip-card">
                <div class="support-tip-header">
                    <div class="support-tip-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h4>Support Tip</h4>
                </div>
                <p class="support-tip-text">
                    Member missed last payment: two last months. Remind them to settle the R 25.00 arrears to avoid policy degradation.
                </p>
                <button class="btn-view-playbook">View Agent Playbook</button>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/agent-footer.php'; ?>
