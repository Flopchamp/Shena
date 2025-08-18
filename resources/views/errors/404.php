<?php include VIEWS_PATH . '/layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="display-1 text-muted mb-4">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h1 class="display-4 mb-3"><?php echo e($error_code); ?></h1>
            <h2 class="h4 mb-4 text-muted"><?php echo e($error_message); ?></h2>
            
            <div class="mb-4">
                <a href="/" class="btn btn-primary me-3">
                    <i class="fas fa-home"></i> Go Home
                </a>
                <button onclick="history.back()" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Go Back
                </button>
            </div>
            
            <?php if ($error_code === '404'): ?>
                <div class="row mt-5">
                    <div class="col-md-6 mx-auto">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Quick Links</h5>
                                <ul class="list-unstyled">
                                    <li><a href="/" class="text-decoration-none">Home</a></li>
                                    <li><a href="/about" class="text-decoration-none">About Us</a></li>
                                    <li><a href="/membership" class="text-decoration-none">Membership</a></li>
                                    <li><a href="/services" class="text-decoration-none">Services</a></li>
                                    <li><a href="/contact" class="text-decoration-none">Contact</a></li>
                                    <?php if (isLoggedIn()): ?>
                                        <li><a href="/dashboard" class="text-decoration-none">Dashboard</a></li>
                                    <?php else: ?>
                                        <li><a href="/login" class="text-decoration-none">Login</a></li>
                                        <li><a href="/register" class="text-decoration-none">Register</a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>
