<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Administrator Sign-In'; ?> - Shena Companion</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background: #F3F4F6;
            min-height: 100vh;
            display: flex;
            overflow: hidden;
        }

        /* Left Panel */
        .left-panel {
            width: 50%;
            background: linear-gradient(180deg, #6B21A8 0%, #7C3AED 50%, #6B21A8 100%);
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 1;
        }

        .logo-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .logo-text {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: 600;
        }

        .logo-text span {
            font-weight: 300;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            margin-top: auto;
            margin-bottom: auto;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 72px;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 24px;
        }

        .hero-description {
            font-size: 16px;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.9);
            max-width: 400px;
        }

        .footer-text {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
            position: relative;
            z-index: 1;
        }

        /* Right Panel */
        .right-panel {
            width: 50%;
            background: #F3F4F6;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
        }

        .login-header {
            margin-bottom: 32px;
        }

        .login-title {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            font-weight: 700;
            color: #1F2937;
            margin-bottom: 8px;
        }

        .login-subtitle {
            font-size: 14px;
            color: #9CA3AF;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            font-size: 11px;
            font-weight: 700;
            color: #4B5563;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            display: block;
        }

        .password-label-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .forgot-link {
            font-size: 12px;
            color: #7C3AED;
            text-decoration: none;
            font-weight: 600;
        }

        .forgot-link:hover {
            color: #6B21A8;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #7C3AED;
            font-size: 16px;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            font-size: 14px;
            color: #1F2937;
            transition: all 0.2s;
            background: white;
        }

        .form-control::placeholder {
            color: #D1D5DB;
        }

        .form-control:focus {
            outline: none;
            border-color: #7C3AED;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9CA3AF;
            cursor: pointer;
            padding: 4px;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: #7C3AED;
        }

        .btn-signin {
            width: 100%;
            padding: 14px 24px;
            background: linear-gradient(135deg, #7C3AED 0%, #6B21A8 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-signin:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(124, 58, 237, 0.3);
        }

        .btn-signin:active {
            transform: translateY(0);
        }

        .emergency-lockout {
            margin-top: 32px;
            text-align: center;
        }

        .lockout-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #DC2626;
            text-decoration: none;
            font-weight: 600;
        }

        .lockout-link:hover {
            color: #B91C1C;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 24px;
            margin-top: 40px;
        }

        .footer-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #6B7280;
            text-decoration: none;
        }

        .footer-link:hover {
            color: #7C3AED;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: none;
            font-size: 14px;
        }

        .alert-danger {
            background: #FEE2E2;
            color: #DC2626;
        }

        /* Responsive */
        @media (max-width: 992px) {
            body {
                flex-direction: column;
            }

            .left-panel,
            .right-panel {
                width: 100%;
            }

            .left-panel {
                min-height: 40vh;
                padding: 40px;
            }

            .hero-title {
                font-size: 48px;
            }

            .right-panel {
                padding: 40px 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Left Panel -->
    <div class="left-panel">
        <div class="logo-section">
            <div class="logo-icon">
                <img src="public/images/shena-logo.png" alt="SHENA Logo">
            </div>
            <div class="logo-text">
                SHENA <span>Companion</span>
            </div>
        </div>

        <div class="hero-content">
            <h1 class="hero-title">We Are<br>Royal.</h1>
            <p class="hero-description">
                Securing the future of administrative management with military-grade encryption and intuitive design.
            </p>
        </div>

        <div class="footer-text">
            © 2024 SHENA Systems  •  v6.0.2 Secure Build
        </div>
    </div>

    <!-- Right Panel -->
    <div class="right-panel">
        <div class="login-container">
            <div class="login-header">
                <h2 class="login-title">Administrator Sign-In</h2>
                <p class="login-subtitle">Enter your credentials to access the secure portal.</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/admin/login" id="loginForm">
                <div class="form-group">
                    <label class="form-label">Admin ID</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" 
                               class="form-control" 
                               id="username" 
                               name="username" 
                               placeholder="E.g. ADM-9942-X" 
                               required 
                               autocomplete="username">
                    </div>
                </div>

                <div class="form-group">
                    <div class="password-label-wrapper">
                        <label class="form-label">Password</label>
                        <a href="/admin/forgot-password" class="forgot-link">Forget?</a>
                    </div>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" 
                               class="form-control" 
                               id="password" 
                               name="password" 
                               placeholder="Enter secure password" 
                               required 
                               autocomplete="current-password">
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-signin">
                    Authenticate & Send 2FA Code
                    <i class="fas fa-shield-alt"></i>
                </button>
            </form>

            <div class="emergency-lockout">
                <a href="/admin/emergency-lockout" class="lockout-link">
                    <i class="fas fa-exclamation-triangle"></i>
                    Emergency Lockout
                </a>
            </div>

            <div class="footer-links">
                <a href="/support" class="footer-link">
                    <i class="fas fa-headset"></i>
                    Support
                </a>
                <a href="/system-status" class="footer-link">
                    <i class="fas fa-circle-notch"></i>
                    System Status
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-focus on username field
        document.getElementById('username').focus();
        
        // Toggle password visibility
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        
        // Add loading state to button on form submit
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.querySelector('.btn-signin');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Authenticating...';
            btn.disabled = true;
        });
    </script>
</body>
</html>
