<?php include_once __DIR__ . '/../layouts/admin-header.php'; ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-edit mr-2"></i>Edit Agent - <?= htmlspecialchars($agent['agent_number']) ?>
        </h1>
        <div>
            <a href="/admin/agents/view/<?= $agent['id'] ?>" class="btn btn-secondary shadow-sm mr-2">
                <i class="fas fa-arrow-left mr-2"></i>Back to Details
            </a>
            <a href="/admin/agents" class="btn btn-outline-secondary shadow-sm">
                <i class="fas fa-list mr-2"></i>All Agents
            </a>
        </div>
    </div>

    <!-- Edit Form Card -->
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-2"></i>Agent Information
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="/admin/agents/update/<?= $agent['id'] ?>" id="agentEditForm">
                        <!-- Personal Information Section -->
                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-user mr-2"></i>Personal Information
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label font-weight-bold">
                                        First Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                           placeholder="Enter first name" value="<?= htmlspecialchars($agent['first_name']) ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label font-weight-bold">
                                        Last Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                           placeholder="Enter last name" value="<?= htmlspecialchars($agent['last_name']) ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="national_id" class="form-label font-weight-bold">
                                        National ID Number
                                    </label>
                                    <input type="text" class="form-control" id="national_id" 
                                           value="<?= htmlspecialchars($agent['national_id']) ?>" disabled>
                                    <small class="form-text text-muted">National ID cannot be changed</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="agent_number" class="form-label font-weight-bold">
                                        Agent Number
                                    </label>
                                    <input type="text" class="form-control" id="agent_number" 
                                           value="<?= htmlspecialchars($agent['agent_number']) ?>" disabled>
                                    <small class="form-text text-muted">Agent number is auto-generated</small>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-address-card mr-2"></i>Contact Information
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label font-weight-bold">
                                        Phone Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           placeholder="0712345678" pattern="^(\+254|0)[17][0-9]{8}$" 
                                           value="<?= htmlspecialchars($agent['phone']) ?>" required>
                                    <small class="form-text text-muted">Format: 07XXXXXXXX or +254XXXXXXXXX</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label font-weight-bold">
                                        Email Address <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           placeholder="agent@example.com" value="<?= htmlspecialchars($agent['email']) ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="county" class="form-label font-weight-bold">
                                        County <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="county" name="county" 
                                           placeholder="Enter county" value="<?= htmlspecialchars($agent['county'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sub_county" class="form-label font-weight-bold">
                                        Sub-County
                                    </label>
                                    <input type="text" class="form-control" id="sub_county" name="sub_county" 
                                           placeholder="Enter sub-county" value="<?= htmlspecialchars($agent['sub_county'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label font-weight-bold">
                                    Physical Address
                                </label>
                                <textarea class="form-control" id="address" name="address" rows="2" 
                                          placeholder="Enter physical address"><?= htmlspecialchars($agent['address'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <!-- Commission Information Section -->
                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-percentage mr-2"></i>Commission Information
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="commission_rate" class="form-label font-weight-bold">
                                        Commission Rate (%) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control" id="commission_rate" 
                                           name="commission_rate" placeholder="10" min="0" max="100" 
                                           step="0.01" value="<?= htmlspecialchars($agent['commission_rate']) ?>" required>
                                    <small class="form-text text-muted">Percentage of contribution</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="bank_account" class="form-label font-weight-bold">
                                        Bank Account Number
                                    </label>
                                    <input type="text" class="form-control" id="bank_account" name="bank_account" 
                                           placeholder="Enter bank account number" value="<?= htmlspecialchars($agent['bank_account'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="bank_name" class="form-label font-weight-bold">
                                        Bank Name
                                    </label>
                                    <input type="text" class="form-control" id="bank_name" name="bank_name" 
                                           placeholder="Enter bank name" value="<?= htmlspecialchars($agent['bank_name'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="bank_branch" class="form-label font-weight-bold">
                                        Bank Branch
                                    </label>
                                    <input type="text" class="form-control" id="bank_branch" name="bank_branch" 
                                           placeholder="Enter bank branch" value="<?= htmlspecialchars($agent['bank_branch'] ?? '') ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Status Section -->
                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-toggle-on mr-2"></i>Account Status
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label font-weight-bold">
                                        Status <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="active" <?= $agent['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                        <option value="inactive" <?= $agent['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                        <option value="suspended" <?= $agent['status'] === 'suspended' ? 'selected' : '' ?>>Suspended</option>
                                    </select>
                                    <small class="form-text text-muted">Change agent account status</small>
                                </div>
                            </div>
                        </div>

                        <!-- Password Update Section (Optional) -->
                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-key mr-2"></i>Update Password (Optional)
                            </h5>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                Leave password fields empty if you don't want to change the password
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="new_password" class="form-label font-weight-bold">
                                        New Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="new_password" name="new_password" 
                                               placeholder="Enter new password" minlength="8">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small class="form-text text-muted">Minimum 8 characters</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="confirm_password" class="form-label font-weight-bold">
                                        Confirm New Password
                                    </label>
                                    <input type="password" class="form-control" id="confirm_password" 
                                           name="confirm_password" placeholder="Confirm new password" minlength="8">
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-sticky-note mr-2"></i>Additional Information
                            </h5>
                            <div class="mb-3">
                                <label for="notes" class="form-label font-weight-bold">
                                    Notes (Optional)
                                </label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" 
                                          placeholder="Add any additional notes about this agent"><?= htmlspecialchars($agent['notes'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Fields marked with <span class="text-danger">*</span> are required
                                        </small>
                                    </div>
                                    <div>
                                        <a href="/admin/agents/view/<?= $agent['id'] ?>" class="btn btn-secondary">
                                            <i class="fas fa-times mr-2"></i>Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save mr-2"></i>Update Agent
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Toggle password visibility
    $('#togglePassword').on('click', function() {
        const passwordInput = $('#new_password');
        const icon = $(this).find('i');
        
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
    
    // Validate password confirmation
    $('#agentEditForm').on('submit', function(e) {
        const newPassword = $('#new_password').val();
        const confirmPassword = $('#confirm_password').val();
        
        // Only validate if passwords are provided
        if (newPassword || confirmPassword) {
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match. Please check and try again.');
                $('#confirm_password').focus();
                return false;
            }
            
            if (newPassword.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long.');
                $('#new_password').focus();
                return false;
            }
        }
        
        // Show loading state
        $(this).find('button[type="submit"]').prop('disabled', true).html(
            '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...'
        );
    });
    
    // Format phone number
    $('#phone').on('blur', function() {
        let phone = $(this).val().replace(/\s+/g, '');
        if (phone.startsWith('0') && phone.length === 10) {
            // Valid format
        } else if (phone.startsWith('+254') || phone.startsWith('254')) {
            // Convert to local format
            phone = '0' + phone.replace(/^\+?254/, '');
            $(this).val(phone);
        }
    });
});
</script>

<?php include_once __DIR__ . '/../layouts/admin-footer.php'; ?>
