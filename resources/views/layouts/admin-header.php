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
            overflow-y: auto;
            padding: 20px 0;
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #E5E7EB;
            border-radius: 4px;
        }

        .sidebar-logo {
            padding: 0 20px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #F3F4F6;
            margin-bottom: 20px;
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
            padding: 0 10px;
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
        }

        /* Admin Profile (Bottom Left) */
        .admin-profile {
            position: fixed;
            bottom: 20px;
            left: 10px;
            width: 240px;
            padding: 12px;
            background: #F9FAFB;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.2s;
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
            <img src="public/images/shena-logo.png" alt="SHENA Logo">
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
        <div class="admin-profile">
            <div class="admin-avatar">AD</div>
            <div class="admin-info">
                <div class="admin-name">Admin Director</div>
                <div class="admin-email">admin@shena.com</div>
            </div>
            <i class="fas fa-ellipsis-v admin-menu-btn"></i>
        </div>
    </div>

    <!-- Top Header -->
    <div class="top-header">
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search members, claims, or files...">
        </div>

        <div class="header-actions">
            <button class="notification-btn">
                <i class="fas fa-bell"></i>
                <span class="notification-badge"></span>
            </button>

            <button class="btn-new-registration" onclick="window.location.href='/admin/members/register'">
                <i class="fas fa-plus"></i>
                New Registration
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">

    <script>
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
    </script>
