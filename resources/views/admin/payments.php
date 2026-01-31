<?php include_once 'admin-header.php'; ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-money-bill-wave mr-2"></i>Payments Management
        </h1>
        <div class="btn-group">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#verifyPaymentModal">
                <i class="fas fa-search-dollar mr-2"></i>Verify Payment
            </button>
            <a href="/admin/payments/reconciliation" class="btn btn-warning">
                <i class="fas fa-balance-scale mr-2"></i>Reconciliation
            </a>
            <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="/admin/payments">All Payments</a>
                <a class="dropdown-item" href="/admin/payments?status=completed">Completed</a>
                <a class="dropdown-item" href="/admin/payments?status=pending">Pending</a>
                <a class="dropdown-item" href="/admin/payments?status=failed">Failed</a>
            </div>
        </div>
    </div>

    <!-- Payments Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Revenue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                KES <?php echo number_format(array_sum(array_column($payments, 'amount')), 2); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
                                Total Payments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo count($payments); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
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
                                Pending Payments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo count(array_filter($payments, fn($p) => $p['status'] === 'pending')); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                This Month
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                KES <?php 
                                    $thisMonth = array_filter($payments, fn($p) => 
                                        date('Y-m', strtotime($p['created_at'])) === date('Y-m')
                                    );
                                    echo number_format(array_sum(array_column($thisMonth, 'amount')), 2);
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Payment Transactions</h6>
        </div>
        <div class="card-body">
            <?php if (!empty($payments)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Member</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($payment['transaction_id'] ?? 'N/A'); ?></td>
                                <td>
                                    <div>
                                        <strong><?php echo htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']); ?></strong><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($payment['member_number']); ?></small>
                                    </div>
                                </td>
                                <td>
                                    <strong>KES <?php echo number_format($payment['amount'], 2); ?></strong>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">
                                        <?php echo ucfirst($payment['payment_type'] ?? 'monthly'); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        <?php echo strtoupper($payment['payment_method'] ?? 'mpesa'); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-<?php 
                                        echo match($payment['status']) {
                                            'completed' => 'success',
                                            'pending' => 'warning',
                                            'failed' => 'danger',
                                            default => 'secondary'
                                        };
                                    ?>">
                                        <?php echo ucfirst($payment['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y H:i', strtotime($payment['created_at'])); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#paymentModal<?php echo $payment['id']; ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#verifyPaymentModal"
                                                data-payment-id="<?php echo (int)$payment['id']; ?>"
                                                data-member-id="<?php echo (int)($payment['member_id'] ?? 0); ?>"
                                                data-amount="<?php echo htmlspecialchars($payment['amount']); ?>"
                                                data-checkout-id="<?php echo htmlspecialchars($payment['transaction_reference'] ?? ''); ?>"
                                                data-receipt="<?php echo htmlspecialchars($payment['transaction_id'] ?? ''); ?>">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <?php if ($payment['status'] === 'pending'): ?>
                                        <button type="button" class="btn btn-success btn-sm" onclick="confirmPayment(<?php echo $payment['id']; ?>)">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="failPayment(<?php echo $payment['id']; ?>)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>

                            <!-- Payment Details Modal -->
                            <div class="modal fade" id="paymentModal<?php echo $payment['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Payment Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Payment Information</h6>
                                                    <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($payment['transaction_id'] ?? 'N/A'); ?></p>
                                                    <p><strong>Amount:</strong> KES <?php echo number_format($payment['amount'], 2); ?></p>
                                                    <p><strong>Type:</strong> <?php echo ucfirst($payment['payment_type'] ?? 'monthly'); ?></p>
                                                    <p><strong>Method:</strong> <?php echo strtoupper($payment['payment_method'] ?? 'mpesa'); ?></p>
                                                    <p><strong>Status:</strong> <span class="badge badge-<?php echo $payment['status'] === 'completed' ? 'success' : 'warning'; ?>"><?php echo ucfirst($payment['status']); ?></span></p>
                                                    <p><strong>Date:</strong> <?php echo date('M j, Y H:i:s', strtotime($payment['created_at'])); ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Member Information</h6>
                                                    <p><strong>Name:</strong> <?php echo htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']); ?></p>
                                                    <p><strong>Member Number:</strong> <?php echo htmlspecialchars($payment['member_number']); ?></p>
                                                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($payment['phone_number'] ?? 'N/A'); ?></p>
                                                    <p><strong>Reference:</strong> <?php echo htmlspecialchars($payment['reference'] ?? 'N/A'); ?></p>
                                                    <p><strong>Notes:</strong> <?php echo htmlspecialchars($payment['notes'] ?? 'N/A'); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#verifyPaymentModal"
                                                data-member-id="<?php echo $payment['member_id']; ?>"
                                                data-amount="<?php echo $payment['amount']; ?>"
                                                data-checkout-id="<?php echo htmlspecialchars($payment['transaction_reference'] ?? ''); ?>"
                                                data-receipt="<?php echo htmlspecialchars($payment['transaction_id'] ?? ''); ?>">
                                                <i class="fas fa-check-circle mr-1"></i>Verify Payment
                                            </button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-money-bill-wave fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">No payments found</h5>
                    <p class="text-gray-500">Payment transactions will appear here.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Verify Payment Modal -->
<div class="modal fade" id="verifyPaymentModal" tabindex="-1" aria-labelledby="verifyPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verifyPaymentModalLabel">Verify Payment (Paybill or STK)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="verifyPaymentForm">
                <?php if (isset($_SESSION['csrf_token'])): ?>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <?php endif; ?>
                <div class="modal-body">
                    <div class="alert alert-info">
                        Use this to verify a Paybill payment (by receipt) or STK push (by Checkout Request ID) when a member reports missing payment.
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Method</label>
                            <select class="form-control" name="method" id="verifyMethod" required>
                                <option value="stk">STK Push</option>
                                <option value="paybill">Paybill</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Payment Type</label>
                            <select class="form-control" name="payment_type">
                                <option value="monthly">Monthly</option>
                                <option value="registration">Registration</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Amount (KES)</label>
                            <input type="number" class="form-control" name="amount" min="1" step="1" placeholder="e.g. 200">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3" id="stkField">
                            <label class="form-label">Checkout Request ID (STK)</label>
                            <input type="text" class="form-control" name="checkout_request_id" id="checkoutRequestId" placeholder="ws_CO_...">
                        </div>
                        <div class="col-md-6 mb-3" id="paybillField" style="display:none;">
                            <label class="form-label">M-Pesa Receipt Number (Paybill)</label>
                            <input type="text" class="form-control" name="mpesa_receipt_number" id="mpesaReceiptNumber" placeholder="ABC123XYZ">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Search Member</label>
                        <div style="position: relative;">
                            <input type="text" class="form-control" id="memberSearchInput" 
                                placeholder="Type member name, number, ID number, or phone..." autocomplete="off">
                            <div id="memberSearchResults" class="list-group" style="position: absolute; z-index: 1000; width: 100%; display: none; max-height: 300px; overflow-y: auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Member ID</label>
                            <input type="number" class="form-control" name="member_id" id="verifyMemberId" placeholder="Member ID" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Member Number</label>
                            <input type="text" class="form-control" name="member_number" id="verifyMemberNumber" placeholder="M-000123" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">ID Number</label>
                            <input type="text" class="form-control" name="id_number" id="verifyIdNumber" placeholder="ID Number" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes (optional)</label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="Verification notes"></textarea>
                    </div>

                    <div id="verifyPaymentResult"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Verify & Post</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function confirmPayment(paymentId) {
    if (confirm('Confirm this payment as completed?')) {
        // Implementation for confirming payment
        window.location.href = `/admin/payments/confirm/${paymentId}`;
    }
}

function failPayment(paymentId) {
    const reason = prompt('Reason for payment failure:');
    if (reason) {
        // Implementation for marking payment as failed
        window.location.href = `/admin/payments/fail/${paymentId}?reason=${encodeURIComponent(reason)}`;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const verifyModal = document.getElementById('verifyPaymentModal');
    const verifyForm = document.getElementById('verifyPaymentForm');
    const verifyMethod = document.getElementById('verifyMethod');
    const stkField = document.getElementById('stkField');
    const paybillField = document.getElementById('paybillField');
    const resultBox = document.getElementById('verifyPaymentResult');
    
    // Member autocomplete
    const searchInput = document.getElementById('memberSearchInput');
    const searchResults = document.getElementById('memberSearchResults');
    const memberIdInput = document.getElementById('verifyMemberId');
    const memberNumberInput = document.getElementById('verifyMemberNumber');
    const idNumberInput = document.getElementById('verifyIdNumber');
    let searchTimeout = null;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length < 2) {
            searchResults.style.display = 'none';
            return;
        }
        
        searchTimeout = setTimeout(() => {
            fetch('/admin/payments/search-members?q=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    searchResults.innerHTML = '';
                    
                    if (data.results && data.results.length > 0) {
                        data.results.forEach(member => {
                            const item = document.createElement('button');
                            item.type = 'button';
                            item.className = 'list-group-item list-group-item-action';
                            item.innerHTML = `
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">${member.name}</h6>
                                    <small>${member.member_number}</small>
                                </div>
                                <small class="text-muted">ID: ${member.id_number} | Phone: ${member.phone}</small>
                            `;
                            item.addEventListener('click', function() {
                                memberIdInput.value = member.id;
                                memberNumberInput.value = member.member_number;
                                idNumberInput.value = member.id_number;
                                searchInput.value = member.label;
                                searchResults.style.display = 'none';
                            });
                            searchResults.appendChild(item);
                        });
                        searchResults.style.display = 'block';
                    } else {
                        const noResults = document.createElement('div');
                        noResults.className = 'list-group-item text-muted';
                        noResults.textContent = 'No members found';
                        searchResults.appendChild(noResults);
                        searchResults.style.display = 'block';
                    }
                })
                .catch(err => {
                    console.error('Search error:', err);
                    searchResults.style.display = 'none';
                });
        }, 300);
    });
    
    // Close search results when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });

    function toggleMethodFields() {
        if (verifyMethod.value === 'paybill') {
            stkField.style.display = 'none';
            paybillField.style.display = 'block';
        } else {
            stkField.style.display = 'block';
            paybillField.style.display = 'none';
        }
    }

    if (verifyMethod) {
        verifyMethod.addEventListener('change', toggleMethodFields);
        toggleMethodFields();
    }

    verifyModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        if (!button) {
            // Clear all fields when opening fresh
            verifyForm.reset();
            searchInput.value = '';
            memberIdInput.value = '';
            memberNumberInput.value = '';
            idNumberInput.value = '';
            resultBox.innerHTML = '';
            return;
        }
        const memberId = button.getAttribute('data-member-id') || '';
        const amount = button.getAttribute('data-amount') || '';
        const checkoutId = button.getAttribute('data-checkout-id') || '';
        const receipt = button.getAttribute('data-receipt') || '';

        // Populate form fields
        memberIdInput.value = memberId;
        document.querySelector('input[name="amount"]').value = amount;
        document.getElementById('checkoutRequestId').value = checkoutId;
        document.getElementById('mpesaReceiptNumber').value = receipt;
        
        // Set appropriate method based on what data is available
        if (receipt) {
            verifyMethod.value = 'paybill';
        } else if (checkoutId) {
            verifyMethod.value = 'stk';
        }
        toggleMethodFields();
        
        resultBox.innerHTML = '';
    });

    verifyForm.addEventListener('submit', function(e) {
        e.preventDefault();
        resultBox.innerHTML = '<div class="alert alert-info">Verifying payment...</div>';

        const formData = new FormData(verifyForm);

        fetch('/admin/payments/verify', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultBox.innerHTML = '<div class="alert alert-success">' + (data.message || 'Payment verified') + '</div>';
                setTimeout(() => window.location.reload(), 1200);
            } else {
                resultBox.innerHTML = '<div class="alert alert-danger">' + (data.error || data.message || 'Verification failed') + '</div>';
            }
        })
        .catch(() => {
            resultBox.innerHTML = '<div class="alert alert-danger">Verification request failed</div>';
        });
    });
});
</script>

<?php include_once 'admin-footer.php'; ?>
