<?php
// Member Upgrade View
$pageTitle = 'Upgrade Package - ' . SITE_NAME;
include 'resources/views/layouts/member-header.php';
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-arrow-circle-up mr-2"></i>Upgrade Your Package
        </h1>
        <a href="/member/dashboard" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
        </a>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>
    
    <div id="dynamic-alert"></div>

    <div class="row">
        <!-- Current Package -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-user mr-2"></i>Your Current Package
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center py-4">
                        <h2 class="text-uppercase text-secondary"><?php echo ucfirst($member['package']); ?></h2>
                        <h3 class="text-primary">KES <?php echo number_format($calculation['current_monthly_fee'], 2); ?></h3>
                        <p class="text-muted">per month</p>
                    </div>
                    
                    <hr>
                    
                    <h6 class="font-weight-bold mb-3">Current Benefits:</h6>
                    <ul class="list-unstyled">
                        <?php if ($member['package'] === 'basic'): ?>
                            <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Funeral Service Coverage</li>
                            <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Basic Burial Expenses</li>
                            <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Up to 3 Dependents</li>
                            <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Standard Support</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Premium Package -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow border-success">
                <div class="card-header bg-success text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-star mr-2"></i>Premium Package
                        <span class="badge badge-warning float-right">UPGRADE</span>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center py-4">
                        <h2 class="text-uppercase text-success">PREMIUM</h2>
                        <h3 class="text-primary">KES <?php echo number_format($calculation['new_monthly_fee'], 2); ?></h3>
                        <p class="text-muted">per month</p>
                    </div>
                    
                    <hr>
                    
                    <h6 class="font-weight-bold mb-3">Enhanced Benefits:</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>All Basic Benefits</li>
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Extended Burial Coverage</li>
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Up to 5 Dependents</li>
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Priority Processing</li>
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>24/7 Support Hotline</li>
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Additional Benefits</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Upgrade Cost Breakdown -->
    <div class="row">
        <div class="col-lg-8 mx-auto mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-calculator mr-2"></i>Upgrade Cost Breakdown
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <td><strong>Current Package Monthly Fee:</strong></td>
                                <td class="text-right">KES <?php echo number_format($calculation['current_monthly_fee'], 2); ?></td>
                            </tr>
                            <tr>
                                <td><strong>New Package Monthly Fee:</strong></td>
                                <td class="text-right">KES <?php echo number_format($calculation['new_monthly_fee'], 2); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Monthly Difference:</strong></td>
                                <td class="text-right">
                                    KES <?php echo number_format($calculation['new_monthly_fee'] - $calculation['current_monthly_fee'], 2); ?>
                                </td>
                            </tr>
                            <tr class="bg-light">
                                <td><strong>Days Remaining in <?php echo date('F'); ?>:</strong></td>
                                <td class="text-right"><?php echo $calculation['days_remaining']; ?> days (out of <?php echo $calculation['total_days_in_month']; ?> days)</td>
                            </tr>
                            <tr class="bg-success text-white">
                                <td><strong>Prorated Upgrade Cost (Pay Today):</strong></td>
                                <td class="text-right"><h4>KES <?php echo number_format($calculation['prorated_amount'], 2); ?></h4></td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>How it works:</strong> You only pay for the remaining days of this month. 
                        Starting <?php echo date('F 1, Y', strtotime('first day of next month')); ?>, 
                        your regular monthly payment will be KES <?php echo number_format($calculation['new_monthly_fee'], 2); ?>.
                    </div>

                    <!-- Upgrade Form -->
                    <?php if (empty($pendingUpgrades)): ?>
                        <form id="upgradeForm" class="mt-4">
                            <div class="form-group">
                                <label>M-Pesa Phone Number</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" 
                                       value="<?php echo htmlspecialchars($member['phone']); ?>" 
                                       placeholder="+254712345678" required>
                                <small class="form-text text-muted">
                                    You will receive an M-Pesa prompt to complete the payment
                                </small>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="agree_terms" required>
                                <label class="form-check-label" for="agree_terms">
                                    I understand that by upgrading, my monthly contribution will increase to 
                                    KES <?php echo number_format($calculation['new_monthly_fee'], 2); ?> 
                                    starting next month.
                                </label>
                            </div>

                            <button type="submit" class="btn btn-success btn-lg btn-block" id="upgradeBtn">
                                <i class="fas fa-arrow-up mr-2"></i>Upgrade to Premium (KES <?php echo number_format($calculation['prorated_amount'], 2); ?>)
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <h5><i class="fas fa-clock mr-2"></i>Pending Upgrade Request</h5>
                            <p>You have a pending upgrade request. Please complete the payment or cancel it before requesting a new upgrade.</p>
                            <?php foreach ($pendingUpgrades as $pending): ?>
                                <div class="mt-3">
                                    <p><strong>Status:</strong> <?php echo ucwords(str_replace('_', ' ', $pending['status'])); ?></p>
                                    <p><strong>Amount:</strong> KES <?php echo number_format($pending['prorated_amount'], 2); ?></p>
                                    <p><strong>Requested:</strong> <?php echo date('d M Y H:i', strtotime($pending['requested_at'])); ?></p>
                                    <?php if ($pending['status'] === 'pending'): ?>
                                        <button class="btn btn-sm btn-danger" onclick="cancelUpgrade(<?php echo $pending['id']; ?>)">
                                            <i class="fas fa-times mr-1"></i>Cancel Request
                                        </button>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Upgrade History -->
    <?php if (!empty($upgradeHistory)): ?>
    <div class="row">
        <div class="col-lg-8 mx-auto mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history mr-2"></i>Upgrade History
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Amount Paid</th>
                                    <th>Payment Method</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($upgradeHistory as $history): ?>
                                <tr>
                                    <td><?php echo date('d M Y', strtotime($history['upgraded_at'])); ?></td>
                                    <td><?php echo ucfirst($history['from_package']); ?></td>
                                    <td><?php echo ucfirst($history['to_package']); ?></td>
                                    <td>KES <?php echo number_format($history['amount_paid'], 2); ?></td>
                                    <td><?php echo strtoupper($history['payment_method']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Payment Processing Modal -->
<div class="modal fade" id="processingModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-5">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                    <span class="sr-only">Processing...</span>
                </div>
                <h5>Processing Your Upgrade</h5>
                <p class="text-muted">Please check your phone for the M-Pesa prompt...</p>
                <p id="processingStatus"></p>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#upgradeForm').on('submit', function(e) {
        e.preventDefault();
        
        if (!$('#agree_terms').is(':checked')) {
            showAlert('Please agree to the terms before proceeding', 'warning');
            return;
        }
        
        var phone = $('#phone_number').val();
        var upgradeBtn = $('#upgradeBtn');
        
        // Disable button
        upgradeBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Processing...');
        
        $.ajax({
            url: '/member/upgrade/request',
            method: 'POST',
            data: { phone_number: phone },
            success: function(response) {
                if (response.success) {
                    $('#processingModal').modal('show');
                    $('#processingStatus').html('Upgrade Request ID: ' + response.upgrade_request_id);
                    
                    // Check status every 5 seconds
                    var checkInterval = setInterval(function() {
                        checkUpgradeStatus(response.upgrade_request_id, checkInterval);
                    }, 5000);
                    
                    // Stop checking after 2 minutes
                    setTimeout(function() {
                        clearInterval(checkInterval);
                        $('#processingModal').modal('hide');
                        showAlert('Payment timeout. Please check your payment status.', 'warning');
                        location.reload();
                    }, 120000);
                } else {
                    showAlert(response.message || 'Failed to initiate upgrade', 'danger');
                    upgradeBtn.prop('disabled', false).html('<i class="fas fa-arrow-up mr-2"></i>Upgrade to Premium');
                }
            },
            error: function(xhr) {
                var error = xhr.responseJSON?.error || 'Failed to process upgrade request';
                showAlert(error, 'danger');
                upgradeBtn.prop('disabled', false).html('<i class="fas fa-arrow-up mr-2"></i>Upgrade to Premium');
            }
        });
    });
});

