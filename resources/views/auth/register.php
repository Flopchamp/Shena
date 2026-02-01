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
    .package-option {
        transition: opacity 0.3s ease;
    }
    .package-disabled {
        opacity: 0.5;
        pointer-events: none;
    }
    #ageFilterMessage {
        animation: slideDown 0.3s ease;
    }
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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
                        <div id="ageFilterMessage" class="alert alert-warning d-none mb-3">
                            <i class="fas fa-info-circle"></i> <span id="ageFilterText"></span>
                        </div>
                        <div class="row" id="packagesContainer">
                            <?php foreach ($packages as $key => $package): ?>
                                <div class="col-md-6 mb-3 package-option" 
                                     data-package-id="<?php echo $key; ?>"
                                     data-age-min="<?php echo isset($package['age_min']) ? (int)$package['age_min'] : 0; ?>"
                                     data-age-max="<?php echo isset($package['age_max']) ? (int)$package['age_max'] : 999; ?>">
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
    const registrationForm = document.querySelector('form[action="/register"]');
    const submitButton = registrationForm.querySelector('button[type="submit"]');
    const dateOfBirthInput = document.getElementById('date_of_birth');
    const ageFilterMessage = document.getElementById('ageFilterMessage');
    const ageFilterText = document.getElementById('ageFilterText');
    const packageOptions = document.querySelectorAll('.package-option');
    
    // Function to calculate age from date of birth
    function calculateAge(dateOfBirth) {
        if (!dateOfBirth) return null;
        const today = new Date();
        const birthDate = new Date(dateOfBirth);
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
    }
    
    // Function to filter packages based on age
    function filterPackagesByAge() {
        const dateOfBirth = dateOfBirthInput.value;
        if (!dateOfBirth) {
            // Show all packages if no date entered
            packageOptions.forEach(option => {
                option.style.display = '';
                option.classList.remove('package-disabled');
            });
            ageFilterMessage.classList.add('d-none');
            return;
        }
        
        const age = calculateAge(dateOfBirth);
        
        if (age === null || age < 18) {
            ageFilterMessage.classList.remove('d-none');
            ageFilterMessage.classList.remove('alert-warning');
            ageFilterMessage.classList.add('alert-danger');
            ageFilterText.textContent = age < 18 ? 
                'You must be at least 18 years old to register.' : 
                'Please enter a valid date of birth.';
            packageOptions.forEach(option => option.style.display = 'none');
            return;
        }
        
        let eligibleCount = 0;
        packageOptions.forEach(option => {
            const ageMin = parseInt(option.dataset.ageMin) || 0;
            const ageMax = parseInt(option.dataset.ageMax) || 999;
            
            if (age >= ageMin && age <= ageMax) {
                option.style.display = '';
                option.classList.remove('package-disabled');
                eligibleCount++;
            } else {
                option.style.display = 'none';
                option.classList.add('package-disabled');
                // Uncheck if this package was selected
                const radio = option.querySelector('input[type="radio"]');
                if (radio && radio.checked) {
                    radio.checked = false;
                }
            }
        });
        
        // Show filter message
        if (eligibleCount === 0) {
            ageFilterMessage.classList.remove('d-none');
            ageFilterMessage.classList.remove('alert-warning');
            ageFilterMessage.classList.add('alert-danger');
            ageFilterText.textContent = `No packages available for age ${age}. Please contact support.`;
        } else if (eligibleCount < packageOptions.length) {
            ageFilterMessage.classList.remove('d-none');
            ageFilterMessage.classList.remove('alert-danger');
            ageFilterMessage.classList.add('alert-warning');
            ageFilterText.textContent = `Showing ${eligibleCount} package(s) eligible for age ${age} years.`;
        } else {
            ageFilterMessage.classList.add('d-none');
        }
    }
    
    // Listen for date of birth changes
    dateOfBirthInput.addEventListener('change', filterPackagesByAge);
    dateOfBirthInput.addEventListener('blur', filterPackagesByAge);
    
    // Filter on page load if date is already entered
    if (dateOfBirthInput.value) {
        filterPackagesByAge();
    }
    
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
    
    // Handle form submission via AJAX
    registrationForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Disable submit button
        const originalText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        
        // Clear any previous errors
        document.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid', 'shake');
        });
        document.querySelectorAll('.error-field-label').forEach(el => {
            el.classList.remove('error-field-label');
        });
        document.querySelectorAll('.alert-danger').forEach(el => el.remove());
        
        try {
            const formData = new FormData(registrationForm);
            
            const response = await fetch('/register', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Show success message
                const successAlert = document.createElement('div');
                successAlert.className = 'alert alert-success alert-dismissible fade show';
                successAlert.innerHTML = `
                    <i class="fas fa-check-circle"></i> ${result.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                registrationForm.insertBefore(successAlert, registrationForm.firstChild);
                
                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
                
                // Reset form after short delay
                setTimeout(() => {
                    registrationForm.reset();
                }, 2000);
            } else {
                // Show error message
                const errorAlert = document.createElement('div');
                errorAlert.className = 'alert alert-danger alert-dismissible fade show';
                errorAlert.innerHTML = `
                    <i class="fas fa-exclamation-circle"></i> ${result.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                registrationForm.insertBefore(errorAlert, registrationForm.firstChild);
                
                // Highlight error field if specified
                if (result.field) {
                    const errorField = document.getElementById(result.field) || 
                                     document.querySelector(`[name="${result.field}"]`);
                    if (errorField) {
                        errorField.classList.add('is-invalid', 'shake');
                        const label = document.querySelector(`label[for="${result.field}"]`);
                        if (label) {
                            label.classList.add('error-field-label');
                        }
                        
                        // Scroll to error field
                        setTimeout(() => {
                            errorField.scrollIntoView({ 
                                behavior: 'smooth', 
                                block: 'center' 
                            });
                            errorField.focus();
                        }, 300);
                    }
                }
                
                // Scroll to top to show error message
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        } catch (error) {
            console.error('Registration error:', error);
            const errorAlert = document.createElement('div');
            errorAlert.className = 'alert alert-danger alert-dismissible fade show';
            errorAlert.innerHTML = `
                <i class="fas fa-exclamation-circle"></i> An unexpected error occurred. Please try again.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            registrationForm.insertBefore(errorAlert, registrationForm.firstChild);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } finally {
            // Re-enable submit button
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        }
    });
    
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
