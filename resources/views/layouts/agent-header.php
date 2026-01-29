<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Agent Portal - Shena Companion'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        body { 
            background: #f8f9fa; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .agent-navbar { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .agent-navbar .navbar-brand, 
        .agent-navbar .nav-link, 
        .agent-navbar .navbar-text { 
            color: #fff !important; 
        }
        .agent-navbar .nav-link.active { 
            font-weight: bold; 
            background: rgba(255,255,255,0.2);
            border-radius: 5px;
        }
        .agent-navbar .nav-link:hover {
            background: rgba(255,255,255,0.1);
            border-radius: 5px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .card-header {
            background: #fff;
            border-bottom: 2px solid #f0f0f0;
            font-weight: 600;
            padding: 15px 20px;
        }
        .stat-card {
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .badge {
            padding: 6px 12px;
            font-weight: 500;
        }
        .btn-agent-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }
        .btn-agent-primary:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6a3f8f 100%);
            color: white;
        }
        .table-hover tbody tr:hover {
            background-color: #f8f9ff;
        }
        .page-header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg agent-navbar mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="/agent/dashboard">
                <i class="fas fa-user-tie"></i> Agent Portal
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#agentNav">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="agentNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link<?php echo (isset($page) && $page === 'dashboard') ? ' active' : ''; ?>" 
                           href="/agent/dashboard">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?php echo (isset($page) && $page === 'members') ? ' active' : ''; ?>" 
                           href="/agent/members">
                            <i class="fas fa-users"></i> My Members
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?php echo (isset($page) && $page === 'commissions') ? ' active' : ''; ?>" 
                           href="/agent/commissions">
                            <i class="fas fa-money-bill-wave"></i> Commissions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?php echo (isset($page) && $page === 'register') ? ' active' : ''; ?>" 
                           href="/agent/register-member">
                            <i class="fas fa-user-plus"></i> Register Member
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?php echo (isset($page) && $page === 'profile') ? ' active' : ''; ?>" 
                           href="/agent/profile">
                            <i class="fas fa-user-edit"></i> My Profile
                        </a>
                    </li>
                </ul>
                <span class="navbar-text me-3">
                    <i class="fas fa-user-circle"></i>
                    <?php echo htmlspecialchars($agent['first_name'] ?? 'Agent'); ?> 
                    <?php echo htmlspecialchars($agent['last_name'] ?? ''); ?>
                    <small class="d-block" style="font-size: 0.75rem; opacity: 0.9;">
                        <?php echo htmlspecialchars($agent['agent_number'] ?? ''); ?>
                    </small>
                </span>
                <a href="/logout" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid px-4">
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['flash_type'] ?? 'info'; ?> alert-dismissible fade show">
                <i class="fas fa-<?php echo ($_SESSION['flash_type'] ?? 'info') === 'success' ? 'check-circle' : 'info-circle'; ?>"></i>
                <?php echo htmlspecialchars($_SESSION['flash_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php 
                unset($_SESSION['flash_message']); 
                unset($_SESSION['flash_type']); 
            ?>
        <?php endif; ?>

