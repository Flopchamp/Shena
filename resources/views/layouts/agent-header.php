<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Agent Portal - Shena Companion'; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            background: #F8F9FA;
            font-family: 'Manrope', sans-serif;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(180deg, #4A1468 0%, #7F20B0 50%, #4A1468 100%);
            padding: 32px 0;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }

        .sidebar-logo {
            padding: 0 24px 32px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 24px;
        }

        .sidebar-logo-icon {
            width: 48px;
            height: 48px;
            background: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7F20B0;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .sidebar-logo-text h1 {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 700;
            color: white;
            margin: 0 0 2px 0;
        }

        .sidebar-logo-text p {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
            margin: 0;
            letter-spacing: 0.5px;
        }

        .sidebar-menu-label {
            padding: 0 24px;
            font-size: 11px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.5);
            letter-spacing: 1px;
            text-transform: uppercase;
            margin: 24px 0 12px 0;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-nav-item {
            margin: 0;
        }

        .sidebar-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 24px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .sidebar-nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-left-color: white;
        }

        .sidebar-nav-link i {
            font-size: 18px;
            width: 20px;
            text-align: center;
        }

        /* Agent Support Section */
        .agent-support {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.2);
            padding: 20px 24px;
        }

        .agent-support-label {
            font-size: 11px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.6);
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .agent-support-text {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 12px;
            line-height: 1.5;
        }

        .btn-contact-admin {
            background: white;
            color: #7F20B0;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            width: 100%;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-contact-admin:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.2);
        }

        /* Coverage Level Widget */
        .coverage-widget {
            padding: 0 24px;
            margin: 24px 0;
        }

        .coverage-label {
            font-size: 11px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.6);
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .coverage-amount {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 700;
            color: white;
            margin-bottom: 4px;
        }

        .coverage-subtitle {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Top Bar */
        .top-bar {
            position: fixed;
            left: 280px;
            top: 0;
            right: 0;
            height: 80px;
            background: white;
            border-bottom: 1px solid #E5E7EB;
            padding: 0 30px 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 999;
        }

        .search-box {
            flex: 0 0 400px;
        }

        .search-input {
            width: 100%;
            padding: 10px 16px 10px 40px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            font-size: 14px;
            color: #4B5563;
            transition: border-color 0.2s;
        }

        .search-input:focus {
            outline: none;
            border-color: #7F20B0;
        }

        .search-box {
            position: relative;
        }

        .search-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            font-size: 14px;
        }

        .top-bar-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: none;
            background: #F9FAFB;
            color: #6B7280;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .icon-btn:hover {
            background: #F3F4F6;
            color: #4B5563;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }

        .user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 16px;
        }

        .user-info h6 {
            font-size: 14px;
            font-weight: 600;
            color: #1F2937;
            margin: 0 0 2px 0;
        }

        .user-info p {
            font-size: 11px;
            color: #7F20B0;
            margin: 0;
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            margin-top: 80px;
            min-height: calc(100vh - 80px);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .top-bar {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }
        }

        @media (max-width: 768px) {
            .search-box {
                flex: 1;
                max-width: 200px;
            }

            .user-info {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <i class="fas fa-hands-helping"></i>
            </div>
            <div class="sidebar-logo-text">
                <h1>SHENA</h1>
                <p>COMPANION</p>
            </div>
        </div>

        <div class="sidebar-menu-label">MAIN MENU</div>
        <ul class="sidebar-nav">
            <li class="sidebar-nav-item">
                <a href="/agent/dashboard" class="sidebar-nav-link<?php echo (isset($page) && $page === 'dashboard') ? ' active' : ''; ?>">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="/agent/members" class="sidebar-nav-link<?php echo (isset($page) && $page === 'members') ? ' active' : ''; ?>">
                    <i class="fas fa-users"></i>
                    <span>Members</span>
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="/agent/claims" class="sidebar-nav-link<?php echo (isset($page) && $page === 'claims') ? ' active' : ''; ?>">
                    <i class="fas fa-file-invoice"></i>
                    <span>Claims</span>
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="/agent/payouts" class="sidebar-nav-link<?php echo (isset($page) && $page === 'payouts') ? ' active' : ''; ?>">
                    <i class="fas fa-money-check-alt"></i>
                    <span>Payouts</span>
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="/agent/resources" class="sidebar-nav-link<?php echo (isset($page) && $page === 'resources') ? ' active' : ''; ?>">
                    <i class="fas fa-book"></i>
                    <span>Resources</span>
                </a>
            </li>
        </ul>

        <div class="agent-support">
            <div class="agent-support-label">AGENT SUPPORT</div>
            <div class="agent-support-text">Need help with a claim or registration?</div>
            <button class="btn-contact-admin" onclick="location.href='/agent/support'">
                Contact Admin
            </button>
        </div>
    </div>

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" class="search-input" placeholder="Search by Member ID or Name...">
        </div>

        <div class="top-bar-actions">
            <button class="icon-btn">
                <i class="fas fa-bell"></i>
            </button>
            <button class="icon-btn" onclick="location.href='/agent/profile'" title="Profile Settings">
                <i class="fas fa-cog"></i>
            </button>
            <div class="user-profile" onclick="location.href='/agent/profile'" style="cursor: pointer;" title="View Profile">
                <div class="user-info">
                    <h6><?php echo htmlspecialchars($agent['first_name'] ?? 'Sarah'); ?> <?php echo htmlspecialchars($agent['last_name'] ?? 'Jenkins'); ?></h6>
                    <p><?php echo htmlspecialchars($agent['agent_number'] ?? 'GOLD TIER AGENT'); ?></p>
                </div>
                <div class="user-avatar">
                    <?php 
                        $firstName = $agent['first_name'] ?? 'S';
                        $lastName = $agent['last_name'] ?? 'J';
                        echo strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['flash_type'] ?? 'info'; ?> alert-dismissible fade show" style="margin: 20px 30px 0 25px;">
                <i class="fas fa-<?php echo ($_SESSION['flash_type'] ?? 'info') === 'success' ? 'check-circle' : 'info-circle'; ?>"></i>
                <?php echo htmlspecialchars($_SESSION['flash_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php 
                unset($_SESSION['flash_message']); 
                unset($_SESSION['flash_type']); 
            ?>
        <?php endif; ?>

