<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>SHENA Member Portal</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <aside class="dashboard-sidebar" id="memberSidebar" style="background: linear-gradient(135deg, #6A0DAD 0%, #8E2DE2 100%);">
        <div class="dashboard-sidebar-brand">
            <i class="bi bi-person-circle"></i> MEMBER PORTAL
        </div>
        
        <ul class="dashboard-menu">
            <li class="dashboard-menu-item">
                <a href="/member/dashboard" class="dashboard-menu-link <?php echo isset($page) && $page == 'dashboard' ? 'active' : ''; ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            
            <li class="dashboard-menu-item">
                <a href="/member/profile" class="dashboard-menu-link <?php echo isset($page) && $page == 'profile' ? 'active' : ''; ?>">
                    <i class="bi bi-person-fill"></i> My Profile
                </a>
            </li>
            
            <li class="dashboard-menu-item">
                <a href="/member/payments" class="dashboard-menu-link <?php echo isset($page) && $page == 'payments' ? 'active' : ''; ?>">
                    <i class="bi bi-cash-stack"></i> Payments
                </a>
            </li>
            
            <li class="dashboard-menu-item">
                <a href="/member/beneficiaries" class="dashboard-menu-link <?php echo isset($page) && $page == 'beneficiaries' ? 'active' : ''; ?>">
                    <i class="bi bi-people-fill"></i> Beneficiaries
                </a>
            </li>
            
            <li class="dashboard-menu-item">
                <a href="/member/claims" class="dashboard-menu-link <?php echo isset($page) && $page == 'claims' ? 'active' : ''; ?>">
                    <i class="bi bi-file-earmark-text-fill"></i> Claims
                </a>
            </li>
            
            <li class="dashboard-menu-item">
                <a href="/member/documents" class="dashboard-menu-link <?php echo isset($page) && $page == 'documents' ? 'active' : ''; ?>">
                    <i class="bi bi-folder-fill"></i> Documents
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
                <h1 class="dashboard-title"><?php echo isset($pageTitle) ? $pageTitle : 'Member Portal'; ?></h1>
                <?php if (isset($pageSubtitle)): ?>
                    <p class="text-muted" style="margin: 0;"><?php echo $pageSubtitle; ?></p>
                <?php endif; ?>
            </div>
            <div class="d-flex align-items-center gap-3">
                <!-- Member Profile -->
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 40px; height: 40px; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                        <?php 
                            if (isset($member->first_name)) {
                                echo strtoupper(substr($member->first_name, 0, 1));
                            } else {
                                echo 'M';
                            }
                        ?>
                    </div>
                    <div style="display: flex; flex-direction: column;">
                        <span style="font-weight: 600; font-size: 0.875rem;">
                            <?php 
                                if (isset($member->first_name)) {
                                    echo htmlspecialchars($member->first_name . ' ' . $member->last_name);
                                } else {
                                    echo 'Member';
                                }
                            ?>
                        </span>
                        <span style="font-size: 0.75rem; color: var(--medium-grey);">
                            ID: <?php echo isset($member->member_id) ? $member->member_id : 'N/A'; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Page Content -->
        <div class="dashboard-content">
