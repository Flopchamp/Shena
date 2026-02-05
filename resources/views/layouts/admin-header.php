<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Admin Dashboard'; ?> - Shena Companion</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background: #F8F9FA;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: white;
            border-right: 1px solid #E5E7EB;
            z-index: 1000;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: width 0.3s ease;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar.collapsed .sidebar-logo-text,
        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .nav-section-title,
        .sidebar.collapsed .admin-info,
        .sidebar.collapsed .admin-menu-btn {
            opacity: 0;
            visibility: hidden;
            width: 0;
            overflow: hidden;
        }

        .sidebar.collapsed .admin-profile {
            padding: 12px;
            justify-content: center;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
        }
        
        .sidebar.collapsed .nav-submenu {
            display: none !important;
        }



        .sidebar-logo {
            padding: 20px 20px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #F3F4F6;
            flex-shrink: 0;
        }

        .sidebar-logo img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .sidebar-logo-text {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            font-weight: 600;
            color: #1F2937;
        }

        .sidebar-logo-text span {
            font-weight: 400;
            color: #7F3D9E;
        }

        .sidebar-nav {
            padding: 20px 10px 100px;
            overflow-y: auto;
            flex: 1;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: #E5E7EB;
            border-radius: 4px;
        }

        .nav-item {
            margin-bottom: 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: #6B7280;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            position: relative;
        }

        .nav-link:hover {
            background: #F9FAFB;
            color: #7F3D9E;
        }

        .nav-link.active {
            background: linear-gradient(135deg, #F3E8FF 0%, #EDE9FE 100%);
            color: #7F3D9E;
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 24px;
            background: #7F3D9E;
            border-radius: 0 4px 4px 0;
        }

        .nav-link i {
            font-size: 16px;
            width: 20px;
        }

        .nav-section-title {
            padding: 20px 16px 8px;
            font-size: 11px;
            font-weight: 700;
            color: #9CA3AF;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Submenu Styles */
        .nav-submenu {
            display: none;
            padding-left: 32px;
            margin-top: 4px;
        }

        .nav-submenu.show {
            display: block;
        }

        .nav-submenu .nav-link {
            padding: 8px 16px;
            font-size: 13px;
        }

        .nav-link.has-submenu {
            position: relative;
        }

        .nav-link.has-submenu::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 16px;
            transition: transform 0.2s;
        }

        .nav-link.has-submenu.open::after {
            transform: rotate(180deg);
        }

        /* Top Header */
        .top-header {
            position: fixed;
            top: 0;
            left: 260px;
            right: 0;
            height: 70px;
            background: white;
            border-bottom: 1px solid #E5E7EB;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            z-index: 999;
            transition: left 0.3s ease;
        }

        .sidebar.collapsed ~ .top-header {
            left: 80px;
        }

        .toggle-sidebar-btn {
            width: 40px;
            height: 40px;
            background: #F9FAFB;
            border: none;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            margin-right: 16px;
        }

        .toggle-sidebar-btn:hover {
            background: #F3F4F6;
            color: #7F3D9E;
        }

        .toggle-sidebar-btn i {
            color: #6B7280;
            font-size: 18px;
        }

        .search-bar {
            position: relative;
            width: 100%;
            max-width: 400px;
        }

        .search-bar i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            font-size: 14px;
            z-index: 2;
        }

        .search-bar input {
            width: 100%;
            padding: 10px 16px 10px 44px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            font-size: 14px;
            color: #1F2937;
            transition: all 0.2s;
        }

        .search-bar input:focus {
            outline: none;
            border-color: #7F3D9E;
            box-shadow: 0 0 0 3px rgba(127, 61, 158, 0.1);
        }

        .search-bar input::placeholder {
            color: #D1D5DB;
        }

        .search-results {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            max-height: 400px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
        }

        .search-results.show {
            display: block;
            animation: slideDown 0.2s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .search-result-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-bottom: 1px solid #F3F4F6;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            color: #1F2937;
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .search-result-item:hover {
            background: #F9FAFB;
        }

        .search-result-icon {
            width: 36px;
            height: 36px;
            background: #F3F4F6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7F3D9E;
            font-size: 14px;
        }

        .search-result-info {
            flex: 1;
        }

        .search-result-title {
            font-size: 14px;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 2px;
        }

        .search-result-category {
            font-size: 12px;
            color: #9CA3AF;
        }

        .search-result-empty {
            padding: 32px 16px;
            text-align: center;
            color: #9CA3AF;
            font-size: 14px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .notification-btn {
            position: relative;
            width: 40px;
            height: 40px;
            background: #F9FAFB;
            border: none;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .notification-btn:hover {
            background: #F3F4F6;
        }

        .notification-btn i {
            color: #6B7280;
            font-size: 18px;
        }

        .notification-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 8px;
            height: 8px;
            background: #EF4444;
            border-radius: 50%;
            border: 2px solid white;
        }

        /* Header Admin Profile Dropdown */
        .header-admin-profile {
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 12px;
            background: #F9FAFB;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .header-admin-profile:hover {
            background: #F3F4F6;
        }

        .header-admin-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #7F3D9E 0%, #9333EA 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 14px;
        }

        .header-admin-info {
            display: flex;
            flex-direction: column;
        }

        .header-admin-name {
            font-size: 13px;
            font-weight: 600;
            color: #1F2937;
            line-height: 1.2;
        }

        .header-admin-role {
            font-size: 11px;
            color: #9CA3AF;
        }

        .header-admin-arrow {
            color: #9CA3AF;
            font-size: 12px;
            transition: transform 0.2s;
        }

        .header-admin-profile.active .header-admin-arrow {
            transform: rotate(180deg);
        }

        .header-admin-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            min-width: 240px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 8px 0;
            display: none;
            z-index: 1000;
        }

        .header-admin-dropdown.show {
            display: block;
            animation: slideDown 0.2s ease-out;
        }

        .header-dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            color: #1F2937;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .header-dropdown-item:hover {
            background: #F9FAFB;
        }

        .header-dropdown-item i {
            width: 18px;
            font-size: 14px;
            text-align: center;
            color: #6B7280;
        }

        .header-dropdown-divider {
            height: 1px;
            background: #E5E7EB;
            margin: 4px 0;
        }

        .header-dropdown-item.logout {
            color: #DC2626;
        }

        .header-dropdown-item.logout:hover {
            background: #FEE2E2;
        }

        .header-dropdown-item.logout i {
            color: #DC2626;
        }

        .btn-new-registration {
            padding: 10px 20px;
            background: linear-gradient(135deg, #7F3D9E 0%, #7F3D9E 100%);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-new-registration:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(127, 61, 158, 0.3);
        }

        /* Main Content Area */
        .main-content {
            margin-left: 260px;
            margin-top: 70px;
            padding: 30px;
            min-height: calc(100vh - 70px);
            transition: margin-left 0.3s ease;
        }

        .sidebar.collapsed ~ .top-header ~ .main-content,
        .sidebar.collapsed + .top-header ~ .main-content {
            margin-left: 80px;
        }

        /* Admin Profile (Bottom Left) */
        .admin-profile {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 16px;
            background: white;
            border-top: 1px solid #E5E7EB;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .admin-profile:hover {
            background: #F3F4F6;
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #F59E0B 0%, #F97316 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 16px;
        }

        .admin-info {
            flex: 1;
        }

        .admin-name {
            font-size: 13px;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 2px;
        }

        .admin-email {
            font-size: 11px;
            color: #9CA3AF;
        }

        .admin-menu-btn {
            color: #9CA3AF;
            font-size: 16px;
        }

        /* Admin Dropdown Menu */
        .admin-dropdown-menu {
            position: absolute;
            bottom: 100%;
            left: 16px;
            right: 16px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            margin-bottom: 8px;
            padding: 8px 0;
            display: none;
            z-index: 1000;
        }

        .admin-dropdown-menu.show {
            display: block;
            animation: slideUp 0.2s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            color: #1F2937;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background: #F9FAFB;
            color: #7F3D9E;
        }

        .dropdown-item i {
            width: 18px;
            font-size: 14px;
            text-align: center;
        }

        .dropdown-item.logout-item {
            border-top: 1px solid #E5E7EB;
            margin-top: 4px;
            padding-top: 12px;
            color: #DC2626;
        }

        .dropdown-item.logout-item:hover {
            background: #FEE2E2;
            color: #B91C1C;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .top-header {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .admin-profile {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-logo">
            <img src="/public/images/shena-logo.png" alt="SHENA Logo">
            <div class="sidebar-logo-text">
                SHENA <span>Companion</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link <?php echo ($_SERVER['REQUEST_URI'] == '/admin' || $_SERVER['REQUEST_URI'] == '/admin/dashboard') ? 'active' : ''; ?>" href="/admin/dashboard">
                        <i class="fas fa-th-large"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <!-- Members -->
                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/members') !== false) ? 'active' : ''; ?>" href="/admin/members">
                        <i class="fas fa-users"></i>
                        <span>Member Management</span>
                    </a>
                </li>

                <!-- Claims -->
                <li class="nav-item">
                    <a class="nav-link has-submenu <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/claims') !== false) ? 'active' : ''; ?>" href="#" onclick="toggleSubmenu(event, 'claims-submenu')">
                        <i class="fas fa-file-medical"></i>
                        <span>Claims Center</span>
                    </a>
                    <ul class="nav-submenu" id="claims-submenu">
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($_SERVER['REQUEST_URI'] == '/admin/claims') ? 'active' : ''; ?>" href="/admin/claims">
                                <i class="fas fa-list"></i>
                                <span>All Claims</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], 'claims/completed') !== false) ? 'active' : ''; ?>" href="/admin/claims/completed">
                                <i class="fas fa-check-circle"></i>
                                <span>Completed Claims</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], 'claims/track-services') !== false) ? 'active' : ''; ?>" href="/admin/claims/track-services">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Track Services</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Financial Management -->
                <div class="nav-section-title">FINANCIAL</div>

                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/financial-dashboard') !== false) ? 'active' : ''; ?>" href="/admin/financial-dashboard">
                        <i class="fas fa-chart-line"></i>
                        <span>Financial Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/payments') !== false && strpos($_SERVER['REQUEST_URI'], 'reconciliation') === false) ? 'active' : ''; ?>" href="/admin/payments">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Payments</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/payments-reconciliation') !== false) ? 'active' : ''; ?>" href="/admin/payments-reconciliation">
                        <i class="fas fa-sync-alt"></i>
                        <span>Reconciliation</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/reports') !== false) ? 'active' : ''; ?>" href="/admin/reports">
                        <i class="fas fa-file-alt"></i>
                        <span>Reports</span>
                    </a>
                </li>

                <!-- Agents -->
                <div class="nav-section-title">AGENTS</div>

                <li class="nav-item">
                    <a class="nav-link has-submenu <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/agents') !== false || strpos($_SERVER['REQUEST_URI'], '/admin/agent-') !== false) ? 'active' : ''; ?>" href="#" onclick="toggleSubmenu(event, 'agents-submenu')">
                        <i class="fas fa-user-tie"></i>
                        <span>Agent Management</span>
                    </a>
                    <ul class="nav-submenu" id="agents-submenu">
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($_SERVER['REQUEST_URI'] == '/admin/agents') ? 'active' : ''; ?>" href="/admin/agents">
                                <i class="fas fa-list"></i>
                                <span>All Agents</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], 'agents/create') !== false) ? 'active' : ''; ?>" href="/admin/agents/create">
                                <i class="fas fa-plus"></i>
                                <span>Add New Agent</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Communications -->
                <div class="nav-section-title">COMMUNICATIONS</div>

                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/communications') !== false) ? 'active' : ''; ?>" href="/admin/communications">
                        <i class="fas fa-comments"></i>
                        <span>Communications Hub</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/email-campaigns') !== false) ? 'active' : ''; ?>" href="/admin/email-campaigns">
                        <i class="fas fa-envelope"></i>
                        <span>Email Campaigns</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/sms-campaigns') !== false) ? 'active' : ''; ?>" href="/admin/sms-campaigns">
                        <i class="fas fa-sms"></i>
                        <span>SMS Campaigns</span>
                    </a>
                </li>

                <!-- System Configuration -->
                <div class="nav-section-title">CONFIGURATION</div>

                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/settings') !== false) ? 'active' : ''; ?>" href="/admin/settings">
                        <i class="fas fa-cog"></i>
                        <span>System Settings</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/mpesa-config') !== false) ? 'active' : ''; ?>" href="/admin/mpesa-config">
                        <i class="fas fa-mobile-alt"></i>
                        <span>M-Pesa Configuration</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/plan-upgrades') !== false) ? 'active' : ''; ?>" href="/admin/plan-upgrades">
                        <i class="fas fa-arrow-up"></i>
                        <span>Plan Upgrades</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Admin Profile -->
        <div class="admin-profile" onclick="toggleAdminMenu(event)">
            <div class="admin-avatar">AD</div>
            <div class="admin-info">
                <div class="admin-name">Admin Director</div>
                <div class="admin-email">admin@shena.com</div>
            </div>
            <i class="fas fa-ellipsis-v admin-menu-btn"></i>
            
            <!-- Dropdown Menu -->
            <div class="admin-dropdown-menu" id="adminDropdownMenu">
                <a href="/admin/settings" class="dropdown-item">
                    <i class="fas fa-user-cog"></i>
                    <span>Profile Settings</span>
                </a>
                <a href="/logout" class="dropdown-item logout-item">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Top Header -->
    <div class="top-header">
        <div style="display: flex; align-items: center; flex: 1;">
            <button class="toggle-sidebar-btn" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" id="globalSearch" placeholder="Search functions, members, claims..." autocomplete="off" oninput="performSearch(this.value)">
                
                <!-- Search Results Dropdown -->
                <div class="search-results" id="searchResults"></div>
            </div>
        </div>

        <div class="header-actions">
            <a href="/admin/communications" class="notification-btn" title="View Notifications">
                <i class="fas fa-bell"></i>
                <span class="notification-badge"></span>
            </a>

            <!-- Header Admin Profile -->
            <div class="header-admin-profile" id="headerAdminProfile" onclick="toggleHeaderAdminMenu(event)">
                <div class="header-admin-avatar">
                    <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'Admin', 0, 2)); ?>
                </div>
                <div class="header-admin-info">
                    <div class="header-admin-name"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin Director'); ?></div>
                    <div class="header-admin-role"><?php echo ucwords(str_replace('_', ' ', $_SESSION['user_role'] ?? 'Super Admin')); ?></div>
                </div>
                <i class="fas fa-chevron-down header-admin-arrow"></i>
                
                <!-- Header Admin Dropdown -->
                <div class="header-admin-dropdown" id="headerAdminDropdown">
                    <a href="/profile" class="header-dropdown-item">
                        <i class="fas fa-user"></i>
                        <span>My Profile</span>
                    </a>
                    <a href="/admin/settings" class="header-dropdown-item">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                    <div class="header-dropdown-divider"></div>
                    <a href="/logout" class="header-dropdown-item logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">

    <script>
        // Toggle sidebar collapse
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        }

        // Restore sidebar state on page load
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (sidebarCollapsed) {
                document.querySelector('.sidebar').classList.add('collapsed');
            }
        });

        // Toggle admin dropdown menu (sidebar)
        function toggleAdminMenu(event) {
            event.stopPropagation();
            const menu = document.getElementById('adminDropdownMenu');
            menu.classList.toggle('show');
        }

        // Toggle header admin dropdown menu
        function toggleHeaderAdminMenu(event) {
            event.stopPropagation();
            const dropdown = document.getElementById('headerAdminDropdown');
            const profile = document.getElementById('headerAdminProfile');
            dropdown.classList.toggle('show');
            profile.classList.toggle('active');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            // Close sidebar admin menu
            const menu = document.getElementById('adminDropdownMenu');
            const profile = document.querySelector('.admin-profile');
            if (menu && profile && !profile.contains(event.target)) {
                menu.classList.remove('show');
            }

            // Close header admin dropdown
            const headerDropdown = document.getElementById('headerAdminDropdown');
            const headerProfile = document.getElementById('headerAdminProfile');
            if (headerDropdown && headerProfile && !headerProfile.contains(event.target)) {
                headerDropdown.classList.remove('show');
                headerProfile.classList.remove('active');
            }

            // Close search results
            const searchResults = document.getElementById('searchResults');
            const searchBar = document.querySelector('.search-bar');
            if (searchResults && searchBar && !searchBar.contains(event.target)) {
                searchResults.classList.remove('show');
            }
        });

        // Toggle submenu function
        function toggleSubmenu(event, submenuId) {
            event.preventDefault();
            const submenu = document.getElementById(submenuId);
            const parentLink = event.currentTarget;
            
            // Toggle the submenu visibility
            submenu.classList.toggle('show');
            parentLink.classList.toggle('open');
            
            // Close other submenus
            document.querySelectorAll('.nav-submenu').forEach(menu => {
                if (menu.id !== submenuId) {
                    menu.classList.remove('show');
                }
            });
            
            document.querySelectorAll('.nav-link.has-submenu').forEach(link => {
                if (link !== parentLink) {
                    link.classList.remove('open');
                }
            });
        }
        
        // Auto-expand active submenu on page load
        document.addEventListener('DOMContentLoaded', function() {
            const activeLinks = document.querySelectorAll('.nav-submenu .nav-link.active');
            activeLinks.forEach(link => {
                const submenu = link.closest('.nav-submenu');
                if (submenu) {
                    submenu.classList.add('show');
                    const parentLink = submenu.previousElementSibling;
                    if (parentLink) {
                        parentLink.classList.add('open');
                    }
                }
            });
        });

        // Search functionality - System functions database
        const systemFunctions = [
            { title: 'Dashboard', category: 'Navigation', icon: 'fa-th-large', url: '/admin/dashboard' },
            { title: 'Member Management', category: 'Navigation', icon: 'fa-users', url: '/admin/members' },
            { title: 'View Members', category: 'Members', icon: 'fa-list', url: '/admin/members' },
            { title: 'Register New Member', category: 'Members', icon: 'fa-user-plus', url: '/admin/members/register' },
            { title: 'Agent Management', category: 'Navigation', icon: 'fa-user-tie', url: '/admin/agents' },
            { title: 'View Agents', category: 'Agents', icon: 'fa-list', url: '/admin/agents' },
            { title: 'Create Agent', category: 'Agents', icon: 'fa-plus', url: '/admin/agents/create' },
            { title: 'Claims Management', category: 'Navigation', icon: 'fa-file-medical', url: '/admin/claims' },
            { title: 'View Claims', category: 'Claims', icon: 'fa-list', url: '/admin/claims' },
            { title: 'Pending Claims', category: 'Claims', icon: 'fa-clock', url: '/admin/claims?status=pending' },
            { title: 'Approved Claims', category: 'Claims', icon: 'fa-check', url: '/admin/claims?status=approved' },
            { title: 'Completed Claims', category: 'Claims', icon: 'fa-check-double', url: '/admin/claims/completed' },
            { title: 'Track Service Delivery', category: 'Claims', icon: 'fa-truck', url: '/admin/claims/track-services' },
            { title: 'All Payments', category: 'Payments', icon: 'fa-money-bill-wave', url: '/admin/payments' },
            { title: 'Payment Reconciliation', category: 'Payments', icon: 'fa-balance-scale', url: '/admin/payments/reconciliation' },
            { title: 'Pending Payments', category: 'Payments', icon: 'fa-clock', url: '/admin/payments?status=pending' },
            { title: 'Failed Payments', category: 'Payments', icon: 'fa-times-circle', url: '/admin/payments?status=failed' },
            { title: 'Email Campaigns', category: 'Communications', icon: 'fa-envelope', url: '/admin/email-campaigns' },
            { title: 'SMS Campaigns', category: 'Communications', icon: 'fa-sms', url: '/admin/sms-campaigns' },
            { title: 'Notifications', category: 'Communications', icon: 'fa-bell', url: '/admin/communications' },
            { title: 'Send Bulk Email', category: 'Communications', icon: 'fa-mail-bulk', url: '/admin/email-campaigns' },
            { title: 'Send Bulk SMS', category: 'Communications', icon: 'fa-comment-dots', url: '/admin/sms-campaigns' },
            { title: 'Reports & Analytics', category: 'Reports', icon: 'fa-chart-line', url: '/admin/reports' },
            { title: 'Financial Dashboard', category: 'Reports', icon: 'fa-chart-pie', url: '/admin/financial-dashboard' },
            { title: 'Member Reports', category: 'Reports', icon: 'fa-users', url: '/admin/reports?type=members' },
            { title: 'Payment Reports', category: 'Reports', icon: 'fa-money-check', url: '/admin/reports?type=payments' },
            { title: 'Claims Reports', category: 'Reports', icon: 'fa-file-contract', url: '/admin/reports?type=claims' },
            { title: 'System Settings', category: 'Settings', icon: 'fa-cog', url: '/admin/settings' },
            { title: 'M-Pesa Configuration', category: 'Settings', icon: 'fa-mobile-alt', url: '/admin/mpesa-config' },
            { title: 'Email Configuration', category: 'Settings', icon: 'fa-envelope-open-text', url: '/admin/settings?tab=email' },
            { title: 'SMS Configuration', category: 'Settings', icon: 'fa-comment-alt', url: '/admin/settings?tab=sms' },
            { title: 'Plan Upgrades', category: 'Management', icon: 'fa-arrow-up', url: '/admin/plan-upgrades' },
            { title: 'Commission Management', category: 'Agents', icon: 'fa-hand-holding-usd', url: '/admin/commissions' }
        ];

        let searchTimeout;
        function performSearch(query) {
            clearTimeout(searchTimeout);
            
            const resultsContainer = document.getElementById('searchResults');
            
            if (query.length < 2) {
                resultsContainer.classList.remove('show');
                return;
            }

            searchTimeout = setTimeout(() => {
                const lowerQuery = query.toLowerCase();
                const results = systemFunctions.filter(func => 
                    func.title.toLowerCase().includes(lowerQuery) ||
                    func.category.toLowerCase().includes(lowerQuery)
                );

                displaySearchResults(results);
            }, 300);
        }

        function displaySearchResults(results) {
            const resultsContainer = document.getElementById('searchResults');
            
            if (results.length === 0) {
                resultsContainer.innerHTML = '<div class="search-result-empty"><i class="fas fa-search"></i><br>No results found</div>';
                resultsContainer.classList.add('show');
                return;
            }

            const html = results.slice(0, 8).map(result => `
                <a href="${result.url}" class="search-result-item">
                    <div class="search-result-icon">
                        <i class="fas ${result.icon}"></i>
                    </div>
                    <div class="search-result-info">
                        <div class="search-result-title">${result.title}</div>
                        <div class="search-result-category">${result.category}</div>
                    </div>
                </a>
            `).join('');

            resultsContainer.innerHTML = html;
            resultsContainer.classList.add('show');
        }

        // Clear search on navigation
        document.addEventListener('click', function(event) {
            if (event.target.closest('.search-result-item')) {
                document.getElementById('globalSearch').value = '';
                document.getElementById('searchResults').classList.remove('show');
            }
        });
    </script>
