<?php 
// Helper function to get old input value
function old($field, $default = '') {
    return $_SESSION['old_input'][$field] ?? $default;
}

// Helper function to check if field has error
function hasError($field) {
    return isset($_SESSION['error_field']) && $_SESSION['error_field'] === $field;
}

include VIEWS_PATH . '/layouts/header.php'; 
?>

<style>
    .is-invalid {
        border-color: #dc3545 !important;
        background-color: #fff5f5 !important;
    }
    .is-invalid:focus {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
    }
    .error-field-label {
        color: #dc3545 !important;
        font-weight: 600;
    }
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    .shake {
        animation: shake 0.5s;
    }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-user-plus"></i> Member Registration</h4>
                    <small>Registration Fee: KES 200</small>
                </div>
                <div class="card-body">
                    <form method="POST" action="/register">
                        <input type="hidden" name="csrf_token" value="<?php echo e($csrf_token); ?>">
                        
                        <!-- Personal Information -->
                        <h5 class="border-bottom pb-2 mb-3">Personal Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label <?php echo hasError('first_name') ? 'error-field-label' : ''; ?>">First Name *</label>
                                <input type="text" class="form-control <?php echo hasError('first_name') ? 'is-invalid shake' : ''; ?>" id="first_name" name="first_name" value="<?php echo e(old('first_name')); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label <?php echo hasError('last_name') ? 'error-field-label' : ''; ?>">Last Name *</label>
                                <input type="text" class="form-control <?php echo hasError('last_name') ? 'is-invalid shake' : ''; ?>" id="last_name" name="last_name" value="<?php echo e(old('last_name')); ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label <?php echo hasError('email') ? 'error-field-label' : ''; ?>">Email Address *</label>
                                <input type="email" class="form-control <?php echo hasError('email') ? 'is-invalid shake' : ''; ?>" id="email" name="email" value="<?php echo e(old('email')); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label <?php echo hasError('phone') ? 'error-field-label' : ''; ?>">Phone Number *</label>
                                <input type="tel" class="form-control <?php echo hasError('phone') ? 'is-invalid shake' : ''; ?>" id="phone" name="phone" value="<?php echo e(old('phone')); ?>" placeholder="+254..." required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="id_number" class="form-label <?php echo hasError('id_number') ? 'error-field-label' : ''; ?>">National ID Number *</label>
                                <input type="text" class="form-control <?php echo hasError('id_number') ? 'is-invalid shake' : ''; ?>" id="id_number" name="id_number" value="<?php echo e(old('id_number')); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label <?php echo hasError('date_of_birth') ? 'error-field-label' : ''; ?>">Date of Birth</label>
                                <input type="date" class="form-control <?php echo hasError('date_of_birth') ? 'is-invalid shake' : ''; ?>" id="date_of_birth" name="date_of_birth" value="<?php echo e(old('date_of_birth')); ?>">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label <?php echo hasError('gender') ? 'error-field-label' : ''; ?>">Gender *</label>
                                <select class="form-select <?php echo hasError('gender') ? 'is-invalid shake' : ''; ?>" id="gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male" <?php echo old('gender') === 'male' ? 'selected' : ''; ?>>Male</option>
                                    <option value="female" <?php echo old('gender') === 'female' ? 'selected' : ''; ?>>Female</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label <?php echo hasError('address') ? 'error-field-label' : ''; ?>">Address</label>
                                <input type="text" class="form-control <?php echo hasError('address') ? 'is-invalid shake' : ''; ?>" id="address" name="address" value="<?php echo e(old('address')); ?>">
                            </div>
                        </div>
                        
                        <!-- Next of Kin Information -->
                        <h5 class="border-bottom pb-2 mb-3 mt-4">Next of Kin Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="next_of_kin" class="form-label <?php echo hasError('next_of_kin') ? 'error-field-label' : ''; ?>">Next of Kin Name</label>
                                <input type="text" class="form-control <?php echo hasError('next_of_kin') ? 'is-invalid shake' : ''; ?>" id="next_of_kin" name="next_of_kin" value="<?php echo e(old('next_of_kin')); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="next_of_kin_phone" class="form-label <?php echo hasError('next_of_kin_phone') ? 'error-field-label' : ''; ?>">Next of Kin Phone</label>
                                <input type="tel" class="form-control <?php echo hasError('next_of_kin_phone') ? 'is-invalid shake' : ''; ?>" id="next_of_kin_phone" name="next_of_kin_phone" value="<?php echo e(old('next_of_kin_phone')); ?>">
                            </div>
                        </div>
                        
                        <!-- Membership Package -->
                        <h5 class="border-bottom pb-2 mb-3 mt-4">Membership Package</h5>
                        <p class="text-muted">
                            Select the package that best matches your family structure and age group.
                            All packages include the full set of last respect services described in the policy booklet.
                        </p>
                        <div class="row">
                            <?php foreach ($packages as $key => $package): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input <?php echo hasError('package') ? 'is-invalid shake' : ''; ?>"
                                                    type="radio"
                                                    name="package"
                                                    id="package_<?php echo $key; ?>"
                                                    value="<?php echo $key; ?>"
                                                    <?php echo (old('package', 'individual_below_70') === $key) ? 'checked' : ''; ?>
                                                >
                                                <label class="form-check-label <?php echo hasError('package') ? 'error-field-label' : ''; ?>" for="package_<?php echo $key; ?>">
                                                    <strong><?php echo e($package['name']); ?></strong>
                                                    <?php if (!empty($package['category']) && $package['category'] === 'executive'): ?>
                                                        <i class="fas fa-crown text-warning"></i>
                                                    <?php endif; ?>
                                                </label>
                                            </div>
                                            <div class="mt-2">
                                                <small class="text-muted d-block">
                                                    Monthly contribution:
                                                    <strong>KES <?php echo number_format($package['monthly_contribution'], 2); ?></strong>
                                                </small>
                                                <?php if (isset($package['age_min']) && isset($package['age_max'])): ?>
                                                    <small class="text-muted d-block">
                                                        Eligible ages: <?php echo (int)$package['age_min']; ?> - <?php echo (int)$package['age_max']; ?> years
                                                    </small>
                                                <?php endif; ?>
                                                <?php if (isset($package['max_children']) || isset($package['max_parents']) || isset($package['max_inlaws'])): ?>
                                                    <small class="text-muted d-block">
                                                        Coverage:
                                                        <?php if (isset($package['max_children'])): ?>
                                                            up to <?php echo (int)$package['max_children']; ?> children;
                                                        <?php endif; ?>
                                                        <?php if (isset($package['max_parents'])): ?>
                                                            up to <?php echo (int)$package['max_parents']; ?> parents;
                                                        <?php endif; ?>
                                                        <?php if (isset($package['max_inlaws'])): ?>
                                                            up to <?php echo (int)$package['max_inlaws']; ?> in-laws;
                                                        <?php endif; ?>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Password -->
                        <h5 class="border-bottom pb-2 mb-3 mt-4">Account Security</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label <?php echo hasError('password') ? 'error-field-label' : ''; ?>">Password *</label>
                                <input type="password" class="form-control <?php echo hasError('password') ? 'is-invalid shake' : ''; ?>" id="password" name="password" required>
                                <div class="form-text">Minimum 8 characters</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label <?php echo hasError('confirm_password') ? 'error-field-label' : ''; ?>">Confirm Password *</label>
                                <input type="password" class="form-control <?php echo hasError('confirm_password') ? 'is-invalid shake' : ''; ?>" id="confirm_password" name="confirm_password" required>
                            </div>
                        </div>
                        
                        <!-- Terms and Conditions -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" target="_blank">Terms and Conditions</a> and 
                                <a href="#" target="_blank">Privacy Policy</a> *
                            </label>
                        </div>
                        
                        <!-- Payment Information -->
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Payment Information</h6>
                            <p class="mb-2">
                                After registration, you can pay the registration fee of 
                                <strong>KES 200</strong> using:
                            </p>
                            <ul class="mb-0">
                                <li><strong>STK Push</strong> - Instant payment prompt to your phone (Recommended)</li>
                                <li><strong>M-Pesa Paybill</strong> - Manual payment via M-Pesa to shortcode <strong><?php echo MPESA_BUSINESS_SHORTCODE; ?></strong></li>
                            </ul>
                            <p class="mb-0 mt-2">
                                <small class="text-muted">Your account will be activated once payment is confirmed.</small>
                            </p>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-user-plus"></i> Register Now
                            </button>
                        </div>
                    </form>
                    
                    <hr>
                    
                    <div class="text-center">
                        <p>Already have an account? <a href="/login">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Real-time password confirmation validation
document.addEventListener('DOMContentLoaded', function() {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    
    function validatePasswordMatch() {
        if (confirmPassword.value && password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Passwords do not match');
            confirmPassword.classList.add('is-invalid');
        } else {
            confirmPassword.setCustomValidity('');
            confirmPassword.classList.remove('is-invalid');
        }
    }
    
    password.addEventListener('input', validatePasswordMatch);
    confirmPassword.addEventListener('input', validatePasswordMatch);
    
    // Scroll to first error field if present
    const errorField = document.querySelector('.is-invalid');
    if (errorField) {
        setTimeout(function() {
            errorField.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });
            errorField.focus();
        }, 300);
    }
});
</script>

<?php 
// Clear old input after displaying
unset($_SESSION['old_input'], $_SESSION['error_field']);
include VIEWS_PATH . '/layouts/footer.php'; 
?>
