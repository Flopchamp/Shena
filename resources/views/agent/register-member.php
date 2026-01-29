<?php $page = 'register'; include __DIR__ . '/../layouts/agent-header.php'; ?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="fas fa-user-plus text-primary"></i> Register New Member
            </h2>
            <p class="text-muted mb-0">Add a new member to the association</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="/agent/members" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Members
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-wpforms"></i> Member Registration Form
    </div>
    <div class="card-body">
        <form method="POST" action="/agent/register-member/store" id="memberRegistrationForm">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
            
            <!-- Personal Information Section -->
            <div class="mb-4">
                <h5 class="border-bottom pb-2 mb-3">
                    <i class="fas fa-user"></i> Personal Information
                </h5>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="id_number" class="form-label">National ID Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="id_number" name="id_number" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Contact Information Section -->
            <div class="mb-4">
                <h5 class="border-bottom pb-2 mb-3">
                    <i class="fas fa-address-book"></i> Contact Information
                </h5>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="phone" name="phone" required placeholder="+254712345678">
                        <small class="form-text text-muted">Format: +254712345678</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Physical Address</label>
                    <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                </div>
            </div>

            <!-- Next of Kin Information Section -->
            <div class="mb-4">
                <h5 class="border-bottom pb-2 mb-3">
                    <i class="fas fa-user-friends"></i> Next of Kin Information
                </h5>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="next_of_kin" class="form-label">Next of Kin Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="next_of_kin" name="next_of_kin" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="next_of_kin_phone" class="form-label">Next of Kin Phone <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="next_of_kin_phone" name="next_of_kin_phone" required>
                    </div>
                </div>
            </div>

            <!-- Package Selection Section -->
            <div class="mb-4">
                <h5 class="border-bottom pb-2 mb-3">
                    <i class="fas fa-box"></i> Package Selection
                </h5>
                
                <div class="mb-3">
                    <label for="package" class="form-label">Select Package <span class="text-danger">*</span></label>
                    <select class="form-select" id="package" name="package" required>
                        <option value="">Choose a package</option>
                        <option value="individual">Individual - KES 500/month</option>
                        <option value="couple">Couple - KES 800/month</option>
                        <option value="family">Family - KES 1,200/month</option>
                        <option value="executive">Executive - KES 2,000/month</option>
                    </select>
                    <small class="form-text text-muted">Package determines coverage benefits and monthly contribution</small>
                </div>
            </div>

            <!-- Password Section -->
            <div class="mb-4">
                <h5 class="border-bottom pb-2 mb-3">
                    <i class="fas fa-lock"></i> Account Security
                </h5>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="8">
                        <small class="form-text text-muted">Minimum 8 characters</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
            </div>

            <!-- Terms and Conditions -->
            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                    <label class="form-check-label" for="terms">
                        I confirm that the member has read and agreed to the terms and conditions
                    </label>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="reset" class="btn btn-secondary">
                    <i class="fas fa-undo"></i> Reset Form
                </button>
                <button type="submit" class="btn btn-agent-primary">
                    <i class="fas fa-user-plus"></i> Register Member
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Form validation
document.getElementById('memberRegistrationForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Passwords do not match!');
        return false;
    }
});
</script>

<?php include __DIR__ . '/../layouts/agent-footer.php'; ?>
