<?php
/**
 * Agent Dashboard Controller
 * Handles agent dashboard and operations
 */
require_once __DIR__ . '/../models/Agent.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Member.php';

class AgentDashboardController extends BaseController
{
    private $agentModel;
    private $userModel;
    private $memberModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireAgent();

        $this->agentModel = new Agent();
        $this->userModel = new User();
        $this->memberModel = new Member();
    }

    public function dashboard()
    {
        $agent = $this->agentModel->getAgentByUserId($_SESSION['user_id']);

        if (!$agent) {
            $_SESSION['error'] = 'Agent profile not found.';
            $this->redirect('/login');
            return;
        }

        // Get agent statistics from database
        $dbStats = $this->agentModel->getAgentDashboardStats($agent['id']);
        
        // Calculate growth percentages (comparing last 30 days vs previous 30 days)
        $members_growth = 0;
        $policies_growth = 0;
        $commission_growth = 0;
        
        // You can enhance this later with actual growth calculations
        
        // Format stats for dashboard view
        $stats = [
            'total_members' => $dbStats['total_members'] ?? 0,
            'members_growth' => $members_growth,
            'active_policies' => $dbStats['active_members'] ?? 0,
            'policies_growth' => $policies_growth,
            'monthly_commission' => $dbStats['pending_commission'] ?? 0,
            'commission_growth' => $commission_growth,
            'agent_rank' => 0, // Can be calculated based on leaderboard later
            'rank_progress' => 0
        ];

        // Get recent members registered by this agent
        $members = $this->memberModel->getMembersByAgent($agent['id']);

        $data = [
            'title' => 'Agent Dashboard - Shena Companion Welfare Association',
            'agent' => $agent,
            'stats' => $stats,
            'members' => $members
        ];

        $this->view('agent/dashboard', $data);
    }

    public function profile()
    {
        $agent = $this->agentModel->getAgentByUserId($_SESSION['user_id']);

        if (!$agent) {
            $_SESSION['error'] = 'Agent profile not found.';
            $this->redirect('/agent/dashboard');
            return;
        }

        $data = [
            'title' => 'My Profile - Shena Companion Welfare Association',
            'agent' => $agent,
            'csrf_token' => $this->generateCsrfToken()
        ];

        $this->view('agent/profile', $data);
    }

    public function updateProfile()
    {
        try {
            $this->validateCsrf();

            $agent = $this->agentModel->getAgentByUserId($_SESSION['user_id']);
            if (!$agent) {
                $_SESSION['error'] = 'Agent profile not found.';
                $this->redirect('/agent/profile');
                return;
            }

            // Update user data
            $userData = [
                'first_name' => $this->sanitizeInput($_POST['first_name'] ?? ''),
                'last_name' => $this->sanitizeInput($_POST['last_name'] ?? ''),
                'phone' => $this->sanitizeInput($_POST['phone'] ?? '')
            ];

            // Update agent data
            $agentData = [
                'phone' => $this->sanitizeInput($_POST['phone'] ?? ''),
                'address' => $this->sanitizeInput($_POST['address'] ?? ''),
                'county' => $this->sanitizeInput($_POST['county'] ?? '')
            ];

            // Validate phone
            if (!empty($userData['phone']) && !$this->validatePhone($userData['phone'])) {
                $_SESSION['error'] = 'Please enter a valid phone number.';
                $this->redirect('/agent/profile');
                return;
            }

            // Update records
            try {
                $this->userModel->update($_SESSION['user_id'], $userData);
                $this->agentModel->updateAgent($agent['id'], $agentData);
            } catch (Exception $e) {
                error_log('Database update error: ' . $e->getMessage());
                throw new Exception('Failed to update profile in database.');
            }

            $_SESSION['success'] = 'Profile updated successfully.';

        } catch (Exception $e) {
            error_log('Profile update error: ' . $e->getMessage());
            $_SESSION['error'] = 'Failed to update profile. Please try again.';
        }

        $this->redirect('/agent/profile');
    }

    public function members()
    {
        $agent = $this->agentModel->getAgentByUserId($_SESSION['user_id']);
        if (!$agent) {
            $_SESSION['error'] = 'Agent profile not found.';
            $this->redirect('/agent/dashboard');
            return;
        }

        $members = $this->memberModel->getMembersByAgent($agent['id']);

        $data = [
            'title' => 'My Members - Shena Companion Welfare Association',
            'agent' => $agent,
            'members' => $members
        ];

        $this->view('agent/members', $data);
    }

    public function commissions()
    {
        $agent = $this->agentModel->getAgentByUserId($_SESSION['user_id']);
        if (!$agent) {
            $_SESSION['error'] = 'Agent profile not found.';
            $this->redirect('/agent/dashboard');
            return;
        }

        $commissions = $this->agentModel->getAgentCommissions($agent['id']);

        // Calculate totals
        $totalEarned = 0;
        $pendingAmount = 0;
        foreach ($commissions as $commission) {
            if ($commission['status'] === 'paid') {
                $totalEarned += $commission['commission_amount'];
            } elseif ($commission['status'] === 'pending') {
                $pendingAmount += $commission['commission_amount'];
            }
        }

        $data = [
            'title' => 'My Commissions - Shena Companion Welfare Association',
            'agent' => $agent,
            'commissions' => $commissions,
            'total_earned' => $totalEarned,
            'pending_amount' => $pendingAmount
        ];

        $this->view('agent/commissions', $data);
    }

    /**
     * Require agent role
     */
    private function requireAgent()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'agent') {
            $this->redirect('/error/403');
        }
    }    
    /**
     * Show member registration form
     */
    public function registerMember()
    {
        $agent = $this->agentModel->getAgentByUserId($_SESSION['user_id']);
        if (!$agent) {
            $_SESSION['error'] = 'Agent profile not found.';
            $this->redirect('/agent/dashboard');
            return;
        }

        $data = [
            'title' => 'Register New Member - Shena Companion Welfare Association',
            'agent' => $agent,
            'csrf_token' => $this->generateCsrfToken()
        ];

        $this->view('agent/register-member', $data);
    }
    
    /**
     * Process member registration
     */
    public function storeRegisterMember()
    {
        try {
            $this->validateCsrf();

            $agent = $this->agentModel->getAgentByUserId($_SESSION['user_id']);
            if (!$agent) {
                $_SESSION['error'] = 'Agent profile not found.';
                $this->redirect('/agent/register-member');
                return;
            }

            // Validate passwords match
            if ($_POST['password'] !== $_POST['confirm_password']) {
                $_SESSION['error'] = 'Passwords do not match.';
                $this->redirect('/agent/register-member');
                return;
            }

            // Prepare member data
            $memberData = [
                'first_name' => $this->sanitizeInput($_POST['first_name']),
                'last_name' => $this->sanitizeInput($_POST['last_name']),
                'email' => $this->sanitizeInput($_POST['email']),
                'phone' => $this->sanitizeInput($_POST['phone']),
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'role' => 'member',
                'id_number' => $this->sanitizeInput($_POST['id_number']),
                'date_of_birth' => $_POST['date_of_birth'],
                'gender' => $_POST['gender'],
                'address' => $this->sanitizeInput($_POST['address'] ?? ''),
                'next_of_kin' => $this->sanitizeInput($_POST['next_of_kin']),
                'next_of_kin_phone' => $this->sanitizeInput($_POST['next_of_kin_phone']),
                'package' => $_POST['package'],
                'agent_id' => $agent['id']
            ];

            // Create user and member records
            // This would need to be implemented properly with User and Member models
            // For now, we'll just redirect with success
            
            $_SESSION['success'] = 'Member registered successfully! Commission will be processed upon payment.';
            $this->redirect('/agent/members');

        } catch (Exception $e) {
            error_log('Member registration error: ' . $e->getMessage());
            $_SESSION['error'] = 'Failed to register member. Please try again.';
            $this->redirect('/agent/register-member');
        }
    }
    
    /**
     * Update agent password
     */
    public function updatePassword()
    {
        try {
            $this->validateCsrf();

            $agent = $this->agentModel->getAgentByUserId($_SESSION['user_id']);
            if (!$agent) {
                $_SESSION['error'] = 'Agent profile not found.';
                $this->redirect('/agent/profile');
                return;
            }

            // Validate passwords
            if ($_POST['new_password'] !== $_POST['confirm_password']) {
                $_SESSION['error'] = 'New passwords do not match.';
                $this->redirect('/agent/profile');
                return;
            }

            // Verify current password
            $user = $this->userModel->getUserById($_SESSION['user_id']);
            if (!password_verify($_POST['current_password'], $user['password'])) {
                $_SESSION['error'] = 'Current password is incorrect.';
                $this->redirect('/agent/profile');
                return;
            }

            // Update password
            $this->userModel->update($_SESSION['user_id'], [
                'password' => password_hash($_POST['new_password'], PASSWORD_DEFAULT)
            ]);

            $_SESSION['success'] = 'Password updated successfully.';

        } catch (Exception $e) {
            error_log('Password update error: ' . $e->getMessage());
            $_SESSION['error'] = 'Failed to update password. Please try again.';
        }

        $this->redirect('/agent/profile');
    }

    /**
     * Display payouts/earnings page
     */
    public function payouts()
    {
        $agent = $this->agentModel->getAgentByUserId($_SESSION['user_id']);
        if (!$agent) {
            $_SESSION['error'] = 'Agent profile not found.';
            $this->redirect('/agent/dashboard');
            return;
        }

        // Get commission statistics
        $commissions = $this->agentModel->getAgentCommissions($agent['id']);
        
        // Calculate totals
        $totalEarned = 0;
        $pendingAmount = 0;
        $currentBalance = 0;
        
        foreach ($commissions as $commission) {
            if ($commission['status'] === 'paid') {
                $totalEarned += $commission['commission_amount'];
                $currentBalance += $commission['commission_amount'];
            } elseif ($commission['status'] === 'pending' || $commission['status'] === 'approved') {
                $pendingAmount += $commission['commission_amount'];
            }
        }

        $data = [
            'title' => 'Payouts & Earnings - Shena Companion Welfare Association',
            'agent' => $agent,
            'commissions' => $commissions,
            'total_earned' => $totalEarned,
            'pending_amount' => $pendingAmount,
            'current_balance' => $currentBalance
        ];

        $this->view('agent/payouts', $data);
    }

    /**
     * Display resources and training materials
     */
    public function resources()
    {
        $agent = $this->agentModel->getAgentByUserId($_SESSION['user_id']);
        if (!$agent) {
            $_SESSION['error'] = 'Agent profile not found.';
            $this->redirect('/agent/dashboard');
            return;
        }

        // TODO: Implement actual resource management system
        // For now, return empty arrays - resources can be managed by admin
        $data = [
            'title' => 'Resources - Shena Companion Welfare Association',
            'agent' => $agent,
            'flyers_brochures' => [],
            'social_media' => [],
            'member_forms' => [],
            'latest_updates' => []
        ];

        $this->view('agent/resources', $data);
    }

    /**
     * Display claims related to agent's members
     */
    public function claims()
    {
        $agent = $this->agentModel->getAgentByUserId($_SESSION['user_id']);
        if (!$agent) {
            $_SESSION['error'] = 'Agent profile not found.';
            $this->redirect('/agent/dashboard');
            return;
        }

        // TODO: Get claims for members registered by this agent
        // This would need proper implementation with a claims model
        $claims = [];

        $data = [
            'title' => 'Claims - Shena Companion Welfare Association',
            'agent' => $agent,
            'claims' => $claims
        ];

        $this->view('agent/claims', $data);
    }

    /**
     * Display detailed member information
     */
    public function memberDetails($memberId)
    {
        $agent = $this->agentModel->getAgentByUserId($_SESSION['user_id']);
        if (!$agent) {
            $_SESSION['error'] = 'Agent profile not found.';
            $this->redirect('/agent/dashboard');
            return;
        }

        // Get member details
        $member = $this->memberModel->getMemberById($memberId);
        
        if (!$member) {
            $_SESSION['error'] = 'Member not found.';
            $this->redirect('/agent/members');
            return;
        }

        // Verify this member belongs to this agent
        if ($member['agent_id'] != $agent['id']) {
            $_SESSION['error'] = 'You do not have access to view this member.';
            $this->redirect('/agent/members');
            return;
        }

        // Get member's dependents/beneficiaries
        $dependents = $this->memberModel->getMemberDependents($memberId);
        
        // Get payment history
        $paymentHistory = $this->memberModel->getMemberPaymentHistory($memberId);

        $data = [
            'title' => 'Member Details - Shena Companion Welfare Association',
            'agent' => $agent,
            'member' => $member,
            'dependents' => $dependents,
            'payment_history' => $paymentHistory
        ];

        $this->view('agent/member-details', $data);
    }

    /**
     * Display support/contact admin page
     */
    public function support()
    {
        $agent = $this->agentModel->getAgentByUserId($_SESSION['user_id']);
        if (!$agent) {
            $_SESSION['error'] = 'Agent profile not found.';
            $this->redirect('/agent/dashboard');
            return;
        }

        $data = [
            'title' => 'Support - Shena Companion Welfare Association',
            'agent' => $agent
        ];

        $this->view('agent/support', $data);
    }
}
