
<?php
$page = 'payments';
include VIEWS_PATH . '/layouts/member-header.php';

// Sample data
$payments = $payments ?? [];
$total_paid = array_sum(array_column($payments, 'amount'));
$pending_count = count(array_filter($payments, fn($p) => $p['status'] === 'pending'));

// Sample payment data if empty
if (empty($payments)) {
    $payments = [
        ['payment_date' => '2023-10-02', 'amount' => 1200, 'period' => 'October 2023', 'transaction_id' => 'RK1R2L8W1P', 'status' => 'completed'],
        ['payment_date' => '2023-09-05', 'amount' => 1200, 'period' => 'September 2023', 'transaction_id' => 'R132K1S5X4', 'status' => 'completed'],
        ['payment_date' => '2023-08-03', 'amount' => 1200, 'period' => 'August 2023', 'transaction_id' => 'RH81M7N9V2', 'status' => 'completed'],
        ['payment_date' => '2023-07-01', 'amount' => 1200, 'period' => 'July 2023', 'transaction_id' => 'RG84L2Q0A1', 'status' => 'completed'],
        ['payment_date' => '2023-06-10', 'amount' => 1200, 'period' => 'June 2023', 'transaction_id' => 'RF118BN3Z9', 'status' => 'pending'],
    ];
    $total_paid = 12400;
}
?>

<style>
main {
    padding: 0 !important;
    margin: 0 !important;
}

.payments-container {
    padding: 30px 30px 40px 25px;
    background: #F8F9FC;
    max-width: 100%;
    margin: 0;
}

.page-header {
    margin-bottom: 30px;
}

.page-header h1 {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1F2937;
    margin: 0 0 4px 0;
}

.page-header p {
    font-size: 0.9rem;
    color: #6B7280;
    margin: 0;
}

.main-grid {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 30px;
    align-items: start;
}

.total-contributions-card {
    background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
    border-radius: 20px;
    padding: 35px 40px;
    color: white;
    margin-bottom: 40px;
    position: relative;
    overflow: hidden;
}

.total-contributions-card::after {
    content: 'Ksh';
    position: absolute;
    right: 40px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 150px;
    font-weight: 700;
    opacity: 0.05;
}

.total-contributions-card h4 {
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 2px;
    margin: 0 0 12px 0;
    color: rgba(255, 255, 255, 0.8);
    position: relative;
    z-index: 1;
}

.total-contributions-card h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0 0 12px 0;
    position: relative;
    z-index: 1;
}

.total-contributions-card p {
    font-size: 0.9rem;
    margin: 0;
    color: rgba(255, 255, 255, 0.9);
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    gap: 8px;
}

.total-contributions-card p i {
    font-size: 0.8rem;
}

.membership-card {
    background: white;
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    margin-bottom: 30px;
}

.membership-card h4 {
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 1.5px;
    color: #9CA3AF;
    margin: 0 0 15px 0;
}

.membership-status {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.membership-status h2 {
    font-size: 2rem;
    font-weight: 700;
    color: #1F2937;
    margin: 0;
}

.status-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #D1FAE5;
    color: #059669;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.status-badge i {
    width: 8px;
    height: 8px;
    background: #059669;
    border-radius: 50%;
}

