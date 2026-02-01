<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'SHENA Companion - Dignified Send-off, Lasting Support'; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- SHENA Design System CSS -->
    <link href="/css/shena-main.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg sticky-top" style="background: white; padding: 0.75rem 0; box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
        <div class="container">
            <a class="navbar-brand" href="/" style="display: flex; align-items: center; gap: 12px; margin: 0; padding: 0;">
                <img src="/public/images/shena-logo.png" alt="SHENA Companion" style="height: 80px; width: auto;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto align-items-center" style="gap: 0.5rem;">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($page ?? '') === 'home' ? 'active' : ''; ?>" href="/" style="color: <?php echo ($page ?? '') === 'home' ? '#7F3D9E' : '#000000'; ?>; font-weight: 500; padding: 0.5rem 1rem;">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($page ?? '') === 'about' ? 'active' : ''; ?>" href="/about" style="color: <?php echo ($page ?? '') === 'about' ? '#7F3D9E' : '#000000'; ?>; font-weight: 500; padding: 0.5rem 1rem;">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($page ?? '') === 'services' ? 'active' : ''; ?>" href="/services" style="color: <?php echo ($page ?? '') === 'services' ? '#7F3D9E' : '#000000'; ?>; font-weight: 500; padding: 0.5rem 1rem;">Welfare Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($page ?? '') === 'packages' ? 'active' : ''; ?>" href="/membership" style="color: <?php echo ($page ?? '') === 'packages' ? '#7F3D9E' : '#000000'; ?>; font-weight: 500; padding: 0.5rem 1rem;">Packages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($page ?? '') === 'claims' ? 'active' : ''; ?>" href="/claims" style="color: <?php echo ($page ?? '') === 'claims' ? '#7F3D9E' : '#000000'; ?>; font-weight: 500; padding: 0.5rem 1rem;">Claims</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($page ?? '') === 'contact' ? 'active' : ''; ?>" href="/contact" style="color: <?php echo ($page ?? '') === 'contact' ? '#7F3D9E' : '#000000'; ?>; font-weight: 500; padding: 0.5rem 1rem;">Contact</a>
                    </li>
                </ul>
                <ul class="navbar-nav align-items-center">
                    <?php if (isLoggedIn()): ?>
                        <?php if (isAdmin()): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/admin"><i class="fas fa-cog"></i> Admin</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a href="/register" class="btn" style="background-color: #7F3D9E; color: white; padding: 10px 28px; border-radius: 25px; font-weight: 500; border: none; white-space: nowrap;">Member Portal</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php 
    $success = getFlashMessage('success');
    $error = getFlashMessage('error');
    $info = getFlashMessage('info');
    $warning = getFlashMessage('warning');
    ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show m-0" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo e($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show m-0" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?php echo e($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($info): ?>
        <div class="alert alert-info alert-dismissible fade show m-0" role="alert">
            <i class="fas fa-info-circle"></i> <?php echo e($info); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($warning): ?>
        <div class="alert alert-warning alert-dismissible fade show m-0" role="alert">
            <i class="fas fa-exclamation-triangle"></i> <?php echo e($warning); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <main>
