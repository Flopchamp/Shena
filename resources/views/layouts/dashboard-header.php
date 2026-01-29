<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>SHENA Admin</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <?php if (isset($additionalCSS)): ?>
        <?php foreach ($additionalCSS as $css): ?>
            <link rel="stylesheet" href="<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <aside class="dashboard-sidebar" id="adminSidebar">
        <div class="dashboard-sidebar-brand">
            <i class="bi bi-gem"></i> SHENA ADMIN
        </div>
        
        <ul class="dashboard-menu">
            <li class="dashboard-menu-item">
                <a href="/admin/dashboard" class="dashboard-menu-link <?php echo isset($activePage) && $activePage == 'dashboard' ? 'active' : ''; ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            
            <li class="dashboard-menu-item">
                <a href="/admin/members" class="dashboard-menu-link <?php echo isset($activePage) && $activePage == 'members' ? 'active' : ''; ?>">
                    <i class="bi bi-people-fill"></i> Members
                </a>
            </li>
            
            <li class="dashboard-menu-item">
                <a href="/admin/agents" class="dashboard-menu-link <?php echo isset($activePage) && $activePage == 'agents' ? 'active' : ''; ?>">
                    <i class="bi bi-person-badge-fill"></i> Agents
                </a>
            </li>
            
            <li class="dashboard-menu-item">
                <a href="/admin/claims" class="dashboard-menu-link <?php echo isset($activePage) && $activePage == 'claims' ? 'active' : ''; ?>">
                    <i class="bi bi-file-earmark-text-fill"></i> Claims
                </a>
            </li>
            
            <li class="dashboard-menu-item">
                <a href="/admin/payments" class="dashboard-menu-link <?php echo isset($activePage) && $activePage == 'payments' ? 'active' : ''; ?>">
                    <i class="bi bi-cash-stack"></i> Payments
                </a>
            </li>
            
            <li class="dashboard-menu-item">
                <a href="/admin/reports" class="dashboard-menu-link <?php echo isset($activePage) && $activePage == 'reports' ? 'active' : ''; ?>">
                    <i class="bi bi-graph-up"></i> Reports
                </a>
            </li>
            
            <li class="dashboard-menu-item">
                <a href="/admin/communications" class="dashboard-menu-link <?php echo isset($activePage) && $activePage == 'communications' ? 'active' : ''; ?>">
                    <i class="bi bi-envelope-fill"></i> Communications
                </a>
            </li>
            
            <li class="dashboard-menu-item">
                <a href="/admin/settings" class="dashboard-menu-link <?php echo isset($activePage) && $activePage == 'settings' ? 'active' : ''; ?>">
                    <i class="bi bi-gear-fill"></i> Settings
                </a>
            </li>
            
            <li class="dashboard-menu-item" style="margin-top: auto; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.2);">
                <a href="/logout" class="dashboard-menu-link">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </li>
        </ul>
    </aside>
    
    <!-- Main Content -->
    <main class="dashboard-main">
        <!-- Mobile Sidebar Toggle -->
        <button data-sidebar-toggle class="btn btn-primary" style="position: fixed; top: 1rem; left: 1rem; z-index: 1001; display: none;">
            <i class="bi bi-list"></i>
        </button>
        
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div>
                <h1 class="dashboard-title"><?php echo isset($pageTitle) ? $pageTitle : 'Dashboard'; ?></h1>
                <?php if (isset($pageSubtitle)): ?>
                    <p class="text-muted" style="margin: 0;"><?php echo $pageSubtitle; ?></p>
                <?php endif; ?>
            </div>
            <div class="d-flex align-items-center gap-3">
                <!-- Notifications -->
                <div style="position: relative;">
                    <button class="btn btn-outline" data-tooltip="Notifications">
                        <i class="bi bi-bell-fill"></i>
                        <?php if (isset($notificationCount) && $notificationCount > 0): ?>
                            <span class="badge badge-danger" style="position: absolute; top: -5px; right: -5px; font-size: 0.65rem; padding: 0.25rem 0.5rem;">
                                <?php echo $notificationCount; ?>
                            </span>
                        <?php endif; ?>
                    </button>
                </div>
                
                <!-- Admin Profile -->
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 40px; height: 40px; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                        <?php echo isset($userName) ? strtoupper(substr($userName, 0, 1)) : 'A'; ?>
                    </div>
                    <div style="display: flex; flex-direction: column;">
                        <span style="font-weight: 600; font-size: 0.875rem;"><?php echo isset($userName) ? $userName : 'Admin'; ?></span>
                        <span style="font-size: 0.75rem; color: var(--medium-grey);"><?php echo isset($userRole) ? $userRole : 'Administrator'; ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Page Content -->
        <div class="dashboard-content">