.verification-icon {
    color: #7F3D9E;
    font-size: 2rem;
    margin-top: 10px;
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

.section-controls {
    display: flex;
    gap: 15px;
    align-items: center;
}

.filter-btn, .year-selector {
    background: white;
    border: 1px solid #E5E7EB;
    padding: 8px 16px;
    border-radius: 10px;
    font-size: 0.9rem;
    font-weight: 500;
    color: #374151;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
}

.filter-btn:hover, .year-selector:hover {
    border-color: #7F3D9E;
    color: #7F3D9E;
}

.year-selector i {
    color: #7F3D9E;
}

.payments-table {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.payments-table table {
    width: 100%;
    border-collapse: collapse;
}

.payments-table thead {
    background: #F9FAFB;
}

.payments-table thead th {
    padding: 18px 20px;
    text-align: left;
    font-size: 0.85rem;
    font-weight: 700;
    color: #7F3D9E;
    letter-spacing: 0.5px;
}

.payments-table tbody tr {
    border-bottom: 1px solid #F3F4F6;
    transition: all 0.3s;
}

.payments-table tbody tr:hover {
    background: #F9FAFB;
}

.payments-table tbody tr:last-child {
    border-bottom: none;
}

.payments-table tbody td {
    padding: 20px;
    font-size: 0.9rem;
    color: #1F2937;
}

.payments-table tbody td:first-child {
    font-weight: 500;
    color: #6B7280;
}

.payment-status {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.payment-status.success {
    background: #D1FAE5;
    color: #059669;
}

.payment-status.pending {
    background: #FEF3C7;
    color: #D97706;
}

.payment-status.failed {
    background: #FEE2E2;
    color: #DC2626;
}

/* Right Sidebar */
.sidebar-right {
    display: flex;
    flex-direction: column;
    gap: 20px;
    position: sticky;
    top: 20px;
}

.quick-pay-card {
    background: white;
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.quick-pay-card h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1F2937;
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.quick-pay-card h3 i {
    color: #7F3D9E;
}

.paybill-info {
    margin: 25px 0;
}

.paybill-info p {
    font-size: 0.75rem;
    color: #6B7280;
    letter-spacing: 1px;
    margin: 0 0 8px 0;
}

.paybill-info h2 {
    font-size: 1.8rem;
    font-weight: 700;
    color: #1F2937;
    margin: 0 0 20px 0;
}

.account-ref {
    margin-top: 15px;
}

.account-ref p {
    font-size: 0.75rem;
    color: #6B7280;
    letter-spacing: 1px;
    margin: 0 0 8px 0;
}

.account-ref h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #7F3D9E;
    margin: 0;
}

.how-to-pay-btn {
    background: #7F3D9E;
    color: white;
    border: none;
    padding: 14px 0;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.95rem;
    cursor: pointer;
    width: 100%;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.how-to-pay-btn:hover {
    background: #6B2D8A;
    transform: translateY(-1px);
}

.statements-card {
    background: white;
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.statements-card h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1F2937;
    margin: 0 0 12px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.statements-card h3 i {
    color: #F59E0B;
}

.statements-card p {
    font-size: 0.85rem;
    color: #6B7280;
    line-height: 1.6;
    margin: 0 0 20px 0;
}

.download-btn {
    background: white;
    color: #7F3D9E;
    border: 2px solid #7F3D9E;
    padding: 12px 0;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.95rem;
    cursor: pointer;
    width: 100%;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.download-btn:hover {
    background: #F3E8FF;
}

@media (max-width: 1024px) {
    .main-grid {
        grid-template-columns: 1fr;
    }
    
    .payments-container {
        padding: 20px;
    }
}

/* Payment Modal Styling */
#paymentModal .modal-content {
    border-radius: 20px;
    border: none;
}

#paymentModal .modal-header {
    background: white;
    padding: 25px 30px;
    border-bottom: 1px solid #E5E7EB;
    border-radius: 20px 20px 0 0;
}

#paymentModal .modal-header .modal-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1F2937;
    margin: 0;
}

#paymentModal .modal-header .btn-close {
    font-size: 1.2rem;
}

#paymentModal .modal-body {
    padding: 30px;
}

#paymentModal .alert-info {
    background: #E0F2FE;
    border: none;
    border-radius: 12px;
    padding: 15px 20px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 12px;
}

#paymentModal .alert-info i {
    color: #0284C7;
    font-size: 1.2rem;
}

#paymentModal .payment-method-label {
    font-size: 1rem;
    font-weight: 600;
    color: #1F2937;
    margin-bottom: 12px;
    display: block;
}

.payment-method-tabs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
    margin-bottom: 30px;
    border-radius: 8px;
    overflow: hidden;
}

.payment-method-tab {
    padding: 14px 20px;
    border: 1px solid #E5E7EB;
    background: white;
    color: #6B7280;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s;
    border-right: none;
}

.payment-method-tab:last-child {
    border-right: 1px solid #E5E7EB;
}

.payment-method-tab.active {
    background: #2563EB;
    color: white;
    border-color: #2563EB;
}

