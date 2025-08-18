<?php include VIEWS_PATH . '/layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="display-1 text-warning mb-4">
                <i class="fas fa-ban"></i>
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
            
            <?php if (!isLoggedIn()): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    You may need to <a href="/login">login</a> to access this resource.
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    You don't have sufficient privileges to access this resource.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>
