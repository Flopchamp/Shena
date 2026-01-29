<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>SHENA Companion</title>
    <meta name="description" content="<?php echo isset($pageDescription) ? $pageDescription : 'Premium Welfare & Funeral Services'; ?>">
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <?php if (isset($additionalCSS)): ?>
        <?php foreach ($additionalCSS as $css): ?>
            <link rel="stylesheet" href="<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>

<!-- Public Navigation -->
<nav class="navbar">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center" style="width: 100%;">
            <a href="/" class="navbar-brand">SHENA COMPANION</a>
            
            <!-- Mobile Toggle -->
            <button data-nav-toggle class="btn btn-primary" style="display: none;">
                <i class="bi bi-list"></i>
            </button>
            
            <!-- Navigation Menu -->
            <ul class="navbar-nav" data-nav-menu>
                <li><a href="/" class="nav-link <?php echo isset($activePage) && $activePage == 'home' ? 'active' : ''; ?>">Home</a></li>
                <li><a href="/about" class="nav-link <?php echo isset($activePage) && $activePage == 'about' ? 'active' : ''; ?>">About</a></li>
                <li><a href="/services" class="nav-link <?php echo isset($activePage) && $activePage == 'services' ? 'active' : ''; ?>">Services</a></li>
                <li><a href="/membership" class="nav-link <?php echo isset($activePage) && $activePage == 'membership' ? 'active' : ''; ?>">Packages</a></li>
                <li><a href="/contact" class="nav-link <?php echo isset($activePage) && $activePage == 'contact' ? 'active' : ''; ?>">Contact</a></li>
                <li><a href="/login" class="btn btn-primary btn-sm">Member Login</a></li>
            </ul>
        </div>
    </div>
</nav>

<style>
    /* Mobile Navigation Styles */
    @media (max-width: 768px) {
        [data-nav-toggle] {
            display: block !important;
        }
        
        .navbar-nav {
            position: fixed;
            top: 0;
            right: -100%;
            width: 280px;
            height: 100vh;
            background: var(--white);
            box-shadow: var(--shadow-xl);
            padding: 2rem;
            flex-direction: column;
            align-items: flex-start;
            transition: right 0.3s ease;
            z-index: 9999;
        }
        
        .navbar-nav.active {
            right: 0;
        }
        
        .navbar-nav li {
            width: 100%;
            margin-bottom: 0.5rem;
        }
        
        .navbar-nav .nav-link,
        .navbar-nav .btn {
            width: 100%;
            text-align: left;
        }
    }
</style>
