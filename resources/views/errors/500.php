<?php include VIEWS_PATH . '/layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="display-1 text-danger mb-4">
                <i class="fas fa-exclamation-circle"></i>
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
            
            <div class="alert alert-warning">
                <i class="fas fa-info-circle"></i>
                If you continue to experience issues, please contact our support team.
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>
