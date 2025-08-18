            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (for any legacy code) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- Chart.js for future charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
        
        // Confirmation dialogs for destructive actions
        document.addEventListener('click', function(e) {
            if (e.target.matches('[data-confirm]')) {
                const message = e.target.getAttribute('data-confirm');
                if (!confirm(message)) {
                    e.preventDefault();
                    return false;
                }
            }
        });
        
        // Auto-refresh dashboard stats every 30 seconds
        if (window.location.pathname === '/admin' || window.location.pathname === '/admin/dashboard') {
            setInterval(function() {
                // This would refresh dashboard stats via AJAX in a real implementation
                // For now, we'll just log to console
                console.log('Dashboard stats refresh interval (placeholder)');
            }, 30000);
        }
    </script>

    <!-- Footer -->
    <footer class="bg-light text-center text-muted py-3 mt-5">
        <div class="container">
            <p class="mb-1">
                <strong>Shena Companion Welfare Association</strong> - Admin Portal
            </p>
            <p class="mb-0">
                © <?php echo date('Y'); ?> All Rights Reserved. 
                <span class="text-primary">Version 1.0</span>
            </p>
            <small class="text-muted">
                Last login: <?php echo isset($_SESSION['last_login']) ? date('M j, Y \a\t g:i A', strtotime($_SESSION['last_login'])) : 'N/A'; ?>
            </small>
        </div>
    </footer>
</body>
</html>