function checkUpgradeStatus(upgradeRequestId, interval) {
    $.ajax({
        url: '/member/upgrade/status?upgrade_request_id=' + upgradeRequestId,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                if (response.status === 'completed') {
                    clearInterval(interval);
                    $('#processingModal').modal('hide');
                    showAlert('Upgrade completed successfully! Redirecting...', 'success');
                    setTimeout(function() {
                        window.location.href = '/member/dashboard';
                    }, 2000);
                } else if (response.status === 'failed') {
                    clearInterval(interval);
                    $('#processingModal').modal('hide');
                    showAlert('Payment failed. Please try again.', 'danger');
                    location.reload();
                }
            }
        }
    });
}

function cancelUpgrade(upgradeRequestId) {
    if (!confirm('Are you sure you want to cancel this upgrade request?')) {
        return;
    }
    
    $.ajax({
        url: '/member/upgrade/cancel',
        method: 'POST',
        data: { upgrade_request_id: upgradeRequestId },
        success: function(response) {
            if (response.success) {
                showAlert('Upgrade request cancelled', 'success');
                location.reload();
            } else {
                showAlert(response.error || 'Failed to cancel upgrade', 'danger');
            }
        },
        error: function(xhr) {
            showAlert(xhr.responseJSON?.error || 'Failed to cancel upgrade', 'danger');
        }
    });
}

function showAlert(message, type) {
    var alert = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    `;
    $('#dynamic-alert').html(alert);
    
    // Scroll to top
    $('html, body').animate({ scrollTop: 0 }, 'fast');
}
</script>

<?php include 'resources/views/layouts/member-footer.php'; ?>
