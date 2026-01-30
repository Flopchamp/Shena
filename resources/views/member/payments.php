
<?php
$page = 'payments';
include VIEWS_PATH . '/layouts/member-header.php';
?>

<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-money-bill-wave"></i> Payment History</h2>
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 bg-success text-white">
                <div class="card-body">
                    <h6>Total Paid</h6>
                    <h3>KES <?php echo number_format($total_paid, 2); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 bg-warning text-white">
                <div class="card-body">
                    <h6>Pending Payments</h6>
                    <h3><?php echo $pending_count; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 bg-info text-white">
                <div class="card-body">
                    <h6>Monthly Contribution</h6>
                    <h3>KES <?php echo number_format($member->monthly_contribution, 2); ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-white">
            <h5 class="mb-0"><i class="fas fa-history"></i> Payment History</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#paymentModal">
                <i class="fas fa-plus"></i> Make Payment
            </button>
        </div>
        <div class="card-body">
            <?php if (!empty($payments)): ?>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td><?php echo date('M j, Y', strtotime($payment['payment_date'] ?? $payment['created_at'])); ?></td>
                            <td>KES <?php echo number_format($payment['amount'], 2); ?></td>
                            <td><?php echo !empty($payment['payment_type']) ? ucfirst($payment['payment_type']) : 'Monthly'; ?></td>
                            <td><?php echo !empty($payment['payment_method']) ? strtoupper($payment['payment_method']) : 'N/A'; ?></td>
                            <td><?php echo $payment['transaction_id'] ?? 'N/A'; ?></td>
                            <td><span class="badge bg-<?php echo $payment['status'] === 'completed' ? 'success' : ($payment['status'] === 'failed' ? 'danger' : 'warning'); ?>"><?php echo !empty($payment['status']) ? ucfirst($payment['status']) : 'Pending'; ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <p class="text-muted text-center py-4">No payment history</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Make Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Choose your preferred payment method
                </div>
                
                <!-- Payment Method Selection -->
                <div class="mb-3">
                    <label class="form-label"><strong>Payment Method:</strong></label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="paymentMethod" id="methodSTK" value="stk" checked>
                        <label class="btn btn-outline-primary" for="methodSTK">
                            <i class="fas fa-mobile-alt"></i> STK Push
                        </label>
                        <input type="radio" class="btn-check" name="paymentMethod" id="methodManual" value="manual">
                        <label class="btn btn-outline-secondary" for="methodManual">
                            <i class="fas fa-hand-holding-usd"></i> Manual Paybill
                        </label>
                    </div>
                </div>
                
                <hr>
                
                <!-- STK Push Section -->
                <div id="stkPushSection">
                    <h6 class="mb-3"><i class="fas fa-mobile-alt text-primary"></i> Pay via M-Pesa STK Push</h6>
                    <p class="text-muted small">Enter your M-Pesa number to receive a payment prompt on your phone.</p>
                    
                    <form id="stkPushForm">
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phoneNumber" 
                                   placeholder="07XXXXXXXX or 2547XXXXXXXX" required
                                   value="<?php echo $member->phone ?? ''; ?>">
                            <small class="text-muted">Enter your M-Pesa registered phone number</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <input type="number" class="form-control" id="amount" 
                                   value="<?php echo $member->monthly_contribution; ?>" 
                                   min="1" step="0.01" required readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Payment Type</label>
                            <select class="form-select" id="paymentType">
                                <option value="monthly">Monthly Contribution</option>
                                <option value="registration">Registration Fee</option>
                                <option value="reactivation">Reactivation Fee</option>
                            </select>
                        </div>
                        
                        <div class="alert alert-warning small">
                            <i class="fas fa-exclamation-triangle"></i> You will receive a payment prompt on your phone. 
                            Enter your M-Pesa PIN to complete the payment.
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100" id="initiateSTKBtn">
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
                    <h6 class="mb-3"><i class="fas fa-hand-holding-usd text-secondary"></i> Pay via M-Pesa Paybill</h6>
                    <div class="card bg-light">
                        <div class="card-body">
                            <p class="mb-2"><strong>Paybill Number:</strong> <span class="text-primary fs-5"><?php echo MPESA_BUSINESS_SHORTCODE; ?></span></p>
                            <p class="mb-2"><strong>Account Number:</strong> <span class="text-success fs-6"><?php echo $member->member_number; ?></span></p>
                            <p class="mb-0"><strong>Amount:</strong> <span class="text-danger fs-6">KES <?php echo number_format($member->monthly_contribution, 2); ?></span></p>
                        </div>
                    </div>
                    <hr>
                    <h6>Steps to Pay:</h6>
                    <ol class="small">
                        <li>Go to M-Pesa menu on your phone</li>
                        <li>Select <strong>Lipa na M-Pesa</strong></li>
                        <li>Select <strong>Pay Bill</strong></li>
                        <li>Enter Business Number: <strong><?php echo MPESA_BUSINESS_SHORTCODE; ?></strong></li>
                        <li>Enter Account Number: <strong><?php echo $member->member_number; ?></strong></li>
                        <li>Enter Amount: <strong><?php echo number_format($member->monthly_contribution, 2); ?></strong></li>
                        <li>Enter your M-Pesa PIN and confirm</li>
                    </ol>
                    <p class="text-muted small mt-3">
                        <i class="fas fa-info-circle"></i> Payment will reflect automatically in your account within a few minutes.
                    </p>
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
                member_id: <?php echo $member->id; ?>,
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
</script>

<?php include VIEWS_PATH . '/layouts/member-footer.php'; ?>
