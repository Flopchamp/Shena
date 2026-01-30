<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Shena Companion - Membership Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        
        .registration-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        
        .registration-header {
            background: var(--primary-color);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .registration-header h1 {
            margin: 0;
            font-size: 2rem;
        }
        
        .registration-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        
        .step-indicator {
            display: flex;
            justify-content: space-between;
            padding: 20px 30px;
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        
        .step {
            flex: 1;
            text-align: center;
            position: relative;
        }
        
        .step::after {
            content: '';
            position: absolute;
            top: 20px;
            left: 50%;
            width: 100%;
            height: 2px;
            background: #dee2e6;
            z-index: 0;
        }
        
        .step:last-child::after {
            display: none;
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            line-height: 40px;
            border-radius: 50%;
            background: #dee2e6;
            color: #6c757d;
            display: inline-block;
            font-weight: bold;
            position: relative;
            z-index: 1;
            transition: all 0.3s;
        }
        
        .step.active .step-number {
            background: var(--secondary-color);
            color: white;
        }
        
        .step.completed .step-number {
            background: var(--success-color);
            color: white;
        }
        
        .step-label {
            display: block;
            margin-top: 8px;
            font-size: 0.85rem;
            color: #6c757d;
        }
        
        .step.active .step-label {
            color: var(--secondary-color);
            font-weight: 600;
        }
        
        .registration-body {
            padding: 40px;
        }
        
        .form-section {
            display: none;
        }
        
        .form-section.active {
            display: block;
            animation: fadeIn 0.5s;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .package-card {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }
        
        .package-card:hover {
            border-color: var(--secondary-color);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.2);
        }
        
        .package-card.selected {
            border-color: var(--success-color);
            background: #f0f9f4;
        }
        
        .package-card.selected::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            top: 15px;
            right: 15px;
            color: var(--success-color);
            font-size: 1.5rem;
        }
        
        .package-name {
            font-size: 1.25rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .package-price {
            font-size: 1.5rem;
            color: var(--secondary-color);
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .package-features {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .package-features li {
            padding: 5px 0;
            color: #555;
        }
        
        .package-features li i {
            color: var(--success-color);
            margin-right: 8px;
        }
        
        .age-filter {
            background: #e3f2fd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        /* Invalid field styling */
        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #dc3545 !important;
            background-color: #fff5f5 !important;
            animation: shake 0.5s;
        }
        
        .form-control.is-invalid:focus,
        .form-select.is-invalid:focus {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        .btn-navigation {
            padding: 12px 30px;
            font-size: 1rem;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: var(--secondary-color);
            border: none;
        }
        
        .btn-primary:hover {
            background: #2980b9;
        }
        
        .summary-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .summary-item:last-child {
            border-bottom: none;
        }
        
        .summary-label {
            font-weight: 600;
            color: #555;
        }
        
        .summary-value {
            color: var(--primary-color);
        }
        
        .payment-method {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .payment-method:hover {
            border-color: var(--secondary-color);
        }
        
        .payment-method.selected {
            border-color: var(--success-color);
            background: #f0f9f4;
        }
        
        .alert-info-custom {
            background: #d1ecf1;
            border-left: 4px solid #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .mpesa-instructions {
            background: #fff3cd;
            border-left: 4px solid #856404;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        
        .success-icon {
            font-size: 4rem;
            color: var(--success-color);
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <!-- Header -->
        <div class="registration-header">
            <h1><i class="fas fa-handshake"></i> Join Shena Companion</h1>
            <p>Secure your family's future with our comprehensive welfare coverage</p>
        </div>
        
        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step active" data-step="1">
                <span class="step-number">1</span>
                <span class="step-label">Select Package</span>
            </div>
            <div class="step" data-step="2">
                <span class="step-number">2</span>
                <span class="step-label">Personal Info</span>
            </div>
            <div class="step" data-step="3">
                <span class="step-number">3</span>
                <span class="step-label">Payment</span>
            </div>
            <div class="step" data-step="4">
                <span class="step-number">4</span>
                <span class="step-label">Confirmation</span>
            </div>
        </div>
        
        <!-- Registration Body -->
        <div class="registration-body">
            <form id="registrationForm" method="POST" action="/register">
                
                <!-- Step 1: Package Selection -->
                <div class="form-section active" data-section="1">
                    <h3 class="mb-4">Choose Your Membership Package</h3>
                    
                    <!-- Age Filter -->
                    <div class="age-filter">
                        <label class="form-label"><i class="fas fa-calendar-alt"></i> Your Age (for package recommendations):</label>
                        <input type="number" id="ageInput" class="form-control" placeholder="Enter your age" min="18" max="100">
                        <small class="text-muted">This helps us recommend suitable packages</small>
                    </div>
                    
                    <!-- Package Cards -->
                    <div id="packageList">
                        <!-- Packages will be loaded here dynamically -->
                    </div>
                    
                    <input type="hidden" name="package_id" id="selectedPackageId">
                    
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-primary btn-navigation" onclick="nextStep(1)">
                            Next <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Step 2: Personal Information -->
                <div class="form-section" data-section="2">
                    <h3 class="mb-4">Personal Information</h3>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">First Name *</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last Name *</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">National ID Number *</label>
                            <input type="text" name="national_id" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date of Birth *</label>
                            <input type="date" name="date_of_birth" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number *</label>
                            <input type="tel" name="phone" class="form-control" placeholder="0712345678" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Physical Address *</label>
                        <textarea name="address" class="form-control" rows="2" required></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">County *</label>
                            <input type="text" name="county" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Sub-County</label>
                            <input type="text" name="sub_county" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Postal Code</label>
                            <input type="text" name="postal_code" class="form-control">
                        </div>
                    </div>
                    
                    <div class="alert-info-custom">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> All information provided will be kept confidential and used only for membership management purposes.
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-secondary btn-navigation" onclick="prevStep(2)">
                            <i class="fas fa-arrow-left"></i> Back
                        </button>
                        <button type="button" class="btn btn-primary btn-navigation" onclick="nextStep(2)">
                            Next <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Step 3: Payment -->
                <div class="form-section" data-section="3">
                    <h3 class="mb-4">Registration Payment</h3>
                    
                    <!-- Summary -->
                    <div class="summary-box">
                        <h5 class="mb-3">Order Summary</h5>
                        <div class="summary-item">
                            <span class="summary-label">Package:</span>
                            <span class="summary-value" id="summaryPackage">-</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Monthly Contribution:</span>
                            <span class="summary-value" id="summaryMonthly">-</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Registration Fee:</span>
                            <span class="summary-value">KES 200</span>
                        </div>
                        <div class="summary-item" style="font-size: 1.2rem;">
                            <span class="summary-label"><strong>Total Due Today:</strong></span>
                            <span class="summary-value"><strong>KES 200</strong></span>
                        </div>
                    </div>
                    
                    <!-- Payment Method Selection -->
                    <h5 class="mb-3">Choose Payment Method</h5>
                    
                    <div class="payment-method" data-method="mpesa" onclick="selectPaymentMethod('mpesa')">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-mobile-alt fa-2x me-3" style="color: #27ae60;"></i>
                            <div>
                                <h5 class="mb-0">M-Pesa (Recommended)</h5>
                                <small class="text-muted">Instant payment confirmation</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="payment-method" data-method="cash" onclick="selectPaymentMethod('cash')">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-money-bill-wave fa-2x me-3" style="color: #3498db;"></i>
                            <div>
                                <h5 class="mb-0">Office Visit (Cash/Cheque)</h5>
                                <small class="text-muted">Pay at our office within 2 weeks</small>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="payment_method" id="selectedPaymentMethod">
                    
                    <!-- M-Pesa Instructions (Hidden by default) -->
                    <div id="mpesaInstructions" class="mpesa-instructions" style="display: none;">
                        <h5><i class="fas fa-mobile-alt"></i> M-Pesa Payment Instructions</h5>
                        <ol>
                            <li>Go to M-Pesa menu on your phone</li>
                            <li>Select <strong>Lipa Na M-Pesa</strong></li>
                            <li>Select <strong>Pay Bill</strong></li>
                            <li>Enter Business Number: <strong><?php echo MPESA_BUSINESS_SHORTCODE; ?></strong></li>
                            <li>Enter Account Number: <strong>REG[Your Phone]</strong> (e.g., REG0712345678)</li>
                            <li>Enter Amount: <strong>KES 200</strong></li>
                            <li>Enter your M-Pesa PIN and confirm</li>
                        </ol>
                        <p class="mb-0"><strong>Note:</strong> You will receive an M-Pesa confirmation message. Keep it for reference.</p>
                    </div>
                    
                    <!-- Cash Instructions (Hidden by default) -->
                    <div id="cashInstructions" class="alert-info-custom" style="display: none;">
                        <h5><i class="fas fa-info-circle"></i> Office Visit Payment</h5>
                        <p>By selecting this option, you agree to visit our office and complete payment within <strong>2 weeks</strong>.</p>
                        <p><strong>Office Location:</strong><br>
                        Shena Companion Welfare Association<br>
                        [Office Address]<br>
                        Office Hours: Monday - Friday, 9:00 AM - 5:00 PM</p>
                        <p class="mb-0"><strong>Important:</strong> Your membership will be pending until payment is confirmed. You must complete payment within 2 weeks or your application will be cancelled.</p>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-secondary btn-navigation" onclick="prevStep(3)">
                            <i class="fas fa-arrow-left"></i> Back
                        </button>
                        <button type="submit" class="btn btn-success btn-navigation" id="submitBtn">
                            <i class="fas fa-check"></i> Complete Registration
                        </button>
                    </div>
                </div>
                
                <!-- Step 4: Confirmation (Shown after successful submission) -->
                <div class="form-section" data-section="4">
                    <div class="text-center">
                        <div class="success-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3 class="mb-3">Registration Submitted Successfully!</h3>
                        <p class="lead">Thank you for joining Shena Companion Welfare Association.</p>
                        
                        <div class="summary-box text-start mt-4">
                            <h5 class="mb-3">What Happens Next?</h5>
                            <div class="alert-info-custom">
                                <p><strong><i class="fas fa-clock"></i> M-Pesa Payment:</strong></p>
                                <ul>
                                    <li>Your payment will be verified within 24 hours</li>
                                    <li>You will receive a confirmation email and SMS</li>
                                    <li>Your membership becomes active after payment confirmation</li>
                                    <li>Maturity period: 4-5 months before full benefits coverage</li>
                                </ul>
                                
                                <p><strong><i class="fas fa-building"></i> Office Payment:</strong></p>
                                <ul>
                                    <li>Visit our office within 2 weeks to complete payment</li>
                                    <li>Bring your National ID and phone for verification</li>
                                    <li>You will receive your membership number after payment</li>
                                    <li>Failure to pay within 2 weeks will result in application cancellation</li>
                                </ul>
                            </div>
                            
                            <p><strong>Need Help?</strong></p>
                            <p>Contact us:<br>
                            <i class="fas fa-phone"></i> <?php echo ADMIN_PHONE; ?><br>
                            <i class="fas fa-envelope"></i> <?php echo ADMIN_EMAIL; ?></p>
                        </div>
                        
                        <div class="mt-4">
                            <a href="/" class="btn btn-primary btn-navigation">
                                <i class="fas fa-home"></i> Return to Home
                            </a>
                            <a href="/login" class="btn btn-secondary btn-navigation">
                                <i class="fas fa-sign-in-alt"></i> Login to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Membership packages data
        const packages = <?php echo json_encode($packages ?? []); ?>;
        
        let currentStep = 1;
        let selectedPackage = null;
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadPackages();
            
            // Age filter
            document.getElementById('ageInput').addEventListener('input', function() {
                filterPackagesByAge(this.value);
            });
        });
        
        // Load packages
        function loadPackages(ageFilter = null) {
            const packageList = document.getElementById('packageList');
            packageList.innerHTML = '';
            
            let hasVisiblePackages = false;
            
            packages.forEach(pkg => {
                // Apply age filter - check if user's age is within package age range
                if (ageFilter) {
                    const age = parseInt(ageFilter);
                    if (pkg.age_min && pkg.age_max) {
                        if (age < pkg.age_min || age > pkg.age_max) {
                            return; // Skip this package
                        }
                    }
                }
                
                hasVisiblePackages = true;
                
                const card = document.createElement('div');
                card.className = 'package-card';
                card.setAttribute('data-package-id', pkg.id);
                card.onclick = () => selectPackage(pkg.id);
                
                let features = `
                    <li><i class="fas fa-check-circle"></i> Monthly: KES ${pkg.monthly_contribution.toLocaleString()}</li>
                `;
                
                if (pkg.age_min && pkg.age_max) {
                    features += `<li><i class="fas fa-info-circle"></i> Age range: ${pkg.age_min}-${pkg.age_max} years</li>`;
                }
                
                if (pkg.max_children) {
                    features += `<li><i class="fas fa-child"></i> Up to ${pkg.max_children} children</li>`;
                }
                
                if (pkg.max_parents) {
                    features += `<li><i class="fas fa-users"></i> Up to ${pkg.max_parents} parents</li>`;
                }
                
                if (pkg.max_inlaws) {
                    features += `<li><i class="fas fa-users"></i> Up to ${pkg.max_inlaws} in-laws</li>`;
                }
                
                card.innerHTML = `
                    <div class="package-name">${pkg.name}</div>
                    <div class="package-price">KES ${pkg.monthly_contribution.toLocaleString()}/month</div>
                    <ul class="package-features">
                        ${features}
                    </ul>
                `;
                
                packageList.appendChild(card);
            });
            
            if (!hasVisiblePackages && ageFilter) {
                packageList.innerHTML = '<p class="text-center text-muted">No packages available for the selected age. Please adjust your age.</p>';
            }
        }
        
        // Filter packages by age
        function filterPackagesByAge(age) {
            if (age) {
                loadPackages(age);
            } else {
                loadPackages();
            }
        }
        
        // Select package
        function selectPackage(packageId) {
            // Remove previous selection
            document.querySelectorAll('.package-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selection
            const card = document.querySelector(`[data-package-id="${packageId}"]`);
            card.classList.add('selected');
            
            selectedPackage = packages.find(p => p.id == packageId);
            document.getElementById('selectedPackageId').value = packageId;
        }
        
        // Select payment method
        function selectPaymentMethod(method) {
            // Remove previous selection
            document.querySelectorAll('.payment-method').forEach(pm => {
                pm.classList.remove('selected');
            });
            
            // Add selection
            const paymentMethod = document.querySelector(`[data-method="${method}"]`);
            paymentMethod.classList.add('selected');
            
            document.getElementById('selectedPaymentMethod').value = method;
            
            // Show/hide instructions
            document.getElementById('mpesaInstructions').style.display = method === 'mpesa' ? 'block' : 'none';
            document.getElementById('cashInstructions').style.display = method === 'cash' ? 'block' : 'none';
        }
        
        // Navigation functions
        function nextStep(step) {
            // Validate current step
            if (step === 1 && !selectedPackage) {
                alert('Please select a membership package');
                return;
            }
            
            if (step === 2) {
                // Validate form fields
                const form = document.getElementById('registrationForm');
                const inputs = form.querySelectorAll('[data-section="2"] input[required], [data-section="2"] textarea[required]');
                let valid = true;
                
                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        input.classList.add('is-invalid');
                        valid = false;
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });
                
                if (!valid) {
                    alert('Please fill in all required fields');
                    return;
                }
                
                // Update summary
                document.getElementById('summaryPackage').textContent = selectedPackage.name;
                document.getElementById('summaryMonthly').textContent = `KES ${selectedPackage.monthly_contribution.toLocaleString()}`;
            }
            
            // Move to next step
            currentStep++;
            updateStepIndicator();
            showSection(currentStep);
        }
        
        function prevStep(step) {
            currentStep--;
            updateStepIndicator();
            showSection(currentStep);
        }
        
        function updateStepIndicator() {
            document.querySelectorAll('.step').forEach(step => {
                const stepNum = parseInt(step.getAttribute('data-step'));
                step.classList.remove('active', 'completed');
                
                if (stepNum === currentStep) {
                    step.classList.add('active');
                } else if (stepNum < currentStep) {
                    step.classList.add('completed');
                }
            });
        }
        
        function showSection(section) {
            document.querySelectorAll('.form-section').forEach(sec => {
                sec.classList.remove('active');
            });
            document.querySelector(`[data-section="${section}"]`).classList.add('active');
        }
        
        // Function to repopulate form with old values
        function repopulateForm(oldValues) {
            if (!oldValues) return;
            
            // Iterate through old values and set form fields
            for (const [key, value] of Object.entries(oldValues)) {
                const field = document.querySelector(`[name="${key}"]`);
                if (field) {
                    if (field.type === 'radio' || field.type === 'checkbox') {
                        if (field.value === value) {
                            field.checked = true;
                        }
                    } else {
                        field.value = value;
                    }
                }
            }
        }
        
        // Function to highlight error field
        function highlightErrorField(fieldName) {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.classList.add('is-invalid');
                field.style.borderColor = '#dc3545';
                field.style.backgroundColor = '#fff5f5';
                
                // Scroll to the error field
                field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Focus on the field
                setTimeout(() => field.focus(), 500);
                
                // Remove error styling after user starts typing
                field.addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                    this.style.borderColor = '';
                    this.style.backgroundColor = '';
                }, { once: true });
            }
        }
        
        // Form submission
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate payment method
            if (!document.getElementById('selectedPaymentMethod').value) {
                alert('Please select a payment method');
                return;
            }
            
            // Disable submit button to prevent double submission
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            
            // Submit form
            const formData = new FormData(this);
            
            fetch('/register', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show confirmation step
                    currentStep = 4;
                    updateStepIndicator();
                    showSection(4);
                } else {
                    // Restore form values
                    if (data.old_values) {
                        repopulateForm(data.old_values);
                    }
                    
                    // Highlight error field if specified
                    if (data.field) {
                        highlightErrorField(data.field);
                        
                        // Navigate to the step containing the error field
                        const errorField = document.querySelector(`[name="${data.field}"]`);
                        if (errorField) {
                            const section = errorField.closest('.form-section');
                            if (section) {
                                const stepNum = parseInt(section.dataset.section);
                                if (stepNum !== currentStep) {
                                    currentStep = stepNum;
                                    updateStepIndicator();
                                    showSection(stepNum);
                                }
                            }
                        }
                    }
                    
                    // Show error message
                    alert('Registration failed: ' + data.message);
                    
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            })
            .catch(error => {
                alert('An error occurred. Please try again.');
                console.error(error);
                
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    </script>
</body>
</html>
