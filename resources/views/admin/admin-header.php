<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Admin Dashboard'; ?> - Shena Companion</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fc;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            box-shadow: 0 0 35px 0 rgba(154, 161, 171, 0.15);
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem 1.5rem;
            border-radius: 0.35rem;
            margin: 0.1rem 0.5rem;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link i {
            margin-right: 0.5rem;
        }
        
        .navbar {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .card {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: none;
        }
        
        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }
        
        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }
        
        .border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
        }
        
        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }
        
        .text-primary {
            color: #4e73df !important;
        }
        
        .text-success {
            color: #1cc88a !important;
        }
        
        .text-info {
            color: #36b9cc !important;
        }
        
        .text-warning {
            color: #f6c23e !important;
        }
        
        .text-gray-800 {
            color: #5a5c69 !important;
        }
        
        .text-gray-300 {
            color: #dddfeb !important;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h5 class="text-white">Shena Companion</h5>
                        <small class="text-white-50">Admin Portal</small>
                    </div>
                    
                    <ul class="nav flex-column">
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($_SERVER['REQUEST_URI'] == '/admin' || $_SERVER['REQUEST_URI'] == '/admin/dashboard') ? 'active' : ''; ?>" href="/admin">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>
                        
                        <!-- Member & Agent Management -->
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/members') !== false) ? 'active' : ''; ?>" href="/admin/members">
                                <i class="fas fa-users"></i>
                                Members
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/agents') !== false) ? 'active' : ''; ?>" href="/admin/agents">
                                <i class="fas fa-user-tie"></i>
                                Agents
                            </a>
                        </li>
                        
                        <!-- Financial Operations -->
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/financial') !== false || strpos($_SERVER['REQUEST_URI'], '/admin/payments') !== false) ? 'active' : ''; ?>" href="/admin/financial-dashboard">
                                <i class="fas fa-chart-line"></i>
                                Financial Operations
                            </a>
                        </li>
                        
                        <!-- Claims Management -->
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/claims') !== false) ? 'active' : ''; ?>" href="/admin/claims">
                                <i class="fas fa-file-medical"></i>
                                Claims
                            </a>
                        </li>
                        
                        <!-- Communications -->
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/communications') !== false || strpos($_SERVER['REQUEST_URI'], '/admin/email-campaigns') !== false || strpos($_SERVER['REQUEST_URI'], '/admin/bulk-sms') !== false) ? 'active' : ''; ?>" href="/admin/communications">
                                <i class="fas fa-comments"></i>
                                Communications
                            </a>
                        </li>
                        
                        <!-- Reports -->
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/reports') !== false) ? 'active' : ''; ?>" href="/admin/reports">
                                <i class="fas fa-chart-bar"></i>
                                Reports
                            </a>
                        </li>
                        
                        <!-- System Settings -->
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/settings') !== false || strpos($_SERVER['REQUEST_URI'], '/admin/mpesa-config') !== false || strpos($_SERVER['REQUEST_URI'], '/admin/notification-settings') !== false) ? 'active' : ''; ?>" href="/admin/settings">
                                <i class="fas fa-cog"></i>
                                Settings
                            </a>
                        </li>
                        
                        <li class="nav-item mt-3">
                            <hr class="text-white-50">
                            <a class="nav-link" href="/logout">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Top Navigation -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white mb-4">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-user-circle me-2"></i>
                                        <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="/admin/settings"><i class="fas fa-cog me-2"></i>Settings</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <!-- Flash Messages -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['warning'])): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo htmlspecialchars($_SESSION['warning']); unset($_SESSION['warning']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['info'])): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <?php echo htmlspecialchars($_SESSION['info']); unset($_SESSION['info']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
