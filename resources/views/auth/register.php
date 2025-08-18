<?php include VIEWS_PATH . '/layouts/header.php'; ?>

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
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control" id="phone" name="phone" placeholder="+254..." required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="id_number" class="form-label">National ID Number *</label>
                                <input type="text" class="form-control" id="id_number" name="id_number" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Gender *</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address">
                            </div>
                        </div>
                        
                        <!-- Next of Kin Information -->
                        <h5 class="border-bottom pb-2 mb-3 mt-4">Next of Kin Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="next_of_kin" class="form-label">Next of Kin Name</label>
                                <input type="text" class="form-control" id="next_of_kin" name="next_of_kin">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="next_of_kin_phone" class="form-label">Next of Kin Phone</label>
                                <input type="tel" class="form-control" id="next_of_kin_phone" name="next_of_kin_phone">
                            </div>
                        </div>
                        
                        <!-- Membership Package -->
                        <h5 class="border-bottom pb-2 mb-3 mt-4">Membership Package</h5>
                        <div class="row">
                            <?php foreach ($packages as $key => $package): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card <?php echo $key === 'executive' ? 'border-warning' : ''; ?>">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="package" 
                                                       id="package_<?php echo $key; ?>" value="<?php echo $key; ?>"
                                                       <?php echo $key === 'individual' ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="package_<?php echo $key; ?>">
                                                    <strong><?php echo e($package['name']); ?></strong>
                                                    <?php if ($key === 'executive'): ?>
                                                        <i class="fas fa-crown text-warning"></i>
                                                    <?php endif; ?>
                                                </label>
                                            </div>
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    Base contribution: KES <?php echo number_format($package['base_price']); ?>/month
                                                    <?php if (isset($package['discount'])): ?>
                                                        <br><span class="text-success">
                                                            <?php echo ($package['discount'] * 100); ?>% family discount
                                                        </span>
                                                    <?php endif; ?>
                                                </small>
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
                                <label for="password" class="form-label">Password *</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="form-text">Minimum 8 characters</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password *</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
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
                            <p class="mb-0">
                                After registration, you will receive instructions to pay the registration fee of 
                                <strong>KES 200</strong> via M-Pesa Paybill <strong>4163987</strong>. 
                                Your account will be activated once payment is confirmed.
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
});
</script>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>
