<?php
/**
 * Agent Controller
 * Handles agent management, registration, and commission operations
 * 
 * @package Shena\Controllers
 */

require_once __DIR__ . '/../models/Agent.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../services/EmailService.php';

class AgentController extends BaseController
{
    private $agentModel;
    private $userModel;
    private $emailService;
    
    public function __construct()
    {
        parent::__construct();
        $this->agentModel = new Agent();
        $this->userModel = new User();
        $this->emailService = new EmailService();
    }
    
    /**
     * Display agent management dashboard (admin only)
     */
    public function index()
    {
        $this->requireRole(['admin', 'super_admin']);
        
        $filters = [
            'status' => $_GET['status'] ?? '',
            'search' => $_GET['search'] ?? ''
        ];
        
        $agents = $this->agentModel->getAllAgents($filters);
        
        $this->render('admin/agents', [
            'agents' => $agents,
            'filters' => $filters,
            'pageTitle' => 'Agent Management'
        ]);
    }
    
    /**
     * Display agent details
     */
    public function show($agentId)
    {
        $this->requireRole(['admin', 'super_admin']);
        
        $agent = $this->agentModel->getAgentById($agentId);
        
        if (!$agent) {
            $this->setFlashMessage('Agent not found', 'error');
            redirect('/admin/agents');
            return;
        }
        
        $stats = $this->agentModel->getAgentDashboardStats($agentId);
        $commissions = $this->agentModel->getAgentCommissions($agentId);
        
        $this->render('admin/agent-details', [
            'agent' => $agent,
            'stats' => $stats,
            'commissions' => $commissions,
            'pageTitle' => 'Agent Details - ' . $agent['agent_number']
        ]);
    }
    
    /**
     * Display agent registration form
     */
    public function create()
    {
        $this->requireRole(['admin', 'super_admin']);
        
        $this->render('admin/agent-create', [
            'pageTitle' => 'Register New Agent'
        ]);
    }
    
