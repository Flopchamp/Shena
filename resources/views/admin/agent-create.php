<?php include_once 'admin-header.php'; ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-plus mr-2"></i>Register New Agent
        </h1>
        <a href="/admin/agents" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i>Back to Agents
        </a>
    </div>

    <!-- Registration Form Card -->
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-2"></i>Agent Information
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="/admin/agents/store" id="agentForm">
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
                                           placeholder="Enter first name" value="<?= htmlspecialchars($_SESSION['old_input']['first_name'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label font-weight-bold">
                                        Last Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                           placeholder="Enter last name" value="<?= htmlspecialchars($_SESSION['old_input']['last_name'] ?? '') ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="id_number" class="form-label font-weight-bold">
                                        National ID Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="id_number" name="id_number" 
                                           placeholder="Enter ID number" pattern="[0-9]{7,8}" value="<?= htmlspecialchars($_SESSION['old_input']['id_number'] ?? '') ?>" required>
                                    <small class="form-text text-muted">7 or 8 digits</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="date_of_birth" class="form-label font-weight-bold">
                                        Date of Birth <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                                           max="<?= date('Y-m-d', strtotime('-18 years')) ?>" value="<?= htmlspecialchars($_SESSION['old_input']['date_of_birth'] ?? '') ?>" required>
                                    <small class="form-text text-muted">Must be at least 18 years old</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="gender" class="form-label font-weight-bold">
                                        Gender <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" id="gender" name="gender" required>
                                        <option value="">Select gender</option>
                                        <option value="male" <?= (($_SESSION['old_input']['gender'] ?? '') === 'male') ? 'selected' : '' ?>>Male</option>
                                        <option value="female" <?= (($_SESSION['old_input']['gender'] ?? '') === 'female') ? 'selected' : '' ?>>Female</option>
                                        <option value="other" <?= (($_SESSION['old_input']['gender'] ?? '') === 'other') ? 'selected' : '' ?>>Other</option>
                                    </select>
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
                                           placeholder="0712345678" pattern="^(\+254|0)[17][0-9]{8}$" value="<?= htmlspecialchars($_SESSION['old_input']['phone'] ?? '') ?>" required>
                                    <small class="form-text text-muted">Format: 07XXXXXXXX or +254XXXXXXXXX</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label font-weight-bold">
                                        Email Address <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           placeholder="agent@example.com" value="<?= htmlspecialchars($_SESSION['old_input']['email'] ?? '') ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="county" class="form-label font-weight-bold">
                                        County <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="county" name="county" 
                                           placeholder="Enter county" value="<?= htmlspecialchars($_SESSION['old_input']['county'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sub_county" class="form-label font-weight-bold">
                                        Sub-County
                                    </label>
                                    <input type="text" class="form-control" id="sub_county" name="sub_county" 
                                           placeholder="Enter sub-county" value="<?= htmlspecialchars($_SESSION['old_input']['sub_county'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label font-weight-bold">
                                    Physical Address
                                </label>
                                <textarea class="form-control" id="address" name="address" rows="2" 
                                          placeholder="Enter physical address"><?= htmlspecialchars($_SESSION['old_input']['address'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <!-- Account Information Section -->
                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-key mr-2"></i>Account Information
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label font-weight-bold">
                                        Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" 
                                               placeholder="Enter password" minlength="8" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small class="form-text text-muted">Minimum 8 characters</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label font-weight-bold">
                                        Confirm Password <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" class="form-control" id="password_confirmation" 
                                           name="password_confirmation" placeholder="Confirm password" 
                                           minlength="8" required>
                                </div>
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
                                           step="0.01" value="<?= htmlspecialchars($_SESSION['old_input']['commission_rate'] ?? '10') ?>" required>
                                    <small class="form-text text-muted">Percentage of contribution (default: 10%)</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="bank_account" class="form-label font-weight-bold">
                                        Bank Account Number
                                    </label>
                                    <input type="text" class="form-control" id="bank_account" name="bank_account" 
                                           placeholder="Enter bank account number" value="<?= htmlspecialchars($_SESSION['old_input']['bank_account'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="bank_name" class="form-label font-weight-bold">
                                        Bank Name
                                    </label>
                                    <input type="text" class="form-control" id="bank_name" name="bank_name" 
                                           placeholder="Enter bank name" value="<?= htmlspecialchars($_SESSION['old_input']['bank_name'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="bank_branch" class="form-label font-weight-bold">
                                        Bank Branch
                                    </label>
                                    <input type="text" class="form-control" id="bank_branch" name="bank_branch" 
                                           placeholder="Enter bank branch" value="<?= htmlspecialchars($_SESSION['old_input']['bank_branch'] ?? '') ?>">
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
                                        <option value="active" <?= (($_SESSION['old_input']['status'] ?? 'active') === 'active') ? 'selected' : '' ?>>Active</option>
                                        <option value="inactive" <?= (($_SESSION['old_input']['status'] ?? '') === 'inactive') ? 'selected' : '' ?>>Inactive</option>
                                        <option value="suspended" <?= (($_SESSION['old_input']['status'] ?? '') === 'suspended') ? 'selected' : '' ?>>Suspended</option>
                                    </select>
                                    <small class="form-text text-muted">Set initial account status</small>
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
                                          placeholder="Add any additional notes about this agent"><?= htmlspecialchars($_SESSION['old_input']['notes'] ?? '') ?></textarea>
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
                                        <button type="reset" class="btn btn-secondary">
                                            <i class="fas fa-undo mr-2"></i>Reset Form
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save mr-2"></i>Register Agent
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php 
                        // Clear old input after displaying
                        unset($_SESSION['old_input']); 
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Toggle password visibility
    $('#togglePassword').on('click', function() {
        const passwordInput = $('#password');
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
    $('#agentForm').on('submit', function(e) {
        const password = $('#password').val();
        const confirmation = $('#password_confirmation').val();
        
        if (password !== confirmation) {
            e.preventDefault();
            alert('Passwords do not match. Please check and try again.');
            $('#password_confirmation').focus();
            return false;
        }
        
        // Show loading state
        $(this).find('button[type="submit"]').prop('disabled', true).html(
            '<i class="fas fa-spinner fa-spin mr-2"></i>Registering...'
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
    
    // Validate ID number
    $('#id_number').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    // Set max date for date of birth (18 years ago)
    const eighteenYearsAgo = new Date();
    eighteenYearsAgo.setFullYear(eighteenYearsAgo.getFullYear() - 18);
    $('#date_of_birth').attr('max', eighteenYearsAgo.toISOString().split('T')[0]);
});
</script>

<?php include_once 'admin-footer.php'; ?>
