/**
 * Custom JavaScript for Shena Companion Welfare Association
 */

// Global app object
const ShenaApp = {
    // Configuration
    config: {
        mpesaPaybill: '4163987',
        maxFileSize: 5 * 1024 * 1024, // 5MB
        allowedFileTypes: ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']
    },

    // Initialize the application
    init: function() {
        this.setupEventListeners();
        this.initializeComponents();
        this.setupFormValidation();
    },

    // Setup event listeners
    setupEventListeners: function() {
        // Confirmation dialogs
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('confirm-delete') || 
                e.target.closest('.confirm-delete')) {
                if (!confirm('Are you sure you want to delete this item?')) {
                    e.preventDefault();
                    return false;
                }
            }

            if (e.target.classList.contains('confirm-action') || 
                e.target.closest('.confirm-action')) {
                const message = e.target.dataset.message || 'Are you sure you want to perform this action?';
                if (!confirm(message)) {
                    e.preventDefault();
                    return false;
                }
            }
        });

        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert.alert-dismissible');
            alerts.forEach(function(alert) {
                if (alert.classList.contains('show')) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);

        // Mobile navigation
        const navToggler = document.querySelector('.navbar-toggler');
        const navCollapse = document.querySelector('.navbar-collapse');
        
        if (navToggler && navCollapse) {
            navToggler.addEventListener('click', function() {
                navCollapse.classList.toggle('show');
            });
        }
    },

    // Initialize components
    initializeComponents: function() {
        this.initializePhoneFormatting();
        this.initializeFileUpload();
        this.initializeDatePickers();
        this.initializeTooltips();
        this.initializeModals();
    },

    // Phone number formatting
    initializePhoneFormatting: function() {
        const phoneInputs = document.querySelectorAll('input[type="tel"], input[name*="phone"]');
        
        phoneInputs.forEach(function(input) {
            input.addEventListener('blur', function() {
                ShenaApp.formatPhoneNumber(this);
            });

            input.addEventListener('input', function() {
                // Remove non-digit characters while typing
                this.value = this.value.replace(/[^\d+]/g, '');
            });
        });
    },

    // Format phone number
    formatPhoneNumber: function(input) {
        let value = input.value.replace(/\D/g, '');
        
        if (value.startsWith('254')) {
            value = '+' + value;
        } else if (value.startsWith('0')) {
            value = '+254' + value.substring(1);
        } else if (value.length === 9 && /^[17]/.test(value)) {
            value = '+254' + value;
        }
        
        input.value = value;
    },

    // File upload initialization
    initializeFileUpload: function() {
        const fileInputs = document.querySelectorAll('input[type="file"]');
        
        fileInputs.forEach(function(input) {
            input.addEventListener('change', function() {
                ShenaApp.validateFileUpload(this);
            });
        });
    },

    // Validate file upload
    validateFileUpload: function(input) {
        const files = input.files;
        const feedback = input.parentNode.querySelector('.invalid-feedback') || 
                        this.createFeedbackElement(input);

        let isValid = true;
        let message = '';

        if (files.length > 0) {
            const file = files[0];
            const fileSize = file.size;
            const fileName = file.name;
            const fileExt = fileName.split('.').pop().toLowerCase();

            // Check file size
            if (fileSize > this.config.maxFileSize) {
                isValid = false;
                message = 'File size must be less than 5MB';
            }

            // Check file type
            if (!this.config.allowedFileTypes.includes(fileExt)) {
                isValid = false;
                message = 'Invalid file type. Allowed: ' + this.config.allowedFileTypes.join(', ');
            }
        }

        if (isValid) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            feedback.style.display = 'none';
        } else {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            feedback.textContent = message;
            feedback.style.display = 'block';
        }
    },

    // Create feedback element
    createFeedbackElement: function(input) {
        const feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        input.parentNode.appendChild(feedback);
        return feedback;
    },

    // Initialize date pickers
    initializeDatePickers: function() {
        const dateInputs = document.querySelectorAll('input[type="date"]');
        
        dateInputs.forEach(function(input) {
            // Set max date for date of birth to 100 years ago
            if (input.name === 'date_of_birth') {
                const today = new Date();
                const maxDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
                const minDate = new Date(today.getFullYear() - 100, today.getMonth(), today.getDate());
                
                input.max = maxDate.toISOString().split('T')[0];
                input.min = minDate.toISOString().split('T')[0];
            }
        });
    },

    // Initialize tooltips
    initializeTooltips: function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    },

    // Initialize modals
    initializeModals: function() {
        const modalElements = document.querySelectorAll('.modal');
        modalElements.forEach(function(modalEl) {
            new bootstrap.Modal(modalEl);
        });
    },

    // Form validation
    setupFormValidation: function() {
        const forms = document.querySelectorAll('.needs-validation');
        
        forms.forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        });

        // Password confirmation validation
        this.setupPasswordConfirmation();
        this.setupBeneficiaryPercentageValidation();
    },

    // Password confirmation validation
    setupPasswordConfirmation: function() {
        const passwordInputs = document.querySelectorAll('input[name="password"]');
        const confirmPasswordInputs = document.querySelectorAll('input[name="confirm_password"]');
        
        if (passwordInputs.length > 0 && confirmPasswordInputs.length > 0) {
            const password = passwordInputs[0];
            const confirmPassword = confirmPasswordInputs[0];
            
            const validateMatch = function() {
                if (confirmPassword.value && password.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('Passwords do not match');
                } else {
                    confirmPassword.setCustomValidity('');
                }
            };
            
            password.addEventListener('input', validateMatch);
            confirmPassword.addEventListener('input', validateMatch);
        }
    },

    // Beneficiary percentage validation
    setupBeneficiaryPercentageValidation: function() {
        const percentageInputs = document.querySelectorAll('input[name="percentage"]');
        
        percentageInputs.forEach(function(input) {
            input.addEventListener('input', function() {
                const value = parseFloat(this.value);
                if (value < 1 || value > 100) {
                    this.setCustomValidity('Percentage must be between 1 and 100');
                } else {
                    this.setCustomValidity('');
                }
            });
        });
    },

    // Payment functions
    payment: {
        initiate: function(memberId, amount, phoneNumber, paymentType = 'monthly') {
            const button = event.target;
            const originalText = button.innerHTML;
            
            // Show loading state
            button.innerHTML = '<span class="loading-spinner"></span> Processing...';
            button.disabled = true;
            
            const data = {
                member_id: memberId,
                amount: amount,
                phone_number: phoneNumber,
                payment_type: paymentType
            };
            
            fetch('/api/payment/initiate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    ShenaApp.showAlert('success', data.message);
                    // Optionally check payment status after a delay
                    setTimeout(() => {
                        ShenaApp.payment.checkStatus(data.checkout_request_id);
                    }, 10000); // Check after 10 seconds
                } else {
                    ShenaApp.showAlert('error', data.error || 'Payment initiation failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                ShenaApp.showAlert('error', 'An error occurred while processing payment');
            })
            .finally(() => {
                // Restore button state
                button.innerHTML = originalText;
                button.disabled = false;
            });
        },
        
        checkStatus: function(checkoutRequestId) {
            fetch(`/api/payment/status?checkout_request_id=${checkoutRequestId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.status) {
                    const status = data.status;
                    if (status.ResultCode === '0') {
                        ShenaApp.showAlert('success', 'Payment completed successfully!');
                        // Optionally reload page or update UI
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    } else if (status.ResultCode !== '1037') { // Not still processing
                        ShenaApp.showAlert('error', 'Payment failed: ' + status.ResultDesc);
                    }
                }
            })
            .catch(error => {
                console.error('Error checking payment status:', error);
            });
        }
    },

    // Utility functions
    showAlert: function(type, message) {
        const alertClass = type === 'error' ? 'alert-danger' : `alert-${type}`;
        const icon = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            info: 'fa-info-circle',
            warning: 'fa-exclamation-triangle'
        }[type] || 'fa-info-circle';

        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas ${icon}"></i> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        // Insert at top of main content or body
        const container = document.querySelector('main') || document.body;
        container.insertAdjacentHTML('afterbegin', alertHtml);
    },

    // Format currency
    formatCurrency: function(amount, currency = 'KES') {
        return `${currency} ${parseFloat(amount).toLocaleString('en-US', {minimumFractionDigits: 2})}`;
    },

    // Format date
    formatDate: function(dateString, options = {}) {
        const defaultOptions = {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        };
        
        const formatOptions = { ...defaultOptions, ...options };
        return new Date(dateString).toLocaleDateString('en-US', formatOptions);
    },

    // Copy to clipboard
    copyToClipboard: function(text) {
        navigator.clipboard.writeText(text).then(() => {
            this.showAlert('success', 'Copied to clipboard!');
        }).catch(() => {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            this.showAlert('success', 'Copied to clipboard!');
        });
    }
};

// Initialize app when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    ShenaApp.init();
});

// Export for global use
window.ShenaApp = ShenaApp;