    /**
     * Process agent registration
     */
    public function store()
    {
        $this->requireRole(['admin', 'super_admin']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/agents/create');
            return;
        }
        
        // Validate input
        $errors = $this->validateAgentData($_POST);
        
        if (!empty($errors)) {
            $_SESSION['old_input'] = $_POST;
            $_SESSION['errors'] = $errors;
            redirect('/admin/agents/create');
            return;
        }
        
        // Check if agent already exists
        if ($this->agentModel->existsByNationalId($_POST['national_id'])) {
            $this->setFlashMessage('An agent with this National ID already exists', 'error');
            redirect('/admin/agents/create');
            return;
        }
        
        // Create user account
        $userData = [
            'username' => $_POST['email'],
            'email' => $_POST['email'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'role' => 'agent'
        ];
        
        $userId = $this->userModel->create($userData);
        
        if (!$userId) {
            $this->setFlashMessage('Failed to create user account', 'error');
            redirect('/admin/agents/create');
            return;
        }
        
        // Create agent profile
        $agentData = [
            'user_id' => $userId,
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'national_id' => $_POST['national_id'],
            'phone' => $_POST['phone'],
            'email' => $_POST['email'],
            'address' => $_POST['address'] ?? '',
            'county' => $_POST['county'] ?? '',
            'commission_rate' => $_POST['commission_rate'] ?? 10.00
        ];
        
        $agentId = $this->agentModel->createAgent($agentData);
        
        if ($agentId) {
            // Send welcome email
            $agent = $this->agentModel->getAgentById($agentId);
            $this->emailService->sendAgentWelcomeEmail($agent, $_POST['password']);
            
            $this->setFlashMessage('Agent registered successfully', 'success');
            redirect('/admin/agents/view/' . $agentId);
        } else {
            $this->setFlashMessage('Failed to create agent profile', 'error');
            redirect('/admin/agents/create');
        }
    }
    
    /**
     * Display agent edit form
     */
    public function edit($agentId)
    {
        $this->requireRole(['admin', 'super_admin']);
        
        $agent = $this->agentModel->getAgentById($agentId);
        
        if (!$agent) {
            $this->setFlashMessage('Agent not found', 'error');
            redirect('/admin/agents');
            return;
        }
        
        $this->render('admin/agent-edit', [
            'agent' => $agent,
            'pageTitle' => 'Edit Agent - ' . $agent['agent_number']
        ]);
    }
    
    /**
     * Process agent update
     */
    public function update($agentId)
    {
        $this->requireRole(['admin', 'super_admin']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/agents/edit/' . $agentId);
            return;
        }
        
        $agent = $this->agentModel->getAgentById($agentId);
        
        if (!$agent) {
            $this->setFlashMessage('Agent not found', 'error');
            redirect('/admin/agents');
            return;
        }
        
        $updateData = [
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'phone' => $_POST['phone'],
            'email' => $_POST['email'],
            'address' => $_POST['address'] ?? '',
            'county' => $_POST['county'] ?? '',
            'commission_rate' => $_POST['commission_rate'] ?? 10.00
        ];
        
        if ($this->agentModel->updateAgent($agentId, $updateData)) {
            $this->setFlashMessage('Agent updated successfully', 'success');
        } else {
            $this->setFlashMessage('Failed to update agent', 'error');
        }
        
        redirect('/admin/agents/view/' . $agentId);
    }
    
    /**
     * Update agent status (activate, suspend, deactivate)
     */
    public function updateStatus($agentId)
    {
        $this->requireRole(['admin', 'super_admin']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/agents');
            return;
        }
        
        $status = $_POST['status'] ?? '';
        $allowedStatuses = ['active', 'suspended', 'inactive'];
        
        if (!in_array($status, $allowedStatuses)) {
            $this->setFlashMessage('Invalid status', 'error');
            redirect('/admin/agents/view/' . $agentId);
            return;
        }
        
        if ($this->agentModel->updateStatus($agentId, $status)) {
            $this->setFlashMessage('Agent status updated successfully', 'success');
        } else {
            $this->setFlashMessage('Failed to update agent status', 'error');
        }
        
        redirect('/admin/agents/view/' . $agentId);
    }
    
    /**
     * Display commission management page
     */
    public function commissions()
    {
        $this->requireRole(['admin', 'super_admin']);
        
        $pendingCommissions = $this->agentModel->getPendingCommissions();
        
        $this->render('admin/commissions', [
            'commissions' => $pendingCommissions,
            'pageTitle' => 'Commission Management'
        ]);
    }
    
    /**
     * Approve commission payment
     */
    public function approveCommission($commissionId)
    {
        $this->requireRole(['admin', 'super_admin']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/commissions');
            return;
        }
        
        $userId = $_SESSION['user_id'];
        
        if ($this->agentModel->approveCommission($commissionId, $userId)) {
            $this->setFlashMessage('Commission approved successfully', 'success');
        } else {
            $this->setFlashMessage('Failed to approve commission', 'error');
        }
        
        redirect('/admin/commissions');
    }
    
    /**
     * Mark commission as paid
     */
    public function markCommissionPaid($commissionId)
    {
        $this->requireRole(['admin', 'super_admin']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/commissions');
            return;
        }
        
        $paymentMethod = $_POST['payment_method'] ?? '';
        $paymentReference = $_POST['payment_reference'] ?? '';
        
        if (empty($paymentMethod) || empty($paymentReference)) {
            $this->setFlashMessage('Payment method and reference are required', 'error');
            redirect('/admin/commissions');
            return;
        }
        
        if ($this->agentModel->markCommissionPaid($commissionId, $paymentMethod, $paymentReference)) {
            $this->setFlashMessage('Commission marked as paid', 'success');
        } else {
            $this->setFlashMessage('Failed to update commission', 'error');
        }
        
        redirect('/admin/commissions');
    }
    
    /**
     * Validate agent registration data
     * 
     * @param array $data Form data
     * @return array Validation errors
     */
    private function validateAgentData($data)
    {
        $errors = [];
        
        if (empty($data['first_name'])) {
            $errors['first_name'] = 'First name is required';
        }
        
        if (empty($data['last_name'])) {
            $errors['last_name'] = 'Last name is required';
        }
        
        if (empty($data['national_id'])) {
            $errors['national_id'] = 'National ID is required';
        }
        
        if (empty($data['phone'])) {
            $errors['phone'] = 'Phone number is required';
        } elseif (!preg_match('/^(\+254|0)[17]\d{8}$/', $data['phone'])) {
            $errors['phone'] = 'Invalid phone number format';
        }
        
        if (empty($data['email'])) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        } elseif ($this->userModel->findByEmail($data['email'])) {
            $errors['email'] = 'Email already exists';
        }
        
        if (empty($data['password'])) {
            $errors['password'] = 'Password is required';
        } elseif (strlen($data['password']) < 8) {
            $errors['password'] = 'Password must be at least 8 characters';
        }
        
        if (isset($data['commission_rate'])) {
            if (!is_numeric($data['commission_rate']) || $data['commission_rate'] < 0 || $data['commission_rate'] > 100) {
                $errors['commission_rate'] = 'Commission rate must be between 0 and 100';
            }
        }
        
        return $errors;
    }
}
