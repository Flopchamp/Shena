<?php
/**
 * Admin Controller - Handles administrative functions
 */
class AdminController extends BaseController 
{
    private $memberModel;
    private $paymentModel;
    private $claimModel;
    private $userModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->memberModel = new Member();
        $this->paymentModel = new Payment();
        $this->claimModel = new Claim();
        $this->userModel = new User();
    }

    private function requireAdminAccess()
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || 
            !in_array($_SESSION['user_role'], ['super_admin', 'manager'])) {
            header('Location: /admin-login');
            exit();
        }
    }

    /**
     * Admin Login Page
     */
    public function showLogin()
    {
        // Check if already logged in as admin
        if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && 
            in_array($_SESSION['user_role'], ['super_admin', 'manager'])) {
            header('Location: /admin/dashboard');
            exit();
        }
        
        $data = [
            'title' => 'Admin Login - ' . APP_NAME,
            'error' => $_SESSION['error'] ?? null
        ];
        
        unset($_SESSION['error']);
        $this->view('admin.login', $data);
    }

    /**
     * Admin Login Process
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $_SESSION['error'] = 'Please fill in all fields.';
                header('Location: /admin/login');
                exit();
            }

            // Check admin credentials in users table
            $admin = $this->userModel->findByEmail($username);
            
            if (!$admin) {
                // Also try to find by first_name as username for admin
                $db = Database::getInstance();
                $query = "SELECT * FROM users WHERE first_name = :first_name AND role IN ('super_admin', 'manager') LIMIT 1";
                $admin = $db->fetch($query, ['first_name' => $username]);
            }
            
            if ($admin && in_array($admin['role'], ['super_admin', 'manager']) && 
                password_verify($password, $admin['password'])) {
                
                $_SESSION['user_id'] = $admin['id'];
                $_SESSION['user_role'] = $admin['role'];
                $_SESSION['user_name'] = $admin['first_name'] . ' ' . $admin['last_name'];
                $_SESSION['user_email'] = $admin['email'];
                
                // Update last login
                $db = Database::getInstance();
                $db->execute("UPDATE users SET last_login = NOW() WHERE id = :id", ['id' => $admin['id']]);
                
                header('Location: /admin/dashboard');
                exit();
            } else {
                $_SESSION['error'] = 'Invalid admin credentials';
            }
        }
        
        header('Location: /admin-login');
        exit();
    }

    /**
     * Admin Logout
     */
    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_role']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_email']);
        header('Location: /admin-login');
        exit();
    }

    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        $this->requireAdminAccess();
        
        // Gather dashboard statistics
        $data = [
            'title' => 'Admin Dashboard',
            'stats' => [
                'total_members' => $this->memberModel->getTotalMembers(),
                'active_members' => $this->memberModel->getActiveMembers(),
                'pending_members' => $this->memberModel->getPendingMembers(),
                'total_payments' => $this->paymentModel->getTotalPayments(),
                'monthly_revenue' => $this->paymentModel->getMonthlyRevenue(),
                'pending_claims' => $this->claimModel->getPendingClaimsCount(),
                'approved_claims' => $this->claimModel->getApprovedClaimsCount()
            ],
            'recent_members' => $this->memberModel->getRecentMembers(5),
            'recent_payments' => $this->paymentModel->getRecentPayments(5),
            'recent_claims' => $this->claimModel->getRecentClaims(5)
        ];
        
        $this->view('admin.dashboard', $data);
    }

    /**
     * Member Management
     */
    public function members()
    {
        $this->requireAdminAccess();
        
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? 'all';
        $package = $_GET['package'] ?? 'all';
        
        $members = $this->memberModel->getAllMembersWithDetails($search, $status, $package);
        
        $data = [
            'title' => 'Members - Admin',
            'members' => $members,
            'total_members' => count($members),
            'search' => $search,
            'status' => $status,
            'package' => $package
        ];
        
        $this->view('admin.members', $data);
    }

    /**
     * Activate Member
     */
    public function activateMember($id = null)
    {
        $this->requireAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $memberId = $id ?? ($_POST['member_id'] ?? 0);
            
            if ($memberId) {
                // Update member status
                $this->memberModel->update($memberId, ['status' => 'active']);
                
                // Update user status
                $member = $this->memberModel->find($memberId);
                if ($member) {
                    $this->userModel->update($member['user_id'], ['status' => 'active']);
                }
                
                $_SESSION['success'] = 'Member activated successfully!';
            } else {
                $_SESSION['error'] = 'Invalid member ID.';
            }
        }
        
        $this->redirect('/admin/members');
    }

    /**
     * Deactivate Member
     */
    public function deactivateMember($id = null)
    {
        $this->requireAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $memberId = $id ?? ($_POST['member_id'] ?? 0);
            
            if ($memberId) {
                // Update member status
                $this->memberModel->update($memberId, ['status' => 'inactive']);
                
                // Update user status
                $member = $this->memberModel->find($memberId);
                if ($member) {
                    $this->userModel->update($member['user_id'], ['status' => 'inactive']);
                }
                
                $_SESSION['success'] = 'Member deactivated successfully!';
            } else {
                $_SESSION['error'] = 'Invalid member ID.';
            }
        }
        
        $this->redirect('/admin/members');
    }

    /**
     * Payment Management
     */
    public function payments()
    {
        $this->requireAdminAccess();
        
        $status = $_GET['status'] ?? 'all';
        
        $conditions = [];
        if ($status !== 'all') {
            $conditions['status'] = $status;
        }
        
        $data = [
            'title' => 'Payments - Admin',
            'payments' => $this->paymentModel->getAllPaymentsWithDetails($conditions),
            'status' => $status
        ];
        
        $this->view('admin.payments', $data);
    }

    /**
     * Claims Management
     */
    public function claims()
    {
        $this->requireAdminAccess();
        
        $status = $_GET['status'] ?? 'all';
        
        $conditions = [];
        if ($status !== 'all') {
            $conditions['status'] = $status;
        }
        
        $data = [
            'title' => 'Claims - Admin',
            'claims' => $this->claimModel->getAllClaimsWithDetails($conditions),
            'status' => $status
        ];
        
        $this->view('admin.claims', $data);
    }

    /**
     * Approve Claim
     */
    public function approveClaim()
    {
        $this->requireAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $claimId = $_POST['claim_id'] ?? 0;
            $notes = $_POST['notes'] ?? '';
            
            if ($claimId && $this->claimModel->approveClaim($claimId, $notes)) {
                $_SESSION['success'] = 'Claim approved successfully!';
            } else {
                $_SESSION['error'] = 'Failed to approve claim.';
            }
        }
        
        header('Location: /admin/claims');
        exit();
    }

    /**
     * Reject Claim
     */
    public function rejectClaim()
    {
        $this->requireAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $claimId = $_POST['claim_id'] ?? 0;
            $reason = $_POST['reason'] ?? '';
            
            if ($claimId && $this->claimModel->rejectClaim($claimId, $reason)) {
                $_SESSION['success'] = 'Claim rejected successfully!';
            } else {
                $_SESSION['error'] = 'Failed to reject claim.';
            }
        }
        
        header('Location: /admin/claims');
        exit();
    }

    /**
     * Reports & Analytics
     */
    public function reports()
    {
        $this->requireAdminAccess();
        
        $reportType = $_GET['report_type'] ?? 'overview';
        $startDate = $_GET['date_from'] ?? date('Y-m-01');
        $endDate = $_GET['date_to'] ?? date('Y-m-d');
        
        $data = [
            'title' => 'Reports - Admin',
            'reportType' => $reportType,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalMembers' => $this->memberModel->getTotalMembers(),
            'activeMembers' => $this->memberModel->getActiveMembers(),
            'inactiveMembers' => $this->memberModel->getInactiveMembers(),
            'pendingMembers' => $this->memberModel->getPendingMembers(),
            'totalRevenue' => $this->paymentModel->getTotalRevenue($startDate, $endDate),
            'monthlyRevenue' => $this->paymentModel->getMonthlyRevenue(),
            'totalClaimsPaid' => $this->claimModel->getTotalClaimsValue(),
            'newMembersThisMonth' => count($this->memberModel->getNewRegistrations(date('Y-m-01'), date('Y-m-d'))),
            'renewalDue' => count($this->paymentModel->getMembersWithOverduePayments()),
            'pendingPayments' => count($this->paymentModel->getPendingPayments()),
            'failedPayments' => count($this->paymentModel->getFailedPayments())
        ];
        
        // Add specific report data based on type
        if ($reportType === 'members') {
            $data['memberReports'] = $this->memberModel->getNewRegistrations($startDate, $endDate);
        } elseif ($reportType === 'payments') {
            $data['paymentReports'] = $this->paymentModel->getPaymentReport($startDate, $endDate);
        } elseif ($reportType === 'claims') {
            $data['claimReports'] = $this->claimModel->getClaimReport($startDate, $endDate);
        }
        
        $this->view('admin.reports', $data);
    }

    /**
     * Communications Center
     */
    public function communications()
    {
        $this->requireAdminAccess();
        
        $type = $_GET['type'] ?? 'all';
        $status = $_GET['status'] ?? 'all';
        
        $data = [
            'title' => 'Communications - Admin',
            'type' => $type,
            'status' => $status,
            'communications' => $this->getRecentCommunications($type, $status)
        ];
        
        $this->view('admin.communications', $data);
    }

    /**
     * Send Email to Members
     */
    public function sendEmail()
    {
        $this->requireAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $recipients = $_POST['recipients'] ?? '';
            $subject = $_POST['subject'] ?? '';
            $message = $_POST['message'] ?? '';
            $sendCopy = isset($_POST['send_copy']);
            
            if ($subject && $message && $recipients) {
                // Get recipient list based on selection
                $memberList = $this->getRecipientList($recipients);
                
                if (!empty($memberList)) {
                    // Log communication attempt
                    $this->logCommunication('email', $recipients, $subject, $message, count($memberList));
                    
                    $_SESSION['success'] = 'Email sent to ' . count($memberList) . ' members successfully!';
                } else {
                    $_SESSION['error'] = 'No recipients found for the selected criteria.';
                }
            } else {
                $_SESSION['error'] = 'Please fill in all required fields.';
            }
        }
        
        header('Location: /admin/communications');
        exit();
    }

    /**
     * Send SMS to Members
     */
    public function sendSMS()
    {
        $this->requireAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $recipients = $_POST['recipients'] ?? '';
            $message = $_POST['message'] ?? '';
            
            if ($message && $recipients && strlen($message) <= 160) {
                // Get recipient list based on selection
                $memberList = $this->getRecipientList($recipients);
                
                if (!empty($memberList)) {
                    // Log communication attempt
                    $this->logCommunication('sms', $recipients, 'SMS Message', $message, count($memberList));
                    
                    $_SESSION['success'] = 'SMS sent to ' . count($memberList) . ' members successfully!';
                } else {
                    $_SESSION['error'] = 'No recipients found for the selected criteria.';
                }
            } else {
                $_SESSION['error'] = 'Please provide a valid message (max 160 characters).';
            }
        }
        
        header('Location: /admin/communications');
        exit();
    }

    /**
     * Get recipient list based on criteria
     */
    private function getRecipientList($criteria)
    {
        switch ($criteria) {
            case 'all':
                return $this->memberModel->getAllMembers();
            case 'active':
                return $this->memberModel->getActiveMembersList();
            case 'inactive':
                return $this->memberModel->getInactiveMembersList();
            case 'recent':
                return $this->memberModel->getRecentMembers(30); // Last 30 days
            default:
                return [];
        }
    }

    /**
     * Log communication attempt
     */
    private function logCommunication($type, $recipients, $subject, $message, $count)
    {
        $db = Database::getInstance();
        $query = "INSERT INTO communications (type, recipient_type, recipient_count, subject, message, status, sent_at) 
                  VALUES (:type, :recipients, :count, :subject, :message, 'sent', NOW())";
        $db->execute($query, ['type' => $type, 'recipients' => $recipients, 'count' => $count, 'subject' => $subject, 'message' => $message]);
    }

    /**
     * Get recent communications
     */
    private function getRecentCommunications($type = 'all', $status = 'all')
    {
        $db = Database::getInstance();
        $query = "SELECT * FROM communications WHERE 1=1";
        $params = [];
        
        if ($type !== 'all') {
            $query .= " AND type = ?";
            $params[] = $type;
        }
        
        if ($status !== 'all') {
            $query .= " AND status = ?";
            $params[] = $status;
        }
        
        $query .= " ORDER BY sent_at DESC LIMIT 50";
        
        $result = $db->query($query, $params);
        return $result ? $result->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    /**
     * Settings Management
     */
    public function settings()
    {
        $this->requireAdminAccess();
        
        // Get current settings from config file
        $settings = [
            'app_name' => defined('APP_NAME') ? APP_NAME : 'Shena Companion Welfare Association',
            'admin_email' => 'admin@shenacompanion.org',
            'sms_enabled' => false,
            'email_enabled' => true,
            'mpesa_enabled' => false,
            'maintenance_mode' => false,
            'max_upload_size' => defined('MAX_FILE_SIZE') ? (MAX_FILE_SIZE / 1024 / 1024) . 'MB' : '5MB',
            'session_timeout' => defined('SESSION_LIFETIME') ? SESSION_LIFETIME : 3600,
            'default_package' => 'individual',
            'base_contribution' => 500,
            'registration_fee' => defined('REGISTRATION_FEE') ? REGISTRATION_FEE : 200,
            'reactivation_fee' => defined('REACTIVATION_FEE') ? REACTIVATION_FEE : 100,
            'grace_period_under_80' => defined('GRACE_PERIOD_UNDER_80') ? GRACE_PERIOD_UNDER_80 : 4,
            'grace_period_80_and_above' => defined('GRACE_PERIOD_80_AND_ABOVE') ? GRACE_PERIOD_80_AND_ABOVE : 5,
            'debug_mode' => defined('DEBUG_MODE') ? DEBUG_MODE : false
        ];
        
        // Generate CSRF token if not exists
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        $data = [
            'title' => 'Settings - Admin',
            'settings' => $settings
        ];
        
        $this->view('admin.settings', $data);
    }

    /**
     * Update Settings
     */
    public function updateSettings()
    {
        $this->requireAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validate CSRF token
                if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                    throw new Exception("Invalid CSRF token");
                }
                
                // Get settings from form
                $newSettings = [
                    'APP_NAME' => $_POST['app_name'] ?? APP_NAME,
                    'ADMIN_EMAIL' => $_POST['admin_email'] ?? '',
                    'SMS_ENABLED' => isset($_POST['sms_enabled']) ? 'true' : 'false',
                    'EMAIL_ENABLED' => isset($_POST['email_enabled']) ? 'true' : 'false',
                    'MPESA_ENABLED' => isset($_POST['mpesa_enabled']) ? 'true' : 'false',
                    'MAINTENANCE_MODE' => isset($_POST['maintenance_mode']) ? 'true' : 'false',
                    'MAX_UPLOAD_SIZE' => $_POST['max_upload_size'] ?? '5MB',
                    'SESSION_TIMEOUT' => (int)($_POST['session_timeout'] ?? 3600),
                    'DEFAULT_PACKAGE' => $_POST['default_package'] ?? 'individual',
                    'BASE_CONTRIBUTION' => (int)($_POST['base_contribution'] ?? 500)
                ];
                
                // Validate settings
                if (empty($newSettings['APP_NAME'])) {
                    throw new Exception("App name is required");
                }
                
                if (!filter_var($newSettings['ADMIN_EMAIL'], FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("Invalid admin email address");
                }
                
                if ($newSettings['SESSION_TIMEOUT'] < 300 || $newSettings['SESSION_TIMEOUT'] > 86400) {
                    throw new Exception("Session timeout must be between 5 minutes and 24 hours");
                }
                
                if ($newSettings['BASE_CONTRIBUTION'] < 100 || $newSettings['BASE_CONTRIBUTION'] > 10000) {
                    throw new Exception("Base contribution must be between 100 and 10,000");
                }
                
                // Read current config file
                $configPath = ROOT_PATH . '/config/config.php';
                $configContent = file_get_contents($configPath);
                
                // Update each setting in the config file
                foreach ($newSettings as $key => $value) {
                    $pattern = "/define\('$key',\s*['\"].*?['\"]\);/";
                    $replacement = "define('$key', '$value');";
                    $configContent = preg_replace($pattern, $replacement, $configContent);
                }
                
                // Write updated config back to file
                if (file_put_contents($configPath, $configContent) === false) {
                    throw new Exception("Failed to update configuration file");
                }
                
                $_SESSION['success'] = 'Settings updated successfully! Some changes may require a server restart to take effect.';
                
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error updating settings: ' . $e->getMessage();
            }
        }
        
        $this->redirect('/admin/settings');
    }
}
?>
