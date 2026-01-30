<?php include_once 'admin-header.php'; ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-balance-scale mr-2"></i>Payment Reconciliation
        </h1>
        <div class="btn-group">
            <a href="/admin/payments" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Payments
            </a>
            <button type="button" class="btn btn-info" onclick="refreshStats()">
                <i class="fas fa-sync-alt mr-2"></i>Refresh
            </button>
        </div>
    </div>

    <!-- Reconciliation Statistics -->
    <div class="row mb-4" id="reconciliation-stats">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total M-Pesa Payments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat-total">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-receipt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Auto-Matched
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat-auto-matched">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Unmatched
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat-unmatched">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Amount
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat-total-amount">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <div id="alert-container"></div>

    <!-- Unmatched Payments Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-warning">
                <i class="fas fa-exclamation-circle mr-2"></i>Unmatched Payments Requiring Action
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="unmatchedPaymentsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>M-Pesa Receipt</th>
                            <th>Amount</th>
                            <th>Transaction Date</th>
                            <th>Sender Phone</th>
                            <th>Sender Name</th>
                            <th>Paybill Account</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="unmatched-payments-body">
                        <tr>
                            <td colspan="7" class="text-center">
                                <i class="fas fa-spinner fa-spin mr-2"></i>Loading unmatched payments...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Match Modal -->
<div class="modal fade" id="matchModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-search mr-2"></i>Find Potential Matches
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Payment Details -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <strong>Payment Details</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Receipt:</strong> <span id="modal-receipt"></span></p>
                                <p><strong>Amount:</strong> <span id="modal-amount"></span></p>
                                <p><strong>Date:</strong> <span id="modal-date"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Phone:</strong> <span id="modal-phone"></span></p>
                                <p><strong>Name:</strong> <span id="modal-name"></span></p>
                                <p><strong>Account:</strong> <span id="modal-account"></span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Potential Matches -->
                <div class="card">
                    <div class="card-header bg-light">
                        <strong>Potential Member Matches</strong>
                    </div>
                    <div class="card-body" id="matches-container">
                        <div class="text-center">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Searching for matches...
                        </div>
                    </div>
                </div>

                <!-- Manual Entry -->
                <div class="card mt-3">
                    <div class="card-header bg-light">
                        <strong>Manual Member Entry</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Member Number or ID Number</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="manual-member-search" 
                                       placeholder="Enter member number or ID number">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" onclick="searchMember()">
                                        <i class="fas fa-search mr-2"></i>Search
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="manual-search-result"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Reconcile Confirmation Modal -->
<div class="modal fade" id="reconcileModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle mr-2"></i>Confirm Reconciliation
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to reconcile this payment?</p>
                <div id="reconcile-details"></div>
                <div class="form-group mt-3">
                    <label>Reconciliation Notes (Optional)</label>
                    <textarea class="form-control" id="reconcile-notes" rows="3" 
                              placeholder="Add any notes about this reconciliation..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="confirmReconcile()">
                    <i class="fas fa-check mr-2"></i>Confirm Reconciliation
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentPaymentId = null;
let currentMemberId = null;

// Load data on page load
$(document).ready(function() {
    loadReconciliationStats();
    loadUnmatchedPayments();
    
    // Auto-refresh every 30 seconds
    setInterval(function() {
        loadReconciliationStats();
        loadUnmatchedPayments();
    }, 30000);
});

// Load reconciliation statistics
function loadReconciliationStats() {
    $.ajax({
        url: '/admin/payments/reconciliation-stats',
        method: 'GET',
        success: function(response) {
            if (response) {
                $('#stat-total').text(response.total_payments || 0);
                $('#stat-auto-matched').text(response.auto_matched || 0);
                $('#stat-unmatched').text(response.unmatched || 0);
                $('#stat-total-amount').text('KES ' + formatMoney(response.total_amount || 0));
            }
        },
        error: function() {
            showAlert('Failed to load statistics', 'danger');
        }
    });
}

// Load unmatched payments
function loadUnmatchedPayments() {
    $.ajax({
        url: '/admin/payments/unmatched',
        method: 'GET',
        success: function(response) {
            let html = '';
            if (response && response.length > 0) {
                response.forEach(function(payment) {
                    html += `
                        <tr>
                            <td><strong>${payment.mpesa_receipt_number}</strong></td>
                            <td>KES ${formatMoney(payment.amount)}</td>
                            <td>${formatDate(payment.transaction_date)}</td>
                            <td>${payment.sender_phone || '-'}</td>
                            <td>${payment.sender_name || '-'}</td>
                            <td>${payment.paybill_account || '-'}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" onclick="findMatches(${payment.id})">
                                    <i class="fas fa-search mr-1"></i>Find Match
                                </button>
                            </td>
                        </tr>
                    `;
                });
            } else {
                html = `
                    <tr>
                        <td colspan="7" class="text-center text-success">
                            <i class="fas fa-check-circle mr-2"></i>No unmatched payments! All payments have been reconciled.
                        </td>
                    </tr>
                `;
            }
            $('#unmatched-payments-body').html(html);
        },
        error: function() {
            $('#unmatched-payments-body').html(`
                <tr>
                    <td colspan="7" class="text-center text-danger">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Failed to load unmatched payments
                    </td>
                </tr>
            `);
        }
    });
}

