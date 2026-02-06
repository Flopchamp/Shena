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
				<h1>Member Dashboard</h1>
				<div class="user-profile">
					<div class="user-profile-text">
						<h4><?php echo htmlspecialchars($member['first_name'] ?? 'John') . ' ' . htmlspecialchars($member['last_name'] ?? 'Doe'); ?></h4>
						<p>ID: <?php echo htmlspecialchars($member['member_number'] ?? 'SH-98238'); ?></p>
					</div>
					<div class="user-avatar">
						<?php echo strtoupper(substr($member['first_name'] ?? 'J', 0, 1)); ?>
					</div>
				</div>
			</div>
	<main>
