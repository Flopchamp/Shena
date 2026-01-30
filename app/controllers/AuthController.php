<?php
/**
 * Authentication Controller - Handles user login, registration, and logout
 */
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Member.php';

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
            } elseif ($user['role'] === 'agent') {
                $this->redirect('/agent/dashboard');
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
                    $_SESSION['old_input'] = array_merge($userData, $memberData);
                    unset($_SESSION['old_input']['password'], $_SESSION['old_input']['confirm_password']);
                    $this->redirect('/register');
                    return;
                }
            }
            
            // Validate email
            if (!$this->validateEmail($userData['email'])) {
                $_SESSION['error'] = 'Please enter a valid email address.';
                $_SESSION['old_input'] = array_merge($userData, $memberData);
                unset($_SESSION['old_input']['password'], $_SESSION['old_input']['confirm_password']);
                $_SESSION['error_field'] = 'email';
                $this->redirect('/register');
                return;
            }
            
            // Validate phone
            if (!$this->validatePhone($userData['phone'])) {
                $_SESSION['error'] = 'Please enter a valid Kenyan phone number.';
                $_SESSION['old_input'] = array_merge($userData, $memberData);
                unset($_SESSION['old_input']['password'], $_SESSION['old_input']['confirm_password']);
                $_SESSION['error_field'] = 'phone';
                $this->redirect('/register');
                return;
            }
            
            // Validate password
            if (strlen($userData['password']) < 8) {
                $_SESSION['error'] = 'Password must be at least 8 characters long.';
                $_SESSION['old_input'] = array_merge($userData, $memberData);
                unset($_SESSION['old_input']['password'], $_SESSION['old_input']['confirm_password']);
                $_SESSION['error_field'] = 'password';
                $this->redirect('/register');
                return;
            }
            
            if ($userData['password'] !== $userData['confirm_password']) {
                $_SESSION['error'] = 'Passwords do not match.';
                $_SESSION['old_input'] = array_merge($userData, $memberData);
                unset($_SESSION['old_input']['password'], $_SESSION['old_input']['confirm_password']);
                $_SESSION['error_field'] = 'confirm_password';
                $this->redirect('/register');
                return;
            }
            
            // Check if email already exists
            if ($this->userModel->findByEmail($userData['email'])) {
                $_SESSION['error'] = 'Email address already registered.';
                $_SESSION['old_input'] = array_merge($userData, $memberData);
                unset($_SESSION['old_input']['password'], $_SESSION['old_input']['confirm_password']);
                $_SESSION['error_field'] = 'email';
                $this->redirect('/register');
                return;
            }
            
            // Check if phone already exists
            if ($this->userModel->findByPhone($userData['phone'])) {
                $_SESSION['error'] = 'Phone number already registered.';
                $_SESSION['old_input'] = array_merge($userData, $memberData);
                unset($_SESSION['old_input']['password'], $_SESSION['old_input']['confirm_password']);
                $_SESSION['error_field'] = 'phone';
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
                        $_SESSION['old_input'] = array_merge($userData, $memberData);
                        unset($_SESSION['old_input']['password'], $_SESSION['old_input']['confirm_password']);
                        $_SESSION['error_field'] = 'date_of_birth';
                        $this->redirect('/register');
                        return;
                    }
                } catch (Exception $e) {
                    error_log('Age calculation error: ' . $e->getMessage());
                    $_SESSION['error'] = 'Invalid date of birth.';
                    $_SESSION['old_input'] = array_merge($userData, $memberData);
                    unset($_SESSION['old_input']['password'], $_SESSION['old_input']['confirm_password']);
                    $_SESSION['error_field'] = 'date_of_birth';
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
                    $_SESSION['old_input'] = array_merge($userData, $memberData);
                    unset($_SESSION['old_input']['password'], $_SESSION['old_input']['confirm_password']);
                    $_SESSION['error_field'] = 'date_of_birth';
                    $this->db->getConnection()->rollback();
                    $this->redirect('/register');
                    return;
                }
                
                // Determine package from selected package key
                global $membership_packages;
                $packageKey = $memberData['package'] ?? null;
                
                if (empty($packageKey) || !isset($membership_packages[$packageKey])) {
                    $_SESSION['error'] = 'Please select a valid membership package.';
                    $_SESSION['old_input'] = array_merge($userData, $memberData);
                    unset($_SESSION['old_input']['password'], $_SESSION['old_input']['confirm_password']);
                    $_SESSION['error_field'] = 'package';
                    $this->db->getConnection()->rollback();
                    $this->redirect('/register');
                    return;
                }
                
                $selectedPackage = $membership_packages[$packageKey];
                $monthlyContribution = $selectedPackage['monthly_contribution'];
                $packageCategory = $selectedPackage['category'] ?? 'individual';
                
                // Calculate maturity period end date based on age and policy configuration
                $maturityMonths = isset($selectedPackage['maturity_months']) ? $selectedPackage['maturity_months'] : 
                                  ($age >= 81 ? MATURITY_PERIOD_80_AND_ABOVE : MATURITY_PERIOD_UNDER_80);
                $maturityEnds = date('Y-m-d', strtotime("+{$maturityMonths} months"));
                
                // Create member record with actual database columns
                $memberRecord = [
                    'user_id' => $userId,
                    'member_number' => $memberNumber,
                    'id_number' => $memberData['id_number'] ?? '',
                    'date_of_birth' => $memberData['date_of_birth'] ?? null,
                    'gender' => $memberData['gender'] ?? 'male',
                    'address' => $memberData['address'] ?? '',
                    'next_of_kin' => $memberData['next_of_kin'] ?? '',
                    'next_of_kin_phone' => $memberData['next_of_kin_phone'] ?? '',
                    'package' => $packageCategory,
                    'monthly_contribution' => $monthlyContribution,
                    'maturity_ends' => $maturityEnds,
                    'status' => 'inactive',
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                $memberId = $this->memberModel->create($memberRecord);
                
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
                
                unset($_SESSION['old_input'], $_SESSION['error_field']);
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
            // Preserve form values even on unexpected errors
            if (isset($userData) && isset($memberData)) {
                $_SESSION['old_input'] = array_merge($userData, $memberData);
                unset($_SESSION['old_input']['password'], $_SESSION['old_input']['confirm_password']);
            }
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
    
    /**
     * Show public registration page
     */
    public function showPublicRegistration()
    {
        global $membership_packages;
        
        // Convert packages array to include IDs (using array keys as IDs)
        $packagesWithIds = [];
        foreach ($membership_packages as $key => $package) {
            $package['id'] = $key;
            $package['key'] = $key; // Keep the key for backward compatibility
            $packagesWithIds[] = $package;
        }
        
        $data = [
            'title' => 'Join Shena Companion - Public Registration',
            'packages' => $packagesWithIds,
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('public.register-public', $data);
    }
    
    /**
     * Process public registration with payment
     */
    public function processPublicRegistration()
    {
        header('Content-Type: application/json');
        
        try {
            $this->validateCsrf();
            
            // Validate required fields
            $required = ['package_id', 'first_name', 'last_name', 'national_id', 'date_of_birth', 
                        'email', 'phone', 'address', 'county', 'payment_method'];
            
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("Field {$field} is required");
                }
            }
            
            // Sanitize inputs
            $packageId = $this->sanitizeInput($_POST['package_id'] ?? '');
            $firstName = $this->sanitizeInput($_POST['first_name']);
            $lastName = $this->sanitizeInput($_POST['last_name']);
            $nationalId = $this->sanitizeInput($_POST['national_id']);
            $dateOfBirth = $_POST['date_of_birth'];
            $email = $this->sanitizeInput($_POST['email']);
            $phone = $this->sanitizeInput($_POST['phone']);
            $address = $this->sanitizeInput($_POST['address']);
            $county = $this->sanitizeInput($_POST['county']);
            $subCounty = $this->sanitizeInput($_POST['sub_county'] ?? '');
            $postalCode = $this->sanitizeInput($_POST['postal_code'] ?? '');
            $paymentMethod = $this->sanitizeInput($_POST['payment_method']);
            
            // Validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Invalid email address');
            }
            
            // Validate phone (Kenyan format)
            $phone = preg_replace('/[^0-9]/', '', $phone);
            if (!preg_match('/^(254|0)[17][0-9]{8}$/', $phone)) {
                throw new Exception('Invalid phone number. Use format: 0712345678 or 254712345678');
            }
            
            // Normalize phone to 254 format
            if (substr($phone, 0, 1) === '0') {
                $phone = '254' . substr($phone, 1);
            }
            
            // Validate age
            $age = floor((time() - strtotime($dateOfBirth)) / 31557600); // Seconds in a year
            if ($age < 18) {
                throw new Exception('You must be at least 18 years old to register');
            }
            
            // Get package details
            global $membership_packages;
            $package = null;
            
            // Package ID is the array key
            if (isset($membership_packages[$packageId])) {
                $package = $membership_packages[$packageId];
            }
            
            if (!$package) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid package selected',
                    'old_values' => $_POST
                ]);
                return;
            }
            
            // Validate age against package limits
            if (isset($package['age_max']) && $age > $package['age_max']) {
                echo json_encode([
                    'success' => false,
                    'message' => "You exceed the maximum age ({$package['age_max']}) for this package",
                    'field' => 'date_of_birth',
                    'old_values' => $_POST
                ]);
                return;
            }
            
            if (isset($package['age_min']) && $age < $package['age_min']) {
                echo json_encode([
                    'success' => false,
                    'message' => "You are below the minimum age ({$package['age_min']}) for this package",
                    'field' => 'date_of_birth',
                    'old_values' => $_POST
                ]);
                return;
            }
            
            // Check if email or national ID already exists
            $existingUser = $this->userModel->findByEmail($email);
            if ($existingUser) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Email address already registered',
                    'field' => 'email',
                    'old_values' => $_POST
                ]);
                return;
            }
            
            $existingMember = $this->memberModel->findByNationalId($nationalId);
            if ($existingMember) {
                echo json_encode([
                    'success' => false,
                    'message' => 'National ID already registered',
                    'field' => 'national_id',
                    'old_values' => $_POST
                ]);
                return;
            }
            
            $this->db->getConnection()->beginTransaction();
            
            try {
                // Generate member number
                $memberNumber = $this->generateMemberNumber();
                
                // Create user account (password will be sent via email)
                $tempPassword = bin2hex(random_bytes(8));
                $hashedPassword = password_hash($tempPassword, PASSWORD_DEFAULT);
                
                $userData = [
                    'email' => $email,
                    'password' => $hashedPassword,
                    'role' => 'member',
                    'status' => 'pending',
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                $userId = $this->userModel->create($userData);
                
                // Create member record
                $maturityMonths = $package['maturity_months'] ?? 4;
                $maturityEnds = date('Y-m-d', strtotime("+{$maturityMonths} months"));
                
                $memberData = [
                    'user_id' => $userId,
                    'member_number' => $memberNumber,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'national_id' => $nationalId,
                    'date_of_birth' => $dateOfBirth,
                    'phone' => $phone,
                    'email' => $email,
                    'address' => $address,
                    'county' => $county,
                    'sub_county' => $subCounty,
                    'postal_code' => $postalCode,
                    'package_id' => $packageId,
                    'monthly_contribution' => $package['monthly_contribution'],
                    'status' => 'pending_payment', // Awaiting registration fee
                    'registration_date' => date('Y-m-d'),
                    'maturity_ends' => $maturityEnds,
                    'payment_deadline' => date('Y-m-d', strtotime('+14 days')), // 2-week deadline
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                $memberId = $this->memberModel->create($memberData);
                
                // Handle payment based on method
                if ($paymentMethod === 'mpesa') {
                    // M-Pesa STK Push will be implemented separately
                    // For now, set status to awaiting_payment
                    $paymentData = [
                        'member_id' => $memberId,
                        'amount' => REGISTRATION_FEE,
                        'payment_type' => 'registration',
                        'payment_method' => 'mpesa',
                        'status' => 'pending',
                        'reference' => 'REG' . $phone,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                } else {
                    // Cash/office payment
                    $paymentData = [
                        'member_id' => $memberId,
                        'amount' => REGISTRATION_FEE,
                        'payment_type' => 'registration',
                        'payment_method' => 'cash',
                        'status' => 'pending',
                        'notes' => 'Awaiting office payment within 14 days',
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }
                
                $paymentModel = new Payment();
                $paymentModel->create($paymentData);
                
                $this->db->getConnection()->commit();
                
                // Send confirmation email
                try {
                    $emailService = new EmailService();
                    $emailService->sendRegistrationConfirmation($email, [
                        'name' => $firstName . ' ' . $lastName,
                        'member_number' => $memberNumber,
                        'package' => $package['name'],
                        'monthly_amount' => $package['monthly_contribution'],
                        'payment_method' => $paymentMethod,
                        'temp_password' => $tempPassword,
                        'payment_deadline' => date('F j, Y', strtotime('+14 days'))
                    ]);
                    
                    $smsService = new SmsService();
                    $smsService->sendWelcomeSms($phone, [
                        'member_number' => $memberNumber
                    ]);
                } catch (Exception $e) {
                    error_log('Notification sending failed: ' . $e->getMessage());
                }
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Registration successful! Check your email for login credentials.',
                    'member_number' => $memberNumber,
                    'payment_method' => $paymentMethod
                ]);
                
            } catch (Exception $e) {
                $this->db->getConnection()->rollback();
                throw $e;
            }
            
        } catch (Exception $e) {
            error_log('Public registration error: ' . $e->getMessage());
            
            // Prepare old values without sensitive data
            $oldValues = $_POST ?? [];
            unset($oldValues['csrf_token']);
            
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
                'old_values' => $oldValues
            ]);
        }
    }
    
    /**
     * Generate unique member number
     */
    private function generateMemberNumber()
    {
        $prefix = 'SCA';
        $year = date('Y');
        
        // Get the last member number for this year
        $lastMember = $this->memberModel->getLastMemberByYear($year);
        
        if ($lastMember && preg_match('/^SCA' . $year . '(\d{4})$/', $lastMember['member_number'], $matches)) {
            $sequence = intval($matches[1]) + 1;
        } else {
            $sequence = 1;
        }
        
        return $prefix . $year . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}

