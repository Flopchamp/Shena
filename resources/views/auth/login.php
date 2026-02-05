<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Portal Access - SHENA Companion</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html {
            scroll-behavior: smooth;
            -webkit-text-size-adjust: 100%;
        }
        
        body {
            font-family: 'Manrope', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
            overflow-y: auto;
        }
        
        .login-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }
        
        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, rgba(127, 61, 158, 0.95) 0%, rgba(94, 43, 122, 0.95) 100%),
                        url('https://images.unsplash.com/photo-1511632765486-a01980e01a18?w=1200&q=80') center/cover;
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            min-height: 100vh;
        }
        
        .left-panel::before {
            content: '';
            position: absolute;
            top: 60px;
            left: 60px;
            width: 380px;
            height: 260px;
            display: grid;
            grid-template-columns: repeat(3, 120px);
            grid-template-rows: repeat(2, 120px);
            gap: 8px;
            opacity: 0.15;
            pointer-events: none;
        }
        
        .left-panel::before {
            background: 
                linear-gradient(white, white) 0 0 / 120px 120px,
                linear-gradient(white, white) 128px 0 / 120px 120px,
                linear-gradient(white, white) 256px 0 / 120px 120px,
                linear-gradient(white, white) 0 128px / 120px 120px,
                linear-gradient(white, white) 128px 128px / 120px 120px,
                linear-gradient(white, white) 256px 128px / 120px 120px;
            background-repeat: no-repeat;
            border-radius: 8px;
        }
        
        .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 60px;
            position: relative;
            z-index: 1;
        }
        
        .logo-icon {
            width: 45px;
            height: 45px;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logo-text {
            color: white;
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
        }
        
        .hero-content h1 {
            font-family: 'Playfair Display', serif;
            font-size: 4rem;
            font-weight: 700;
            color: white;
            line-height: 1.1;
            margin-bottom: 30px;
        }
        
        .hero-content p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
            line-height: 1.7;
            max-width: 450px;
            margin-bottom: 40px;
        }
        
        .mission-btn {
            background: white;
            color: #7F3D9E;
            padding: 14px 35px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }
        
        .mission-btn:hover {
            transform: translateY(-2px);
            color: #7F3D9E;
        }
        
        .footer-text {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.85rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            position: relative;
            z-index: 1;
        }
        
        .right-panel {
            flex: 1;
            background: #F7F7F9;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            min-height: 100vh;
            overflow-y: auto;
        }
        
        .login-card {
            background: white;
            border-radius: 24px;
            padding: 50px 60px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }
        
        .portal-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #9C27B0 0%, #7F3D9E 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
        }
        
        .portal-icon i {
            color: white;
            font-size: 2rem;
        }
        
        .login-card h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: #1A1A1A;
            text-align: center;
            margin-bottom: 12px;
        }
        
        .subtitle {
            text-align: center;
            color: #6B7280;
            font-size: 0.95rem;
            margin-bottom: 40px;
        }
        
        .tabs {
            display: flex;
            gap: 0;
            margin-bottom: 40px;
            border-bottom: 2px solid #E5E7EB;
        }
        
        .tab {
            flex: 1;
            padding: 12px 0;
            text-align: center;
            color: #6B7280;
            font-weight: 600;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            transition: all 0.3s ease;
        }
        
        .tab.active {
            color: #7F3D9E;
            border-bottom-color: #7F3D9E;
        }
        
        .tab:hover:not(.active) {
            color: #9C27B0;
            background: rgba(127, 61, 158, 0.05);
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-label {
            display: block;
            color: #1A1A1A;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }
        
        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: border-color 0.3s ease;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #7F3D9E;
        }
        
        .form-control.invalid {
            border-color: #DC2626;
        }
        
        .form-control.invalid:focus {
            border-color: #DC2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }
        
        .password-wrapper {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #6B7280;
            cursor: pointer;
        }
        
        .forgot-password {
            color: #7F3D9E;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            float: right;
            margin-bottom: 8px;
        }
        
        .login-btn {
            width: 100%;
            background: linear-gradient(135deg, #9C27B0 0%, #7F3D9E 100%);
            color: white;
            padding: 16px;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(127, 61, 158, 0.3);
            transition: all 0.3s ease;
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(127, 61, 158, 0.4);
        }
        
        .login-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        
        .register-section {
            text-align: center;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #E5E7EB;
        }
        
        .register-text {
            color: #6B7280;
            font-size: 0.9rem;
            margin-bottom: 16px;
        }
        
        .register-btn {
            width: 100%;
            background: transparent;
            color: #7F3D9E;
            padding: 14px;
            border: 2px solid #7F3D9E;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            text-decoration: none;
            display: block;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .register-btn:hover {
            background: #F3E8FF;
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 40px;
        }
        
        .footer-link {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #6B7280;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .footer-link:hover {
            color: #7F3D9E;
        }
        
        .copyright {
            text-align: center;
            color: #9CA3AF;
            font-size: 0.75rem;
            margin-top: 24px;
        }
        
        .copyright a {
            color: #7F3D9E;
            text-decoration: underline;
        }
        
        .alert {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
        }
        
        .alert-danger {
            background: #FEE2E2;
            border-left: 4px solid #DC2626;
            color: #991B1B;
        }
        
        .alert-success {
            background: #D1FAE5;
            border-left: 4px solid #059669;
            color: #065F46;
        }
        
        .alert-info {
            background: #DBEAFE;
            border-left: 4px solid #2563EB;
            color: #1E40AF;
        }
        
        .alert i {
            font-size: 1.1rem;
        }
        
        @media (max-width: 992px) {
            .login-container {
                flex-direction: column;
            }
            
            .left-panel {
                min-height: 50vh;
                padding: 40px 30px;
                background-attachment: scroll;
            }
            
            .left-panel::before {
                display: none;
            }
            
            .logo-section {
                margin-bottom: 30px;
            }
            
            .hero-content h1 {
                font-size: 2.5rem;
            }
            
            .hero-content p {
                font-size: 1rem;
                max-width: 100%;
            }
            
            .mission-btn {
                padding: 12px 28px;
                font-size: 0.9rem;
            }
            
            .right-panel {
                padding: 30px 20px;
                min-height: auto;
            }
            
            .login-card {
                padding: 40px 25px;
                max-width: 100%;
            }
            
            .portal-icon {
                width: 60px;
                height: 60px;
            }
            
            .portal-icon i {
                font-size: 1.5rem;
            }
            
            .login-card h2 {
                font-size: 1.75rem;
            }
            
            .footer-links {
                flex-direction: column;
                gap: 15px;
            }
        }
        
        @media (max-width: 480px) {
            .left-panel {
                min-height: 40vh;
                padding: 30px 20px;
            }
            
            .hero-content h1 {
                font-size: 2rem;
                line-height: 1.2;
            }
            
            .hero-content p {
                font-size: 0.9rem;
                margin-bottom: 25px;
            }
            
            .logo-icon {
                width: 40px;
                height: 40px;
            }
            
            .logo-text {
                font-size: 1.1rem;
            }
            
            .right-panel {
                padding: 20px 15px;
            }
            
            .login-card {
                padding: 30px 20px;
            }
            
            .portal-icon {
                width: 55px;
                height: 55px;
            }
            
            .login-card h2 {
                font-size: 1.5rem;
            }
            
            .subtitle {
                font-size: 0.85rem;
                margin-bottom: 30px;
            }
            
            .tabs {
                margin-bottom: 30px;
            }
            
            .tab {
                font-size: 0.9rem;
                padding: 10px 0;
            }
            
            .form-control {
                padding: 12px 14px;
                font-size: 0.9rem;
            }
            
            .login-btn {
                padding: 14px;
                font-size: 0.95rem;
            }
            
            .register-section {
                margin-top: 25px;
                padding-top: 25px;
            }
            
            .footer-text {
                font-size: 0.7rem;
            }
            
            .copyright {
                font-size: 0.7rem;
            }
        }
        
        /* Tablet specific styles */
        @media (min-width: 481px) and (max-width: 768px) {
            .left-panel {
                min-height: 45vh;
                padding: 50px 40px;
            }
            
            .hero-content h1 {
                font-size: 3rem;
            }
            
            .login-card {
                padding: 45px 35px;
                max-width: 480px;
            }
        }
        
        /* Landscape mode adjustments */
        @media (max-height: 600px) and (orientation: landscape) {
            .left-panel {
                display: none;
            }
            
            .right-panel {
                width: 100%;
                padding: 20px;
                min-height: auto;
            }
            
            .login-card {
                padding: 30px;
            }
            
            .portal-icon {
                width: 50px;
                height: 50px;
                margin-bottom: 20px;
            }
            
            .login-card h2 {
                font-size: 1.5rem;
                margin-bottom: 8px;
            }
            
            .subtitle {
                margin-bottom: 20px;
            }
            
            .tabs {
                margin-bottom: 20px;
            }
            
            .form-group {
                margin-bottom: 16px;
            }
            
            .register-section {
                margin-top: 20px;
                padding-top: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Panel -->
        <div class="left-panel">
            <div>
                <div class="logo-section">
                    <div class="logo-icon">
                        <img src="/public/images/shena-logo.png" alt="SHENA" style="width: 35px; height: 35px; object-fit: contain;">
                    </div>
                    <span class="logo-text">SHENA Companion</span>
                </div>
                
                <div class="hero-content">
                    <h1>We Are Royal</h1>
                    <p>Protecting your family's dignity with compassion. Join our mission-driven welfare association.</p>
                    <a href="/about" class="mission-btn">Our Mission</a>
                </div>
            </div>
            
            <div class="footer-text">
                ESTABLISHED FOR THE KISUMU COMMUNITY
            </div>
        </div>
        
        <!-- Right Panel -->
        <div class="right-panel">
            <div class="login-card">
                <div class="portal-icon">
                    <i class="fas fa-user-circle"></i>
                </div>
                
                <h2>Portal Access</h2>
                <p class="subtitle">Manage your association membership</p>
                
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i>
                    <span><strong>Admin/Staff:</strong> Please use the <a href="/admin/login" style="color: #1E40AF; text-decoration: underline; font-weight: 600;">Admin Login Portal</a></span>
                </div>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle"></i>
                        <span><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></span>
                    </div>
                <?php endif; ?>
                
                <div class="tabs">
                    <div class="tab active">Sign In</div>
                    <div class="tab" onclick="window.location.href='/register'">Register</div>
                </div>
                
                <form method="POST" action="/login">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                    
                    <div class="form-group">
                        <label class="form-label">Member ID / Email</label>
                        <input type="text" name="email" class="form-control" placeholder="Enter your ID or email" required>
                    </div>
                    
                    <div class="form-group">
                        <a href="/forgot-password" class="forgot-password">Forgot Password?</a>
                        <label class="form-label">Password</label>
                        <div class="password-wrapper">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                            <i class="fas fa-eye password-toggle" onclick="togglePassword()"></i>
                        </div>
                    </div>
                    
                    <button type="submit" class="login-btn" id="loginBtn">
                        <span id="btnText">Login to Dashboard</span>
                        <span id="btnLoader" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Signing in...
                        </span>
                    </button>
                </form>
                
                <div class="register-section">
                    <p class="register-text">New to SHENA Companion? Join our welfare association today.</p>
                    <a href="/register" class="register-btn">Start Registration</a>
                </div>
                
                <div class="footer-links">
                    <a href="/admin/login" class="footer-link">
                        <i class="fas fa-user-shield"></i>
                        Agent Login
                    </a>
                    <a href="/admin/login" class="footer-link">
                        <i class="fas fa-briefcase"></i>
                        Staff Portal
                    </a>
                </div>
                
                <div class="copyright">
                    © <?php echo date('Y'); ?> SHENA Companion. All Rights Reserved. 
                    <a href="/privacy">Privacy Policy</a> | 
                    <a href="/terms">Terms of Service</a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        
        // Add loading state to login button
        document.querySelector('form').addEventListener('submit', function(e) {
            const loginBtn = document.getElementById('loginBtn');
            const btnText = document.getElementById('btnText');
            const btnLoader = document.getElementById('btnLoader');
            
            // Disable button and show loading state
            loginBtn.disabled = true;
            btnText.style.display = 'none';
            btnLoader.style.display = 'inline-flex';
        });
        
        // Auto-focus on email field
        document.querySelector('input[name="email"]').focus();
    </script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