// Find potential matches for a payment
function findMatches(paymentId) {
    currentPaymentId = paymentId;
    
    // Get payment details first
    $.ajax({
        url: '/admin/payments/unmatched',
        method: 'GET',
        success: function(payments) {
            let payment = payments.find(p => p.id == paymentId);
            if (payment) {
                // Populate payment details
                $('#modal-receipt').text(payment.mpesa_receipt_number);
                $('#modal-amount').text('KES ' + formatMoney(payment.amount));
                $('#modal-date').text(formatDate(payment.transaction_date));
                $('#modal-phone').text(payment.sender_phone || '-');
                $('#modal-name').text(payment.sender_name || '-');
                $('#modal-account').text(payment.paybill_account || '-');
                
                // Show modal
                $('#matchModal').modal('show');
                
                // Load potential matches
                loadPotentialMatches(paymentId);
            }
        }
    });
}

// Load potential matches
function loadPotentialMatches(paymentId) {
    $('#matches-container').html('<div class="text-center"><i class="fas fa-spinner fa-spin mr-2"></i>Searching for matches...</div>');
    
    $.ajax({
        url: '/admin/payments/' + paymentId + '/matches',
        method: 'GET',
        success: function(matches) {
            let html = '';
            if (matches && matches.length > 0) {
                matches.forEach(function(match) {
                    let confidenceClass = match.confidence >= 90 ? 'success' : (match.confidence >= 70 ? 'warning' : 'info');
                    let confidenceIcon = match.confidence >= 90 ? 'check-circle' : (match.confidence >= 70 ? 'exclamation-circle' : 'info-circle');
                    
                    html += `
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h6 class="mb-1">${match.first_name || ''} ${match.last_name || ''}</h6>
                                        <small class="text-muted">
                                            Member: ${match.member_number || '-'} | 
                                            ID: ${match.id_number || '-'} | 
                                            Phone: ${match.phone || '-'}
                                        </small>
                                        <div class="mt-1">
                                            <span class="badge badge-${confidenceClass}">
                                                <i class="fas fa-${confidenceIcon} mr-1"></i>
                                                ${match.confidence}% Confidence (${match.match_type})
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <button class="btn btn-success btn-sm" onclick="prepareReconcile(${match.id}, '${match.first_name} ${match.last_name}', '${match.member_number}')">
                                            <i class="fas fa-link mr-1"></i>Match
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
            } else {
                html = `
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-info-circle mr-2"></i>No potential matches found. Try manual search below.
                    </div>
                `;
            }
            $('#matches-container').html(html);
        },
        error: function() {
            $('#matches-container').html(`
                <div class="alert alert-danger mb-0">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Failed to load potential matches
                </div>
            `);
        }
    });
}

// Search member manually
function searchMember() {
    let searchTerm = $('#manual-member-search').val().trim();
    if (!searchTerm) {
        showAlert('Please enter a member number or ID number', 'warning');
        return;
    }
    
    $('#manual-search-result').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Searching...</div>');
    
    // Search using the same endpoint but with search parameter
    $.ajax({
        url: '/admin/members/search?q=' + encodeURIComponent(searchTerm),
        method: 'GET',
        success: function(members) {
            if (members && members.length > 0) {
                let member = members[0]; // Take first result
                let html = `
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6>${member.first_name} ${member.last_name}</h6>
                            <small class="text-muted">
                                Member: ${member.member_number} | ID: ${member.id_number}
                            </small>
                            <div class="mt-2">
                                <button class="btn btn-success btn-sm" onclick="prepareReconcile(${member.id}, '${member.first_name} ${member.last_name}', '${member.member_number}')">
                                    <i class="fas fa-link mr-1"></i>Match with This Member
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                $('#manual-search-result').html(html);
            } else {
                $('#manual-search-result').html(`
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-user-times mr-2"></i>No member found with that number
                    </div>
                `);
            }
        },
        error: function() {
            $('#manual-search-result').html(`
                <div class="alert alert-danger mb-0">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Search failed
                </div>
            `);
        }
    });
}

// Prepare reconciliation confirmation
function prepareReconcile(memberId, memberName, memberNumber) {
    currentMemberId = memberId;
    
    let details = `
        <div class="card bg-light">
            <div class="card-body">
                <p><strong>Payment:</strong> ${$('#modal-receipt').text()} - ${$('#modal-amount').text()}</p>
                <p><strong>Member:</strong> ${memberName} (${memberNumber})</p>
            </div>
        </div>
    `;
    
    $('#reconcile-details').html(details);
    $('#matchModal').modal('hide');
    $('#reconcileModal').modal('show');
}

// Confirm and execute reconciliation
function confirmReconcile() {
    if (!currentPaymentId || !currentMemberId) {
        showAlert('Missing payment or member information', 'danger');
        return;
    }
    
    let notes = $('#reconcile-notes').val().trim();
    
    $.ajax({
        url: '/admin/payments/reconcile',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            payment_id: currentPaymentId,
            member_id: currentMemberId,
            notes: notes
        }),
        success: function(response) {
            if (response.success) {
                $('#reconcileModal').modal('hide');
                showAlert('Payment reconciled successfully!', 'success');
                
                // Refresh data
                loadReconciliationStats();
                loadUnmatchedPayments();
                
                // Reset
                currentPaymentId = null;
                currentMemberId = null;
                $('#reconcile-notes').val('');
            } else {
                showAlert(response.message || 'Reconciliation failed', 'danger');
            }
        },
        error: function() {
            showAlert('Failed to reconcile payment', 'danger');
        }
    });
}

// Utility functions
function formatMoney(amount) {
    return parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

function formatDate(dateString) {
    if (!dateString) return '-';
    let date = new Date(dateString);
    return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
}

function showAlert(message, type) {
    let alert = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `;
    $('#alert-container').html(alert);
    
    // Auto-dismiss after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}

function refreshStats() {
    loadReconciliationStats();
    loadUnmatchedPayments();
    showAlert('Data refreshed', 'info');
}
</script>

<?php include_once 'admin-footer.php'; ?>
