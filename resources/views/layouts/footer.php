    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-heart"></i> Shena Companion</h5>
                    <p>Providing affordable funeral services and burial expense coverage to our community members.</p>
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="/about" class="text-white-50">About Us</a></li>
                        <li><a href="/membership" class="text-white-50">Membership</a></li>
                        <li><a href="/services" class="text-white-50">Services</a></li>
                        <li><a href="/contact" class="text-white-50">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6>Contact Information</h6>
                    <ul class="list-unstyled text-white-50">
                        <li><i class="fas fa-phone"></i> +254 700 000 000</li>
                        <li><i class="fas fa-envelope"></i> info@shenacompanion.org</li>
                        <li><i class="fas fa-map-marker-alt"></i> Nairobi, Kenya</li>
                    </ul>
                    <div class="mt-3">
                        <h6>M-Pesa Paybill</h6>
                        <div class="bg-primary p-2 rounded text-center">
                            <strong>4163987</strong>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-white-50">&copy; <?php echo date('Y'); ?> Shena Companion Welfare Association. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-white-50 me-3">Privacy Policy</a>
                    <a href="#" class="text-white-50">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (alert.classList.contains('show')) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);

        // Confirmation for delete actions
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('confirm-delete') || 
                e.target.closest('.confirm-delete')) {
                if (!confirm('Are you sure you want to delete this item?')) {
                    e.preventDefault();
                    return false;
                }
            }
        });

        // Phone number formatting
        function formatPhoneNumber(input) {
            let value = input.value.replace(/\D/g, '');
            
            if (value.startsWith('254')) {
                value = '+' + value;
            } else if (value.startsWith('0')) {
                value = '+254' + value.substring(1);
            } else if (value.length === 9) {
                value = '+254' + value;
            }
            
            input.value = value;
        }

        // Initialize phone number formatting
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInputs = document.querySelectorAll('input[type="tel"], input[name*="phone"]');
            phoneInputs.forEach(function(input) {
                input.addEventListener('blur', function() {
                    formatPhoneNumber(this);
                });
            });
        });
    </script>
</body>
</html>