.payment-method-tab:not(.active):hover {
    background: #F9FAFB;
}

.payment-section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1rem;
    font-weight: 600;
    color: #1F2937;
    margin-bottom: 12px;
}

.payment-section-title i {
    color: #2563EB;
}

.payment-description {
    font-size: 0.85rem;
    color: #6B7280;
    margin-bottom: 25px;
}

#paymentModal .form-label {
    font-size: 0.9rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

#paymentModal .form-control,
#paymentModal .form-select {
    border: 1.5px solid #E5E7EB;
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 0.95rem;
    transition: all 0.3s;
}

#paymentModal .form-control:focus,
#paymentModal .form-select:focus {
    border-color: #2563EB;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

#paymentModal .form-control::placeholder {
    color: #9CA3AF;
}

#paymentModal .text-muted {
    font-size: 0.8rem;
    color: #6B7280;
    margin-top: 6px;
    display: block;
}

#paymentModal .alert-warning {
    background: #FEF3C7;
    border: none;
    border-radius: 10px;
    padding: 14px 16px;
    margin: 20px 0;
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

#paymentModal .alert-warning i {
    color: #D97706;
    font-size: 1rem;
    margin-top: 2px;
}

.btn-send-payment {
    background: #2563EB;
    color: white;
    border: none;
    padding: 14px 0;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.95rem;
    cursor: pointer;
    width: 100%;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.btn-send-payment:hover {
    background: #1D4ED8;
}

.btn-send-payment:disabled {
    background: #93C5FD;
    cursor: not-allowed;
}

#paymentModal .modal-footer {
    padding: 20px 30px;
    border-top: 1px solid #E5E7EB;
    border-radius: 0 0 20px 20px;
}

#paymentModal .modal-footer .btn {
    padding: 12px 30px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.95rem;
}

#paymentModal .modal-footer .btn-secondary {
    background: #F3F4F6;
    color: #374151;
    border: none;
}

#paymentModal .modal-footer .btn-secondary:hover {
    background: #E5E7EB;
}

