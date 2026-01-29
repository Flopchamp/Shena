<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title ?? 'Member Portal - Shena Companion'; ?></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
	<style>
		body { background: #f8f9fa; }
		.member-navbar { background: #667eea; }
		.member-navbar .navbar-brand, .member-navbar .nav-link, .member-navbar .navbar-text { color: #fff !important; }
		.member-navbar .nav-link.active { font-weight: bold; text-decoration: underline; }
		.sidebar { background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
		.sidebar .nav-link { color: #667eea; }
		.sidebar .nav-link.active, .sidebar .nav-link:hover { background: #f1f5fb; color: #5a67d8; }
	</style>
</head>
<body>
	<nav class="navbar navbar-expand-lg member-navbar mb-4">
		<div class="container">
			<a class="navbar-brand" href="/dashboard"><i class="fas fa-user-shield"></i> Member Portal</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#memberNav">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="memberNav">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<li class="nav-item"><a class="nav-link<?php echo ($page ?? '') === 'dashboard' ? ' active' : ''; ?>" href="/dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
					<li class="nav-item"><a class="nav-link<?php echo ($page ?? '') === 'payments' ? ' active' : ''; ?>" href="/payments"><i class="fas fa-money-bill-wave"></i> Payments</a></li>
					<li class="nav-item"><a class="nav-link<?php echo ($page ?? '') === 'beneficiaries' ? ' active' : ''; ?>" href="/beneficiaries"><i class="fas fa-users"></i> Beneficiaries</a></li>
					<li class="nav-item"><a class="nav-link<?php echo ($page ?? '') === 'claims' ? ' active' : ''; ?>" href="/claims"><i class="fas fa-file-medical"></i> Claims</a></li>
					<li class="nav-item"><a class="nav-link<?php echo ($page ?? '') === 'profile' ? ' active' : ''; ?>" href="/profile"><i class="fas fa-user-edit"></i> Profile</a></li>
				</ul>
				<span class="navbar-text me-3">Welcome, <?php echo htmlspecialchars($member['first_name'] ?? 'Member'); ?></span>
				<a href="/logout" class="btn btn-outline-light btn-sm"><i class="fas fa-sign-out-alt"></i> Logout</a>
			</div>
		</div>
	</nav>
	<main>
