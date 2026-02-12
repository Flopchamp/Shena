<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title ?? 'Member Portal - Shena Companion'; ?></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
	<link href="/public/css/modals.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}
		
		body { 
			background: #F8F9FC; 
			font-family: 'Manrope', sans-serif;
		}
		
		.dashboard-wrapper {
			display: flex;
			min-height: 100vh;
		}
		
		.sidebar {
			width: 280px;
			background: #7F3D9E;
			color: white;
			position: fixed;
			height: 100vh;
			overflow-y: auto;
			z-index: 1000;
		}
		
		.sidebar-logo {
			padding: 30px 25px;
			border-bottom: 1px solid rgba(255, 255, 255, 0.1);
		}
		
		.sidebar-logo h2 {
			font-size: 1.1rem;
			font-weight: 700;
			letter-spacing: 0.5px;
			margin: 0;
			display: flex;
			align-items: center;
			gap: 10px;
		}
		
		.sidebar-nav {
			padding: 20px 0;
		}
		
		.sidebar-nav a {
			display: flex;
			align-items: center;
			gap: 12px;
			padding: 14px 25px;
			color: rgba(255, 255, 255, 0.8);
			text-decoration: none;
			transition: all 0.3s ease;
			font-size: 0.95rem;
			font-weight: 500;
		}
		
		.sidebar-nav a i {
			width: 20px;
			font-size: 1.1rem;
		}
		
		.sidebar-nav a:hover {
			background: rgba(255, 255, 255, 0.1);
			color: white;
		}
		
		.sidebar-nav a.active {
			background: rgba(255, 255, 255, 0.15);
			color: white;
			border-left: 4px solid #FFD700;
			padding-left: 21px;
		}
		
		.sidebar-coverage {
			position: absolute;
			bottom: 20px;
			left: 20px;
			right: 20px;
			background: rgba(0, 0, 0, 0.2);
			padding: 20px;
			border-radius: 12px;
		}
		
		.sidebar-coverage h5 {
			font-size: 0.75rem;
			letter-spacing: 1px;
			color: rgba(255, 255, 255, 0.6);
			margin-bottom: 8px;
		}
		
		.sidebar-coverage h3 {
			font-size: 1.8rem;
			font-weight: 700;
			margin-bottom: 5px;
		}
		
		.sidebar-coverage p {
			font-size: 0.75rem;
			color: rgba(255, 255, 255, 0.7);
			margin: 0;
		}
		
		.main-content {
			margin-left: 280px;
			flex: 1;
			padding: 0;
		}
		
		.top-bar {
			background: white;
			padding: 20px 40px 20px 25px;
			border-bottom: 1px solid #E5E7EB;
			display: flex;
			justify-content: space-between;
			align-items: center;
		}
		
		.top-bar h1 {
			font-size: 1.5rem;
			font-weight: 600;
			color: #1F2937;
			margin: 0;
		}

		.top-bar-right {
			display: flex;
			align-items: center;
			gap: 20px;
		}

		.search-container {
			position: relative;
		}

		.search-input-wrapper {
			position: relative;
			width: 300px;
		}

		.search-input {
			width: 100%;
			padding: 10px 16px 10px 40px;
			border: 1px solid #E5E7EB;
			border-radius: 8px;
			font-size: 14px;
			transition: all 0.2s;
			background: #F9FAFB;
		}

		.search-input:focus {
			outline: none;
			border-color: #7F3D9E;
			background: white;
			box-shadow: 0 0 0 3px rgba(127, 61, 158, 0.1);
		}

		.search-icon {
			position: absolute;
			left: 12px;
			top: 50%;
			transform: translateY(-50%);
			color: #9CA3AF;
			font-size: 14px;
		}

		.search-results {
			position: absolute;
			top: calc(100% + 8px);
			left: 0;
			right: 0;
			background: white;
			border: 1px solid #E5E7EB;
			border-radius: 12px;
			box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
			max-height: 400px;
			overflow-y: auto;
			z-index: 1002;
		}

		.search-result-item {
			padding: 12px 16px;
			border-bottom: 1px solid #F3F4F6;
			cursor: pointer;
			transition: background 0.2s;
			display: flex;
			align-items: center;
			gap: 12px;
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
			background: linear-gradient(135deg, #7F3D9E 0%, #A855F7 100%);
			border-radius: 8px;
			display: flex;
			align-items: center;
			justify-content: center;
			color: white;
			font-size: 14px;
		}

		.search-result-content h6 {
			font-size: 14px;
			font-weight: 600;
			color: #1F2937;
			margin: 0 0 2px 0;
		}

		.search-result-content p {
			font-size: 12px;
			color: #6B7280;
			margin: 0;
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
			color: #7F20B0;
		}

		.logout-btn {
			background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
			color: white;
			border: none;
			padding: 10px 20px;
			border-radius: 8px;
			font-weight: 600;
			font-size: 14px;
			display: flex;
			align-items: center;
			gap: 8px;
			cursor: pointer;
			transition: all 0.2s;
		}

		.logout-btn:hover {
			transform: translateY(-1px);
			box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
		}

		.profile-menu {
			position: relative;
			display: flex;
			align-items: center;
		}

		.profile-button {
			background: transparent;
			border: none;
			padding: 0;
			display: flex;
			align-items: center;
			gap: 10px;
			cursor: pointer;
		}

		.profile-button:focus {
			outline: none;
		}

		.profile-caret {
			font-size: 12px;
			color: #9CA3AF;
		}

		.profile-dropdown {
			position: absolute;
			top: calc(100% + 10px);
			right: 0;
			min-width: 200px;
			background: white;
			border: 1px solid #E5E7EB;
			border-radius: 12px;
			box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
			padding: 8px;
			opacity: 0;
			visibility: hidden;
			transform: translateY(-6px);
			transition: all 0.2s ease;
			z-index: 1003;
		}

		.profile-menu:focus-within .profile-dropdown,
		.profile-menu:hover .profile-dropdown {
			opacity: 1;
			visibility: visible;
			transform: translateY(0);
		}

		.profile-dropdown-item {
			display: flex;
			align-items: center;
			gap: 10px;
			padding: 10px 12px;
			border-radius: 8px;
			color: #374151;
			text-decoration: none;
			font-size: 14px;
			font-weight: 600;
			background: transparent;
			border: none;
			width: 100%;
			cursor: pointer;
			text-align: left;
		}

		.profile-dropdown-item:hover {
			background: #F9FAFB;
			color: #7F20B0;
		}

		.profile-dropdown-item.logout {
			color: #DC2626;
		}

		.profile-dropdown-item.logout:hover {
			background: #FEF2F2;
			color: #B91C1C;
		}
		
		.user-profile {
			display: flex;
			align-items: center;
			gap: 12px;
		}
		
		.user-profile-text {
			text-align: right;
		}
		
		.user-profile-text h4 {
			font-size: 0.95rem;
			font-weight: 600;
			color: #1F2937;
			margin: 0;
		}
		
		.user-profile-text p {
			font-size: 0.8rem;
			color: #6B7280;
			margin: 0;
		}
		
		.user-avatar {
			width: 45px;
			height: 45px;
			border-radius: 50%;
			background: linear-gradient(135deg, #7F3D9E, #A855F7);
			display: flex;
			align-items: center;
			justify-content: center;
			color: white;
			font-weight: 600;
			font-size: 1.1rem;
		}
		
		@media (max-width: 768px) {
			.sidebar {
				transform: translateX(-100%);
			}
			
			.main-content {
				margin-left: 0;
			}
		}
	</style>
</head>
<body>
	<div class="dashboard-wrapper">
		<!-- Sidebar -->
		<aside class="sidebar">
			<div class="sidebar-logo">
				<h2>
					<i class="fas fa-shield-alt"></i>
					SHENA COMPANION
				</h2>
			</div>
			<nav class="sidebar-nav">
				<a href="/dashboard" class="<?php echo ($page ?? '') === 'dashboard' ? 'active' : ''; ?>">
					<i class="fas fa-th-large"></i> Dashboard
				</a>
				<a href="/payments" class="<?php echo ($page ?? '') === 'payments' ? 'active' : ''; ?>">
					<i class="fas fa-credit-card"></i> Contributions
				</a>
				<a href="/beneficiaries" class="<?php echo ($page ?? '') === 'beneficiaries' ? 'active' : ''; ?>">
					<i class="fas fa-users"></i> Beneficiaries
				</a>
				<a href="/claims" class="<?php echo ($page ?? '') === 'claims' ? 'active' : ''; ?>">
					<i class="fas fa-file-medical"></i> Claims
				</a>
				<a href="/profile" class="<?php echo ($page ?? '') === 'settings' ? 'active' : ''; ?>">
					<i class="fas fa-cog"></i> Settings
				</a>
			</nav>
			<div class="sidebar-coverage">
				<h5>BENEFIT COVERAGE</h5>
				<h3>$15,000</h3>
				<p style="width: 100%; height: 3px; background: linear-gradient(90deg, #FFD700 0%, #FFA500 100%); margin: 8px 0;"></p>
			</div>
		</aside>
		
		<!-- Main Content -->
		<div class="main-content">
			<div class="top-bar">
				<div class="top-bar-left">
					<h1>Member Dashboard</h1>
				</div>
				<div class="top-bar-right">
					<div class="search-container">
						<div class="search-input-wrapper">
							<i class="fas fa-search search-icon"></i>
							<input type="text" class="search-input" id="globalSearch" placeholder="Search features...">
						</div>
						<div class="search-results" id="searchResults" style="display: none;"></div>
					</div>				<button class="icon-btn" onclick="location.href='/member/notifications'" title="Notifications">
					<i class="fas fa-bell"></i>
					</button>
					<div class="profile-menu">
						<button class="profile-button" type="button" aria-haspopup="true" aria-expanded="false">
							<div class="user-profile">
								<div class="user-profile-text">
									<h4><?php echo htmlspecialchars($member['first_name'] ?? 'John') . ' ' . htmlspecialchars($member['last_name'] ?? 'Doe'); ?></h4>
									<p>ID: <?php echo htmlspecialchars($member['member_number'] ?? 'SH-98238'); ?></p>
								</div>
								<div class="user-avatar">
									<?php echo strtoupper(substr($member['first_name'] ?? 'J', 0, 1)); ?>
								</div>
							</div>
							<i class="fas fa-chevron-down profile-caret"></i>
						</button>
						<div class="profile-dropdown" role="menu">
							<a class="profile-dropdown-item" href="/profile">
								<i class="fas fa-user-cog"></i>
								Profile Settings
							</a>
							<a class="profile-dropdown-item" href="/member/notification-settings">
								<i class="fas fa-bell"></i>
								Notifications
							</a>
							<button class="profile-dropdown-item logout" type="button" onclick="handleLogout()">
								<i class="fas fa-sign-out-alt"></i>
								Logout
							</button>
						</div>
					</div>
				</div>
			</div>
	<main>