.paybill-instructions {
    background: #F9FAFB;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}

.paybill-instructions p {
    margin: 0 0 8px 0;
    font-size: 0.9rem;
}

.paybill-instructions strong {
    color: #1F2937;
}

.paybill-instructions .paybill-number {
    color: #2563EB;
    font-size: 1.3rem;
    font-weight: 700;
}

.paybill-instructions .account-number {
    color: #059669;
    font-size: 1.1rem;
    font-weight: 600;
}

.payment-steps {
    margin: 20px 0;
}

.payment-steps h6 {
    font-size: 1rem;
    font-weight: 600;
    color: #1F2937;
    margin-bottom: 15px;
}

.payment-steps ol {
    padding-left: 20px;
}

.payment-steps ol li {
    font-size: 0.85rem;
    color: #4B5563;
    margin-bottom: 8px;
    line-height: 1.6;
}

.payment-steps ol li strong {
    color: #1F2937;
}
</style>

<div class="payments-container">
    <div class="page-header">
        <h1>Member Portal</h1>
        <p>Contribution History Dashboard</p>
    </div>

    <div class="main-grid">
        <div>
            <!-- Total Contributions Card -->
            <div class="total-contributions-card">
                <h4>TOTAL CONTRIBUTIONS 2023</h4>
                <h2>Ksh <?php echo number_format($total_paid, 2); ?></h2>
                <p><i class="fas fa-arrow-up"></i> 12% increase from 2022</p>
            </div>

            <!-- Contribution Logs -->
            <div class="section-header">
                <h3>Contribution Logs</h3>
                <div class="section-controls">
                    <button class="filter-btn">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <button class="year-selector">
                        <i class="fas fa-calendar"></i> 2023
                    </button>
                </div>
            </div>

            <!-- Payments Table -->
            <div class="payments-table">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Ref Number</th>
                            <th>Amount</th>
                            <th>Period</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td><?php echo date('M d, Y', strtotime($payment['payment_date'])); ?></td>
                            <td><?php echo htmlspecialchars($payment['transaction_id']); ?></td>
                            <td><?php echo number_format($payment['amount'], 2); ?></td>
                            <td><?php echo htmlspecialchars($payment['period']); ?></td>
                            <td>
                                <span class="payment-status <?php echo $payment['status'] === 'completed' ? 'success' : ($payment['status'] === 'failed' ? 'failed' : 'pending'); ?>">
                                    <?php echo strtoupper($payment['status']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="sidebar-right">
            <!-- Membership Standing -->
            <div class="membership-card">
                <h4>MEMBERSHIP STANDING</h4>
                <div class="membership-status">
                    <h2>ACTIVE</h2>
                    <div class="verification-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="status-badge">
                    <i></i>
                    <span>Good Standing</span>
                </div>
            </div>

            <!-- Quick Pay -->
            <div class="quick-pay-card">
                <h3><i class="fas fa-mobile-alt"></i> Quick Pay</h3>
                
                <div class="paybill-info">
                    <p>M-PESA PAYBILL</p>
                    <h2>4163987</h2>
                </div>

                <div class="account-ref">
                    <p>YOUR ACCOUNT REF</p>
                    <h3>SH-99238</h3>
                </div>

                <button class="how-to-pay-btn" data-bs-toggle="modal" data-bs-target="#paymentModal">
                    <i class="fas fa-info-circle"></i> HOW TO PAY
                </button>
            </div>

            <!-- Statements -->
            <div class="statements-card">
                <h3><i class="fas fa-file-invoice"></i> Statements</h3>
                <p>Generate a certified record of your contributions for official use or personal audit.</p>
                <button class="download-btn">
                    <i class="fas fa-download"></i> DOWNLOAD FULL STATEMENT
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Make Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <span>Choose your preferred payment method</span>
                </div>
                
                <!-- Payment Method Selection -->
                <label class="payment-method-label">Payment Method:</label>
                <div class="payment-method-tabs">
                    <button type="button" class="payment-method-tab active" data-method="stk">
                        <i class="fas fa-mobile-alt"></i> STK Push
                    </button>
                    <button type="button" class="payment-method-tab" data-method="manual">
                        <i class="fas fa-hand-holding-usd"></i> Manual Paybill
                    </button>
                </div>
                
                <!-- STK Push Section -->
                <div id="stkPushSection">
                    <h6 class="payment-section-title">
                        <i class="fas fa-mobile-alt"></i>
                        Pay via M-Pesa STK Push
                    </h6>
                    <p class="payment-description">Enter your M-Pesa number to receive a payment prompt on your phone.</p>
                    
                    <form id="stkPushForm">
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phoneNumber" 
                                   placeholder="07XXXXXXXX or 2547XXXXXXXX" required
                                   value="<?php echo $member['phone'] ?? ''; ?>">
                            <small class="text-muted">Enter your M-Pesa registered phone number</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <input type="number" class="form-control" id="amount" 
                                   value="<?php echo $member['monthly_contribution'] ?? 500; ?>" 
                                   min="1" step="0.01" required readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Payment Type</label>
                            <select class="form-select" id="paymentType">
                                <option value="monthly" selected>Monthly Contribution</option>
                                <option value="registration">Registration Fee</option>
                                <option value="reactivation">Reactivation Fee</option>
                            </select>
                        </div>
                        
                        <div class="alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>You will receive a payment prompt on your phone. Enter your M-Pesa PIN to complete the payment.</span>
                        </div>
                        
                        <button type="submit" class="btn-send-payment" id="initiateSTKBtn">
                            <i class="fas fa-paper-plane"></i> Send Payment Request
                        </button>
                    </form>
                    
                    <div id="stkPushStatus" class="mt-3" style="display: none;">
                        <div class="alert alert-success">
                            <i class="fas fa-spinner fa-spin"></i> <span id="statusMessage">Processing payment...</span>
                        </div>
                    </div>
                </div>
                
                <!-- Manual Paybill Section -->
                <div id="manualPaybillSection" style="display: none;">
                    <h6 class="payment-section-title">
                        <i class="fas fa-hand-holding-usd"></i>
                        Pay via M-Pesa Paybill
                    </h6>
                    <div class="paybill-instructions">
                        <p><strong>Paybill Number:</strong> <span class="paybill-number">4163987</span></p>
                        <p><strong>Account Number:</strong> <span class="account-number"><?php echo $member['member_number'] ?? 'SH-99238'; ?></span></p>
                        <p><strong>Amount:</strong> <span style="color: #DC2626; font-weight: 600;">KES <?php echo number_format($member['monthly_contribution'] ?? 500, 2); ?></span></p>
                    </div>
                    
                    <div class="payment-steps">
                        <h6>Steps to Pay:</h6>
                        <ol>
                            <li>Go to M-Pesa menu on your phone</li>
                            <li>Select <strong>Lipa na M-Pesa</strong></li>
                            <li>Select <strong>Pay Bill</strong></li>
                            <li>Enter Business Number: <strong>4163987</strong></li>
                            <li>Enter Account Number: <strong><?php echo $member['member_number'] ?? 'SH-99238'; ?></strong></li>
                            <li>Enter Amount: <strong><?php echo number_format($member['monthly_contribution'] ?? 500, 2); ?></strong></li>
                            <li>Enter your M-Pesa PIN and confirm</li>
                        </ol>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <span>Payment will reflect automatically in your account within a few minutes.</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Verify Transaction Modal -->
<div class="modal fade" id="verifyTransactionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="fas fa-check-circle"></i> Verify M-Pesa Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> If you completed a payment but it shows as pending or failed, verify it here using your M-Pesa transaction code.
                </div>
                
                <form id="verifyTransactionForm">
                    <div class="mb-3">
                        <label class="form-label">M-Pesa Transaction Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase" id="transactionCode" 
                               placeholder="e.g., RCH12ABC34" required maxlength="15">
                        <small class="text-muted">Check your M-Pesa message for the transaction code</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Phone Number Used <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="verifyPhoneNumber" 
                               placeholder="07XXXXXXXX or 2547XXXXXXXX" required
                               value="<?php echo $member['phone'] ?? ''; ?>">
                        <small class="text-muted">Enter the phone number you paid from</small>
                    </div>
                    
                    <div class="alert alert-warning small">
                        <strong>Note:</strong> This will search for your pending or failed payments within the last 7 days that match your transaction code and phone number.
                    </div>
                    
                    <button type="submit" class="btn btn-warning w-100" id="verifyBtn">
                        <i class="fas fa-search"></i> Verify Transaction
                    </button>
                </form>
                
                <div id="verifyStatus" class="mt-3" style="display: none;">
                    <div class="alert">
                        <span id="verifyMessage"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Payment method toggle
document.querySelectorAll('input[name="paymentMethod"]').forEach(radio => {
    radio.addEventListener('change', function() {
        if (this.value === 'stk') {
            document.getElementById('stkPushSection').style.display = 'block';
            document.getElementById('manualPaybillSection').style.display = 'none';
        } else {
            document.getElementById('stkPushSection').style.display = 'none';
            document.getElementById('manualPaybillSection').style.display = 'block';
        }
    });
});

// STK Push Form Submission
document.getElementById('stkPushForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('initiateSTKBtn');
    const statusDiv = document.getElementById('stkPushStatus');
    const statusMsg = document.getElementById('statusMessage');
    
    const phoneNumber = document.getElementById('phoneNumber').value;
    const amount = document.getElementById('amount').value;
    const paymentType = document.getElementById('paymentType').value;
    
    // Validate phone number
    if (!phoneNumber || phoneNumber.length < 9) {
        alert('Please enter a valid phone number');
        return;
    }
    
    // Disable button and show loading
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
    statusDiv.style.display = 'none';
    
    try {
        const response = await fetch('/payment/initiate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                member_id: <?php echo $member['id'] ?? 0; ?>,
                phone_number: phoneNumber,
                amount: amount,
                payment_type: paymentType
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            statusDiv.style.display = 'block';
            statusDiv.querySelector('.alert').className = 'alert alert-success';
            statusMsg.innerHTML = '<i class="fas fa-check-circle"></i> ' + data.message;
            
            // Poll for payment status
            if (data.checkout_request_id) {
                pollPaymentStatus(data.checkout_request_id);
            }
            
            // Reset form after 3 seconds
            setTimeout(() => {
                statusDiv.style.display = 'none';
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Payment Request';
            }, 3000);
        } else {
            statusDiv.style.display = 'block';
            statusDiv.querySelector('.alert').className = 'alert alert-danger';
            statusMsg.innerHTML = '<i class="fas fa-times-circle"></i> ' + (data.error || 'Payment initiation failed');
            
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Payment Request';
        }
    } catch (error) {
        console.error('Payment error:', error);
        statusDiv.style.display = 'block';
        statusDiv.querySelector('.alert').className = 'alert alert-danger';
        statusMsg.innerHTML = '<i class="fas fa-times-circle"></i> Network error. Please try again.';
        
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Payment Request';
    }
});

// Poll payment status
function pollPaymentStatus(checkoutRequestId) {
    let attempts = 0;
    const maxAttempts = 30; // Poll for 30 seconds
    
    const interval = setInterval(async () => {
        attempts++;
        
        if (attempts > maxAttempts) {
            clearInterval(interval);
            document.getElementById('statusMessage').innerHTML = 
                '<i class="fas fa-info-circle"></i> Please check your payment history for status.';
            return;
        }
        
        try {
            const response = await fetch(`/payment/status?checkout_request_id=${checkoutRequestId}`);
            const data = await response.json();
            
            if (data.success && data.status) {
                if (data.status.ResultCode === '0') {
                    // Payment successful
                    clearInterval(interval);
                    document.getElementById('statusMessage').innerHTML = 
                        '<i class="fas fa-check-circle"></i> Payment completed successfully!';
                    
                    // Reload page after 2 seconds
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else if (data.status.ResultCode !== undefined) {
                    // Payment failed
                    clearInterval(interval);
                    const statusDiv = document.getElementById('stkPushStatus');
                    statusDiv.querySelector('.alert').className = 'alert alert-danger';
                    document.getElementById('statusMessage').innerHTML = 
                        '<i class="fas fa-times-circle"></i> ' + (data.status.ResultDesc || 'Payment failed');
                }
            }
        } catch (error) {
            console.error('Status check error:', error);
        }
    }, 1000);
}

// Transaction Verification Form
document.getElementById('verifyTransactionForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('verifyBtn');
    const statusDiv = document.getElementById('verifyStatus');
    const statusMsg = document.getElementById('verifyMessage');
    
    const transactionCode = document.getElementById('transactionCode').value.trim();
    const phoneNumber = document.getElementById('verifyPhoneNumber').value.trim();
    
    // Validate inputs
    if (!transactionCode || transactionCode.length < 8) {
        alert('Please enter a valid M-Pesa transaction code');
        return;
    }
    
    if (!phoneNumber || phoneNumber.length < 9) {
        alert('Please enter a valid phone number');
        return;
    }
    
    // Disable button and show loading
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';
    statusDiv.style.display = 'none';
    
    try {
        const formData = new FormData();
        formData.append('transaction_code', transactionCode);
        formData.append('phone_number', phoneNumber);
        
        const response = await fetch('/payments/verify-transaction', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        statusDiv.style.display = 'block';
        
        if (data.success) {
            statusDiv.querySelector('.alert').className = 'alert alert-success';
            statusMsg.innerHTML = '<i class="fas fa-check-circle"></i> ' + data.message;
            
            // Reload page after 2 seconds
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            statusDiv.querySelector('.alert').className = 'alert alert-danger';
            statusMsg.innerHTML = '<i class="fas fa-times-circle"></i> ' + (data.message || 'Verification failed');
            
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-search"></i> Verify Transaction';
        }
    } catch (error) {
        console.error('Verification error:', error);
        statusDiv.style.display = 'block';
        statusDiv.querySelector('.alert').className = 'alert alert-danger';
        statusMsg.innerHTML = '<i class="fas fa-times-circle"></i> Network error. Please try again.';
        
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-search"></i> Verify Transaction';
    }
});
</script>

<?php include VIEWS_PATH . '/layouts/member-footer.php'; ?>
