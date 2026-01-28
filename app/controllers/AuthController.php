<?php
/**
 * Authentication Controller - Handles user login, registration, and logout
 */
class AuthController extends BaseController 
{
    private $userModel;
    private $memberModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->memberModel = new Member();
    }
    
    public function showLogin()
    {
        // Redirect if already logged in
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
            return;
        }
        
        $data = [
            'title' => 'Login - Shena Companion Welfare Association',
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('auth.login', $data);
    }
    
    public function login()
    {
        try {
            $this->validateCsrf();
            
            $email = $this->sanitizeInput($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            // Rate limiting - prevent brute force attacks
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $rateLimitKey = 'login_attempts_' . md5($ipAddress . $email);
            
            if (!isset($_SESSION[$rateLimitKey])) {
                $_SESSION[$rateLimitKey] = ['count' => 0, 'time' => time()];
            }
            
            // Reset counter after 15 minutes
            if (time() - $_SESSION[$rateLimitKey]['time'] > 900) {
                $_SESSION[$rateLimitKey] = ['count' => 0, 'time' => time()];
            }
            
            // Block after 5 failed attempts
            if ($_SESSION[$rateLimitKey]['count'] >= 5) {
                $waitTime = 900 - (time() - $_SESSION[$rateLimitKey]['time']);
                $_SESSION['error'] = 'Too many login attempts. Please try again in ' . ceil($waitTime / 60) . ' minutes.';
                $this->redirect('/login');
                return;
            }
            
            // Validate inputs
            if (empty($email) || empty($password)) {
                $_SESSION[$rateLimitKey]['count']++;
                $_SESSION['error'] = 'Please enter both email and password.';
                $this->redirect('/login');
                return;
            }
            
            // Find user by email
            try {
                $user = $this->userModel->findByEmail($email);
            } catch (Exception $e) {
                error_log('Database error during login: ' . $e->getMessage());
                $_SESSION['error'] = 'An error occurred. Please try again.';
                $this->redirect('/login');
                return;
            }
            
            if (!$user) {
                $_SESSION[$rateLimitKey]['count']++;
                $_SESSION['error'] = 'Invalid credentials.';
                $this->redirect('/login');
                return;
            }
            
            // Verify password
            if (!$this->userModel->verifyPassword($password, $user['password'])) {
                $_SESSION[$rateLimitKey]['count']++;
                $_SESSION['error'] = 'Invalid credentials.';
                $this->redirect('/login');
                return;
            }
            
            // Check if user is active
            if ($user['status'] !== 'active') {
                $_SESSION['error'] = 'Your account is not active. Please contact support.';
                $this->redirect('/login');
                return;
            }
            
            // Reset rate limit on successful login
            unset($_SESSION[$rateLimitKey]);
            
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);
            
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['login_time'] = time();
            
            // Update last login
            try {
                $this->userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
            } catch (Exception $e) {
                error_log('Failed to update last login: ' . $e->getMessage());
            }
            
            // Redirect based on role
            if (in_array($user['role'], ['super_admin', 'manager'])) {
                $this->redirect('/admin');
            } else {
                $this->redirect('/dashboard');
            }
            
        } catch (Exception $e) {
            error_log('Login error: ' . $e->getMessage());
            $_SESSION['error'] = 'An error occurred during login. Please try again.';
            $this->redirect('/login');
        }
    }
    
    public function showRegister()
    {
        // Redirect if already logged in
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
            return;
        }
        
        global $membership_packages;
        
        $data = [
            'title' => 'Register - Shena Companion Welfare Association',
            'csrf_token' => $this->generateCsrfToken(),
            'packages' => $membership_packages
        ];
        
        $this->view('auth.register', $data);
    }
    
    public function register()
    {
        try {
            $this->validateCsrf();
            
            // Sanitize inputs
            $userData = [
                'first_name' => $this->sanitizeInput($_POST['first_name'] ?? ''),
                'last_name' => $this->sanitizeInput($_POST['last_name'] ?? ''),
                'email' => $this->sanitizeInput($_POST['email'] ?? ''),
                'phone' => $this->sanitizeInput($_POST['phone'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? ''
            ];
            
            $memberData = [
                'id_number' => $this->sanitizeInput($_POST['id_number'] ?? ''),
                'date_of_birth' => $_POST['date_of_birth'] ?? '',
                'gender' => $_POST['gender'] ?? '',
                'address' => $this->sanitizeInput($_POST['address'] ?? ''),
                'next_of_kin' => $this->sanitizeInput($_POST['next_of_kin'] ?? ''),
                'next_of_kin_phone' => $this->sanitizeInput($_POST['next_of_kin_phone'] ?? ''),
                // This is the specific configured package key (e.g. individual_below_70)
                'package_key' => $_POST['package'] ?? ''
            ];
            
            // Validate required fields
            $required = ['first_name', 'last_name', 'email', 'phone', 'password'];
            foreach ($required as $field) {
                if (empty($userData[$field])) {
                    $_SESSION['error'] = 'Please fill in all required fields.';
                    $this->redirect('/register');
                    return;
                }
            }
            
            // Validate email
            if (!$this->validateEmail($userData['email'])) {
                $_SESSION['error'] = 'Please enter a valid email address.';
                $this->redirect('/register');
                return;
            }
            
            // Validate phone
            if (!$this->validatePhone($userData['phone'])) {
                $_SESSION['error'] = 'Please enter a valid Kenyan phone number.';
                $this->redirect('/register');
                return;
            }
            
            // Validate password
            if (strlen($userData['password']) < 8) {
                $_SESSION['error'] = 'Password must be at least 8 characters long.';
                $this->redirect('/register');
                return;
            }
            
            if ($userData['password'] !== $userData['confirm_password']) {
                $_SESSION['error'] = 'Passwords do not match.';
                $this->redirect('/register');
                return;
            }
            
            // Check if email already exists
            if ($this->userModel->findByEmail($userData['email'])) {
                $_SESSION['error'] = 'Email address already registered.';
                $this->redirect('/register');
                return;
            }
            
            // Check if phone already exists
            if ($this->userModel->findByPhone($userData['phone'])) {
                $_SESSION['error'] = 'Phone number already registered.';
                $this->redirect('/register');
                return;
            }
            
            // Validate age (18-100) and capture for later calculations
            $age = null;
            if (!empty($memberData['date_of_birth'])) {
                try {
                    $age = $this->memberModel->calculateAge($memberData['date_of_birth']);
                    if ($age < 18 || $age > 100) {
                        $_SESSION['error'] = 'Members must be between 18 and 100 years old.';
                        $this->redirect('/register');
                        return;
                    }
                } catch (Exception $e) {
                    error_log('Age calculation error: ' . $e->getMessage());
                    $_SESSION['error'] = 'Invalid date of birth.';
                    $this->redirect('/register');
                    return;
                }
            }
            
            // Start transaction
            try {
                $this->db->getConnection()->beginTransaction();
            } catch (Exception $e) {
                error_log('Failed to start transaction: ' . $e->getMessage());
                $_SESSION['error'] = 'Registration failed. Please try again.';
                $this->redirect('/register');
                return;
            }
            
            try {
                // Create user
                unset($userData['confirm_password']);
                $userId = $this->userModel->createUser($userData);
                
                // Generate member number
                $memberNumber = 'SC' . date('Y') . str_pad($userId, 4, '0', STR_PAD_LEFT);
                
                // Ensure we have a valid age for contribution/maturity calculations
                if ($age === null && !empty($memberData['date_of_birth'])) {
                    $age = $this->memberModel->calculateAge($memberData['date_of_birth']);
                }
                if ($age === null) {
                    $_SESSION['error'] = 'Date of birth is required to determine eligibility and package.';
                    $this->db->getConnection()->rollback();
                    $this->redirect('/register');
                    return;
                }
                
                // Determine high-level package category from selected configured package
                global $membership_packages;
                $selectedKey = $memberData['package_key'];
                if (empty($selectedKey) || !isset($membership_packages[$selectedKey])) {
                    $_SESSION['error'] = 'Please select a valid membership package.';
                    $this->db->getConnection()->rollback();
                    $this->redirect('/register');
                    return;
                }
                
                $selectedPackage = $membership_packages[$selectedKey];
                $packageCategory = $selectedPackage['category'] ?? 'individual';
                
                // Calculate monthly contribution strictly from configured package definition
                $monthlyContribution = $this->memberModel->calculateMonthlyContribution($selectedKey, $age);
                
                // Calculate maturity period end date based on age and policy configuration
                $maturityMonths = getMaturityPeriodMonths($age);
                $maturityEnds = date('Y-m-d', strtotime("+{$maturityMonths} months"));
                
                // Create member record
                $memberData['user_id'] = $userId;
                $memberData['member_number'] = $memberNumber;
                $memberData['monthly_contribution'] = $monthlyContribution;
                $memberData['package'] = $packageCategory;
                $memberData['status'] = 'inactive'; // Will become active once registration fee and first contributions are confirmed
                $memberData['maturity_ends'] = $maturityEnds;
                
                $memberId = $this->memberModel->create($memberData);
                
                // Commit transaction
                $this->db->getConnection()->commit();
                
                // Send welcome email (skip if mail server not configured)
                try {
                    $emailService = new EmailService();
                    @$emailService->sendWelcomeEmail($userData['email'], [
                        'name' => $userData['first_name'] . ' ' . $userData['last_name'],
                        'member_number' => $memberNumber
                    ]);
                } catch (Exception $e) {
                    error_log('Email sending failed: ' . $e->getMessage());
                }
                
                $_SESSION['success'] = 'Registration successful! Your membership application is under review. You will be notified via email once approved.';
                $this->redirect('/login');
                
            } catch (Exception $e) {
                $this->db->getConnection()->rollback();
                throw $e;
            }
            
        } catch (Exception $e) {
            error_log('Registration error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            $_SESSION['error'] = 'Registration failed: ' . ($e->getMessage() ?: 'Please try again.');
            $this->redirect('/register');
        }
    }
    
    public function logout()
    {
        session_destroy();
        session_start();
        $_SESSION['success'] = 'You have been logged out successfully.';
        $this->redirect('/login');
    }
}
