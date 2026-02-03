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
                $member = $this->memberModel->find($memberId);
                
                if (!$member) {
                    $_SESSION['error'] = 'Member not found.';
                    $this->redirect('/admin/members');
                    return;
                }
                
                // Verify registration fee payment (KES 200) per policy Section 5
                $paymentModel = new Payment();
                $registrationFeeRequired = defined('REGISTRATION_FEE') ? REGISTRATION_FEE : 200;
                
                // Check if registration fee has been paid
                $registrationPayments = $paymentModel->findAll([
                    'member_id' => $memberId,
                    'payment_type' => 'registration',
                    'status' => 'completed'
                ]);
                
                $totalRegistrationPaid = 0;
                foreach ($registrationPayments as $payment) {
                    $totalRegistrationPaid += $payment['amount'];
                }
                
                if ($totalRegistrationPaid < $registrationFeeRequired) {
                    $outstanding = $registrationFeeRequired - $totalRegistrationPaid;
                    $_SESSION['error'] = "Cannot activate member. Registration fee of KES " . number_format($registrationFeeRequired) . " not paid. Outstanding: KES " . number_format($outstanding);
                    $this->redirect('/admin/members');
                    return;
                }
                
                // Update member status
                $this->memberModel->update($memberId, [
                    'status' => 'active',
                    'coverage_ends' => date('Y-m-d', strtotime('+1 year'))
                ]);
                
                // Update user status
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
     * View Completed Claims
     */
    public function viewCompletedClaims()
    {
        $this->requireAdminAccess();
        
        $data = [
            'title' => 'Completed Claims - Admin',
            'claims' => $this->claimModel->getAllClaimsWithDetails(['status' => 'completed'])
        ];
        
        $this->view('admin.claims-completed', $data);
    }

    /**
     * View Track Services - Overview of all claims being tracked
     */
    public function viewTrackServices()
    {
        $this->requireAdminAccess();
        
        // Get all approved claims that are in progress
        $data = [
            'title' => 'Track Services - Admin',
            'claims' => $this->claimModel->getAllClaimsWithDetails([
                'status' => 'approved'
            ])
        ];
        
        $this->view('admin/track-services', $data);
    }

    /**
     * Approve Claim for Standard Services (Default)
     */
    public function approveClaim()
    {
        $this->requireAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $claimId = $_POST['claim_id'] ?? 0;
            $deliveryDate = $_POST['services_delivery_date'] ?? date('Y-m-d');
            $notes = $_POST['notes'] ?? '';
            
            if ($claimId) {
                try {
                    $claim = $this->claimModel->find($claimId);
                    if (!$claim) {
                        throw new Exception('Claim not found.');
                    }

                    $member = $this->memberModel->find($claim['member_id']);
                    if (!$member) {
                        throw new Exception('Associated member not found.');
                    }

                    // Validate claim eligibility per policy Section 9
                    if ($member['status'] === 'defaulted') {
                        throw new Exception('Cannot approve claim. Member is in default status.');
                    }
                    
                    if ($member['status'] !== 'active') {
                        throw new Exception('Cannot approve claim. Member must be active.');
                    }
                    
                    // Check maturity period completion
                    if (!empty($member['maturity_ends'])) {
                        $maturityDate = new DateTime($member['maturity_ends']);
                        $today = new DateTime();
                        
                        if ($today < $maturityDate) {
                            throw new Exception('Cannot approve claim. Maturity period not completed.');
                        }
                    }
                    
                    // Check required documents per policy Section 8
                    $claimDocumentModel = new ClaimDocument();
                    $documents = $claimDocumentModel->getClaimDocuments($claimId);
                    $requiredDocs = ['id_copy', 'chief_letter', 'mortuary_invoice'];
                    $uploadedTypes = array_column($documents, 'document_type');
                    
                    foreach ($requiredDocs as $docType) {
                        if (!in_array($docType, $uploadedTypes)) {
                            throw new Exception("Required document missing: {$docType}");
                        }
                    }

                    // Approve for standard service delivery
                    $this->claimModel->approveClaimForServices($claimId, $deliveryDate, $notes);
                    
                    // Send notification to member
                    if (class_exists('EmailService')) {
                        try {
                            $emailService = new EmailService();
                            $emailService->sendClaimApprovalEmail($member, $claim);
                        } catch (Exception $e) {
                            error_log('Email notification failed: ' . $e->getMessage());
                        }
                    }
                    
                    $message = 'Claim approved for standard service delivery. Proceed to arrange services.';
                    
                    // Check if AJAX request
                    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => true, 'message' => $message]);
                        exit();
                    }
                    
                    $_SESSION['success'] = $message;
                } catch (Exception $e) {
                    error_log('Claim approval error: ' . $e->getMessage());
                    $errorMessage = 'Failed to approve claim: ' . $e->getMessage();
                    
                    // Check if AJAX request
                    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'message' => $errorMessage]);
                        exit();
                    }
                    
                    $_SESSION['error'] = $errorMessage;
                }
            }
        }
        
        $this->redirect('/admin/claims');
    }
    
    /**
     * Approve Claim for Cash Alternative (KSH 20,000)
     * Per Policy Section 12: Only in exceptional circumstances
     */
    public function approveClaimCashAlternative()
    {
        $this->requireAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $claimId = $_POST['claim_id'] ?? 0;
            $reason = $_POST['cash_alternative_reason'] ?? '';
            $requestedBy = $_POST['requested_by'] ?? 'company';
            
            if ($claimId) {
                try {
                    $claim = $this->claimModel->find($claimId);
                    if (!$claim) {
                        throw new Exception('Claim not found.');
                    }
                    
                    if (strlen($reason) < 20) {
                        throw new Exception('Detailed reason required (minimum 20 characters).');
                    }
                    
                    // Approve for cash alternative
                    $this->claimModel->approveClaimForCashAlternative(
                        $claimId,
                        $reason,
                        $requestedBy,
                        $_SESSION['user_id']
                    );
                    
                    $message = 'Claim approved for KSH 20,000 cash alternative. Agreement must be signed before payment.';
                    
                    // Check if AJAX request
                    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => true, 'message' => $message]);
                        exit();
                    }
                    
                    $_SESSION['success'] = $message;
                    
                } catch (Exception $e) {
                    error_log('Cash alternative approval error: ' . $e->getMessage());
                    $errorMessage = 'Failed to approve cash alternative: ' . $e->getMessage();
                    
                    // Check if AJAX request
                    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'message' => $errorMessage]);
                        exit();
                    }
                    
                    $_SESSION['error'] = $errorMessage;
                }
            }
        }
        
        $this->redirect('/admin/claims');
    }
    
    /**
     * Track Service Delivery for Approved Claims
     */
    public function trackServiceDelivery($claimId = null)
    {
        $this->requireAdminAccess();
        
        if (!$claimId && isset($_GET['claim_id'])) {
            $claimId = (int)$_GET['claim_id'];
        }
        
        if (!$claimId) {
            $_SESSION['error'] = 'Invalid claim ID.';
            $this->redirect('/admin/claims');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Update service completion status
            $serviceType = $_POST['service_type'] ?? '';
            $completed = isset($_POST['completed']) ? (bool)$_POST['completed'] : true;
            $serviceNotes = $_POST['service_notes'] ?? '';
            
            try {
                $checklistModel = new ClaimServiceChecklist();
                $checklistModel->markServiceCompleted($claimId, $serviceType, $_SESSION['user_id'], $serviceNotes);
                
                // Update main claim table
                $fieldMap = [
                    'mortuary_bill' => 'mortuary_bill_settled',
                    'body_dressing' => 'body_dressing_completed',
                    'coffin' => 'coffin_delivered',
                    'transportation' => 'transportation_arranged',
                    'equipment' => 'equipment_delivered'
                ];
                
                if (isset($fieldMap[$serviceType])) {
                    $this->claimModel->updateServiceDeliveryStatus($claimId, $fieldMap[$serviceType], $completed);
                }
                
                $_SESSION['success'] = 'Service delivery status updated.';
                
            } catch (Exception $e) {
                error_log('Service tracking error: ' . $e->getMessage());
                $_SESSION['error'] = 'Failed to update service status: ' . $e->getMessage();
            }
            
            $this->redirect('/admin/claims/track/' . $claimId);
            return;
        }
        
        // Load claim and service checklist
        $claim = $this->claimModel->getClaimDetails($claimId);
        if (!$claim) {
            $_SESSION['error'] = 'Claim not found.';
            $this->redirect('/admin/claims');
            return;
        }
        
        $checklistModel = new ClaimServiceChecklist();
        $checklist = $checklistModel->getClaimChecklist($claimId);
        $completionPercentage = $checklistModel->getCompletionPercentage($claimId);
        
        $data = [
            'title' => 'Track Service Delivery - Claim #' . $claimId,
            'claim' => $claim,
            'checklist' => $checklist,
            'completion_percentage' => $completionPercentage
        ];
        
        $this->view('admin.claims-track-services', $data);
    }
    
    /**
     * Complete Claim After All Services Delivered
     */
    public function completeClaim()
    {
        $this->requireAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $claimId = $_POST['claim_id'] ?? 0;
            $completionNotes = $_POST['completion_notes'] ?? '';
            
            if ($claimId) {
                try {
                    $this->claimModel->completeClaim($claimId, $completionNotes);
                    $_SESSION['success'] = 'Claim marked as completed successfully.';
                } catch (Exception $e) {
                    error_log('Complete claim error: ' . $e->getMessage());
                    $_SESSION['error'] = 'Failed to complete claim: ' . $e->getMessage();
                }
            }
        }
        
        $this->redirect('/admin/claims');
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
     * Export Report (PDF or Excel)
     */
    public function exportReport()
    {
        $this->requireAdminAccess();
        
        $format = $_GET['format'] ?? 'excel';
        $reportType = $_GET['type'] ?? 'overview';
        $startDate = $_GET['date_from'] ?? date('Y-m-01');
        $endDate = $_GET['date_to'] ?? date('Y-m-d');
        
        // Gather report data
        $data = [
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
        
        if ($format === 'pdf') {
            $this->exportPDF($data);
        } else {
            $this->exportExcel($data);
        }
    }
    
    /**
     * Export Report as PDF
     */
    private function exportPDF($data)
    {
        // Set headers for PDF download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="report_' . $data['reportType'] . '_' . date('Y-m-d') . '.pdf"');
        
        // For now, generate a simple PDF using HTML2PDF or similar
        // This is a basic implementation - you can enhance with libraries like TCPDF, FPDF, or Dompdf
        
        // Simple HTML content for PDF
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                h1 { color: #8B5CF6; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #8B5CF6; color: white; }
                .header { margin-bottom: 30px; }
                .stat { display: inline-block; margin: 10px; padding: 10px; background: #f0f0f0; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Shena Companion Welfare - ' . ucfirst($data['reportType']) . ' Report</h1>
                <p><strong>Period:</strong> ' . $data['startDate'] . ' to ' . $data['endDate'] . '</p>
                <p><strong>Generated:</strong> ' . date('Y-m-d H:i:s') . '</p>
            </div>
            
            <h2>Summary Statistics</h2>
            <div class="stat"><strong>Total Members:</strong> ' . $data['totalMembers'] . '</div>
            <div class="stat"><strong>Active Members:</strong> ' . $data['activeMembers'] . '</div>
            <div class="stat"><strong>Total Revenue:</strong> KES ' . number_format($data['totalRevenue'], 2) . '</div>
            <div class="stat"><strong>Total Claims Paid:</strong> KES ' . number_format($data['totalClaimsPaid'], 2) . '</div>';
        
        if ($data['reportType'] === 'members' && !empty($data['memberReports'])) {
            $html .= '<h2>Member Details</h2>
            <table>
                <tr>
                    <th>Member Number</th>
                    <th>Name</th>
                    <th>Package</th>
                    <th>Status</th>
                    <th>Registration Date</th>
                </tr>';
            foreach ($data['memberReports'] as $member) {
                $html .= '<tr>
                    <td>' . htmlspecialchars($member['member_number']) . '</td>
                    <td>' . htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) . '</td>
                    <td>' . htmlspecialchars($member['package']) . '</td>
                    <td>' . htmlspecialchars($member['status']) . '</td>
                    <td>' . date('Y-m-d', strtotime($member['created_at'])) . '</td>
                </tr>';
            }
            $html .= '</table>';
        }
        
        $html .= '</body></html>';
        
        // Output HTML that can be printed to PDF by the browser
        // User can use browser's "Print to PDF" feature
        header('Content-Type: text/html; charset=UTF-8');
        echo $html;
        echo '<script>window.print();</script>';
        exit;
    }
    
    /**
     * Export Report as Excel
     */
    private function exportExcel($data)
    {
        $filename = 'report_' . $data['reportType'] . '_' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Report Header
        fputcsv($output, ['Shena Companion Welfare - ' . ucfirst($data['reportType']) . ' Report']);
        fputcsv($output, ['Period:', $data['startDate'] . ' to ' . $data['endDate']]);
        fputcsv($output, ['Generated:', date('Y-m-d H:i:s')]);
        fputcsv($output, []);
        
        // Summary Statistics
        fputcsv($output, ['SUMMARY STATISTICS']);
        fputcsv($output, ['Metric', 'Value']);
        fputcsv($output, ['Total Members', $data['totalMembers']]);
        fputcsv($output, ['Active Members', $data['activeMembers']]);
        fputcsv($output, ['Inactive Members', $data['inactiveMembers']]);
        fputcsv($output, ['Pending Members', $data['pendingMembers']]);
        fputcsv($output, ['Total Revenue', 'KES ' . number_format($data['totalRevenue'], 2)]);
        fputcsv($output, ['Total Claims Paid', 'KES ' . number_format($data['totalClaimsPaid'], 2)]);
        fputcsv($output, ['New Members This Month', $data['newMembersThisMonth']]);
        fputcsv($output, ['Renewals Due', $data['renewalDue']]);
        fputcsv($output, ['Pending Payments', $data['pendingPayments']]);
        fputcsv($output, ['Failed Payments', $data['failedPayments']]);
        fputcsv($output, []);
        
        // Detailed data based on report type
        if ($data['reportType'] === 'members' && !empty($data['memberReports'])) {
            fputcsv($output, ['MEMBER DETAILS']);
            fputcsv($output, ['Member Number', 'First Name', 'Last Name', 'Package', 'Status', 'Registration Date']);
            
            foreach ($data['memberReports'] as $member) {
                fputcsv($output, [
                    $member['member_number'],
                    $member['first_name'],
                    $member['last_name'],
                    $member['package'],
                    $member['status'],
                    date('Y-m-d', strtotime($member['created_at']))
                ]);
            }
        } elseif ($data['reportType'] === 'payments' && !empty($data['paymentReports'])) {
            fputcsv($output, ['PAYMENT DETAILS']);
            fputcsv($output, ['Member Number', 'Amount', 'Method', 'Status', 'Date']);
            
            foreach ($data['paymentReports'] as $payment) {
                fputcsv($output, [
                    $payment['member_number'] ?? 'N/A',
                    'KES ' . number_format($payment['amount'], 2),
                    $payment['payment_method'] ?? 'N/A',
                    $payment['status'],
                    date('Y-m-d H:i', strtotime($payment['payment_date']))
                ]);
            }
        } elseif ($data['reportType'] === 'claims' && !empty($data['claimReports'])) {
            fputcsv($output, ['CLAIM DETAILS']);
            fputcsv($output, ['Claim Number', 'Member Number', 'Amount', 'Status', 'Date']);
            
            foreach ($data['claimReports'] as $claim) {
                fputcsv($output, [
                    $claim['claim_number'] ?? 'N/A',
                    $claim['member_number'] ?? 'N/A',
                    'KES ' . number_format($claim['claim_amount'], 2),
                    $claim['status'],
                    date('Y-m-d', strtotime($claim['created_at']))
                ]);
            }
        }
        
        fclose($output);
        exit;
    }

    /**
     * Communications Center with SMS Campaigns
     */
    public function communications()
    {
        $this->requireAdminAccess();
        
        $type = $_GET['type'] ?? 'all';
        $status = $_GET['status'] ?? 'all';
        
        // Get recent communications from the communications log
        $communications = $this->getRecentCommunications($type, $status);
        
        $data = [
            'title' => 'Communications Hub - Admin',
            'type' => $type,
            'status' => $status,
            'communications' => $communications
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
        try {
            switch ($criteria) {
                case 'all':
                    return $this->memberModel->getAllMembers();
                case 'active':
                    return $this->memberModel->getActiveMembersList();
                case 'inactive':
                    return $this->memberModel->getInactiveMembersList();
                case 'recent':
                    return $this->memberModel->getRecentMembers(30);
                default:
                    return [];
            }
        } catch (Exception $e) {
            error_log('Failed to get recipient list: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Log communication attempt
     */
    private function logCommunication($type, $recipients, $subject, $message, $count)
    {
        try {
            $db = Database::getInstance();
            // communications table schema:
            // sender_id, recipient_id (optional), recipient_type, recipient_criteria (JSON),
            // subject, message, type, status, sent_at
            $query = "INSERT INTO communications (sender_id, recipient_id, recipient_type, recipient_criteria, subject, message, type, status, sent_at) 
                      VALUES (:sender_id, NULL, :recipient_type, :recipient_criteria, :subject, :message, :type, 'sent', NOW())";

            $criteria = [
                'criteria' => $recipients,
                'estimated_recipient_count' => $count
            ];

            $db->execute($query, [
                'sender_id' => $_SESSION['user_id'] ?? null,
                'recipient_type' => $recipients,
                'recipient_criteria' => json_encode($criteria),
                'subject' => $subject,
                'message' => $message,
                'type' => $type
            ]);
        } catch (Exception $e) {
            error_log('Failed to log communication: ' . $e->getMessage());
        }
    }

    /**
     * Get recent communications
     */
    private function getRecentCommunications($type = 'all', $status = 'all')
    {
        try {
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
        } catch (Exception $e) {
            error_log('Failed to fetch communications: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Settings Management
     */
    /**
     * Settings Management
     */
    public function settings()
    {
        $this->requireAdminAccess();
        
        $settingsService = new SettingsService();
        $dbSettings = $settingsService->getAll();
        
        // Merge DB settings with defaults or constants if not in DB
        $settings = array_merge([
            'app_name' => defined('APP_NAME') ? APP_NAME : 'Shena Companion',
            'registration_fee' => 200,
            'reactivation_fee' => 100,
            'grace_period_under_80' => 4,
            'grace_period_80_and_above' => 5,
            'maturation_period_under_80' => 4, // Align logic naming
            'maturation_period_80_and_above' => 5,
        ], $dbSettings);
        
        // Ensure values are present via lookup if keys differ
        // Note: system_settings keys should be lower_snake_case
        if (!isset($settings['registration_fee'])) $settings['registration_fee'] = get_system_setting('registration_fee', 200);
        if (!isset($settings['reactivation_fee'])) $settings['reactivation_fee'] = get_system_setting('reactivation_fee', 100);

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

                $settingsService = new SettingsService();
                $editableSettings = [
                    'registration_fee', 
                    'reactivation_fee', 
                    'grace_period_under_80', 
                    'grace_period_80_and_above',
                    'app_name',
                    'admin_email'
                ];

                foreach ($editableSettings as $key) {
                    if (isset($_POST[$key])) {
                        $settingsService->set($key, $_POST[$key]);
                    }
                }
                
                $_SESSION['success'] = 'Settings updated successfully to database!';
                
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error updating settings: ' . $e->getMessage();
            }
        }
        
        $this->redirect('/admin/settings');
    }

    /**
     * M-Pesa Configuration Page
     */
    public function viewMpesaConfig()
    {
        $this->requireAdminAccess();
        
        $db = Database::getInstance()->getConnection();
        
        // Get current configuration
        $config = [];
        try {
            $stmt = $db->query("SELECT * FROM mpesa_config ORDER BY id DESC LIMIT 1");
            $config = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        } catch (Exception $e) {
            // Table doesn't exist yet, use empty config
            error_log("M-Pesa config table not found: " . $e->getMessage());
        }
        
        $data = [
            'title' => 'M-Pesa Configuration - ' . APP_NAME,
            'config' => $config
        ];
        
        $this->view('admin.mpesa-config', $data);
    }

    /**
     * Update M-Pesa Configuration
     */
    public function updateMpesaConfig()
    {
        $this->requireAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/mpesa-config');
            return;
        }
        
        // CSRF validation
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid request';
            $this->redirect('/admin/mpesa-config');
            return;
        }
        
        $db = Database::getInstance()->getConnection();
        
        $environment = $_POST['environment'] ?? 'sandbox';
        $consumerKey = $_POST['consumer_key'] ?? '';
        $consumerSecret = $_POST['consumer_secret'] ?? '';
        $shortCode = $_POST['short_code'] ?? '';
        $passKey = $_POST['pass_key'] ?? '';
        $callbackUrl = $_POST['callback_url'] ?? '';
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        
        try {
            // Check if mpesa_config table exists, if not create it
            $stmt = $db->query("SHOW TABLES LIKE 'mpesa_config'");
            if (!$stmt->fetch()) {
                // Create table if it doesn't exist
                $db->exec("
                    CREATE TABLE mpesa_config (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        environment VARCHAR(20) DEFAULT 'sandbox',
                        consumer_key VARCHAR(255) NOT NULL,
                        consumer_secret VARCHAR(255) NOT NULL,
                        short_code VARCHAR(20) NOT NULL,
                        pass_key VARCHAR(255) NOT NULL,
                        callback_url VARCHAR(255),
                        is_active TINYINT(1) DEFAULT 1,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                    )
                ");
            }
            
            // Check if configuration exists
            $stmt = $db->query("SELECT id FROM mpesa_config LIMIT 1");
            $exists = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($exists) {
                // Update existing
                $stmt = $db->prepare("
                    UPDATE mpesa_config SET
                        environment = ?,
                        consumer_key = ?,
                        consumer_secret = ?,
                        short_code = ?,
                        pass_key = ?,
                        callback_url = ?,
                        is_active = ?,
                        updated_at = NOW()
                    WHERE id = ?
                ");
                $stmt->execute([
                    $environment, $consumerKey, $consumerSecret, 
                    $shortCode, $passKey, $callbackUrl, $isActive, $exists['id']
                ]);
            } else {
                // Insert new
                $stmt = $db->prepare("
                    INSERT INTO mpesa_config (
                        environment, consumer_key, consumer_secret, 
                        short_code, pass_key, callback_url, is_active
                    ) VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $environment, $consumerKey, $consumerSecret, 
                    $shortCode, $passKey, $callbackUrl, $isActive
                ]);
            }
            
            $_SESSION['success'] = 'M-Pesa configuration updated successfully';
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error updating configuration: ' . $e->getMessage();
        }
        
        $this->redirect('/admin/mpesa-config');
    }

    /**
     * Plan Upgrades Management Page
     */
    public function viewPlanUpgrades()
    {
        $this->requireAdminAccess();
        
        $db = Database::getInstance()->getConnection();
        
        // Get statistics
        $stats = [
            'pending' => 0,
            'completed' => 0,
            'cancelled' => 0,
            'total_revenue' => 0
        ];
        
        $stmt = $db->query("
            SELECT 
                status,
                COUNT(*) as count,
                SUM(prorated_amount) as total
            FROM plan_upgrade_requests
            GROUP BY status
        ");
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $stats[$row['status']] = $row['count'];
            if ($row['status'] === 'completed') {
                $stats['total_revenue'] = $row['total'];
            }
        }
        
        // Build filter query
        $where = ['1=1'];
        $params = [];
        
        if (!empty($_GET['status'])) {
            $where[] = 'pur.status = ?';
            $params[] = $_GET['status'];
        }
        
        if (!empty($_GET['from_date'])) {
            $where[] = 'DATE(pur.requested_at) >= ?';
            $params[] = $_GET['from_date'];
        }
        
        if (!empty($_GET['to_date'])) {
            $where[] = 'DATE(pur.requested_at) <= ?';
            $params[] = $_GET['to_date'];
        }
        
        // Get upgrade requests
        $sql = "
            SELECT 
                pur.*,
                u.first_name, u.last_name, m.member_number,
                CONCAT(u.first_name, ' ', u.last_name) as member_name
            FROM plan_upgrade_requests pur
            JOIN members m ON pur.member_id = m.id
            JOIN users u ON m.user_id = u.id
            WHERE " . implode(' AND ', $where) . "
            ORDER BY pur.requested_at DESC
            LIMIT 100
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $upgrades = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $data = [
            'title' => 'Plan Upgrade Management - ' . APP_NAME,
            'stats' => $stats,
            'upgrades' => $upgrades
        ];
        
        $this->view('admin.plan-upgrades', $data);
    }

    /**
     * Complete Plan Upgrade
     */
    public function completePlanUpgrade($id)
    {
        $this->requireAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/plan-upgrades');
            return;
        }
        
        $db = Database::getInstance()->getConnection();
        
        try {
            $db->beginTransaction();
            
            // Get upgrade request
            $stmt = $db->prepare("
                SELECT * FROM plan_upgrade_requests 
                WHERE id = ? AND status = 'pending' AND payment_status = 'completed'
            ");
            $stmt->execute([$id]);
            $upgrade = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$upgrade) {
                throw new Exception('Upgrade request not found or not eligible for completion');
            }
            
            // Update member package
            $stmt = $db->prepare("UPDATE members SET package = ? WHERE id = ?");
            $stmt->execute([$upgrade['to_package'], $upgrade['member_id']]);
            
            // Update upgrade status
            $stmt = $db->prepare("
                UPDATE plan_upgrade_requests 
                SET status = 'completed', processed_at = NOW(), processed_by = ?
                WHERE id = ?
            ");
            $stmt->execute([$_SESSION['user_id'], $id]);
            
            // Insert into upgrade history
            $stmt = $db->prepare("
                INSERT INTO plan_upgrade_history (
                    member_id, from_package, to_package, 
                    amount, upgrade_date, processed_by
                ) VALUES (?, ?, ?, ?, NOW(), ?)
            ");
            $stmt->execute([
                $upgrade['member_id'],
                $upgrade['from_package'],
                $upgrade['to_package'],
                $upgrade['prorated_amount'],
                $_SESSION['user_id']
            ]);
            
            $db->commit();
            $_SESSION['success'] = 'Upgrade completed successfully';
            
        } catch (Exception $e) {
            $db->rollBack();
            $_SESSION['error'] = 'Error completing upgrade: ' . $e->getMessage();
        }
        
        $this->redirect('/admin/plan-upgrades');
    }

    /**
     * Cancel Plan Upgrade and Refund
     */
    public function cancelPlanUpgrade($id)
    {
        $this->requireAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/plan-upgrades');
            return;
        }
        
        $db = Database::getInstance()->getConnection();
        
        try {
            $db->beginTransaction();
            
            // Get upgrade request
            $stmt = $db->prepare("SELECT * FROM plan_upgrade_requests WHERE id = ?");
            $stmt->execute([$id]);
            $upgrade = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$upgrade) {
                throw new Exception('Upgrade request not found');
            }
            
            // Update upgrade status
            $stmt = $db->prepare("
                UPDATE plan_upgrade_requests 
                SET status = 'cancelled', processed_at = NOW(), processed_by = ?
                WHERE id = ?
            ");
            $stmt->execute([$_SESSION['user_id'], $id]);
            
            // Record refund transaction if payment was completed
            if ($upgrade['payment_status'] === 'completed') {
                $stmt = $db->prepare("
                    INSERT INTO financial_transactions (
                        transaction_type, amount, member_id, 
                        upgrade_request_id, status, description
                    ) VALUES (
                        'refund', ?, ?, ?, 'completed', 
                        'Refund for cancelled plan upgrade'
                    )
                ");
                $stmt->execute([
                    $upgrade['prorated_amount'],
                    $upgrade['member_id'],
                    $id
                ]);
            }
            
            $db->commit();
            $_SESSION['success'] = 'Upgrade cancelled and refund processed';
            
        } catch (Exception $e) {
            $db->rollBack();
            $_SESSION['error'] = 'Error cancelling upgrade: ' . $e->getMessage();
        }
        
        $this->redirect('/admin/plan-upgrades');
    }

    /**
     * Financial Dashboard
     */
    public function viewFinancialDashboard()
    {
        $this->requireAdminAccess();
        
        $db = Database::getInstance()->getConnection();
        
        // Date range from filters or default to current month
        $fromDate = $_GET['from_date'] ?? date('Y-m-01');
        $toDate = $_GET['to_date'] ?? date('Y-m-d');
        
        // Initialize default empty data
        $kpis = [
            'total_payments' => 0,
            'total_commissions' => 0,
            'total_upgrades' => 0,
            'total_refunds' => 0,
            'paying_members' => 0,
            'earning_agents' => 0,
            'net_revenue' => 0,
            'revenue_change' => 0,
            'total_revenue' => 0
        ];
        $monthlySummary = [];
        $topAgents = [];
        $recentTransactions = [];
        
        try {
            // Get KPIs
            $stmt = $db->prepare("
                SELECT 
                    SUM(CASE WHEN transaction_type = 'payment' THEN amount ELSE 0 END) as total_payments,
                    SUM(CASE WHEN transaction_type = 'commission' THEN amount ELSE 0 END) as total_commissions,
                    SUM(CASE WHEN transaction_type = 'upgrade' THEN amount ELSE 0 END) as total_upgrades,
                    SUM(CASE WHEN transaction_type = 'refund' THEN amount ELSE 0 END) as total_refunds,
                    COUNT(DISTINCT CASE WHEN transaction_type = 'payment' THEN member_id END) as paying_members,
                    COUNT(DISTINCT CASE WHEN transaction_type = 'commission' THEN agent_id END) as earning_agents
                FROM financial_transactions
                WHERE DATE(transaction_date) BETWEEN ? AND ?
                AND status = 'completed'
            ");
            $stmt->execute([$fromDate, $toDate]);
            $fetchedKpis = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($fetchedKpis) {
                $kpis = $fetchedKpis;
                $kpis['net_revenue'] = ($kpis['total_payments'] + $kpis['total_upgrades']) - 
                                       ($kpis['total_commissions'] + $kpis['total_refunds']);
                $kpis['revenue_change'] = 0; // Calculate vs previous period if needed
                $kpis['total_revenue'] = $kpis['total_payments'] + $kpis['total_upgrades'];
            }
            
            // Get monthly summary
            $stmt = $db->query("
                SELECT * FROM vw_financial_summary
                ORDER BY month DESC
                LIMIT 12
            ");
            $monthlySummary = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get top agents
            $stmt = $db->query("
                SELECT * FROM vw_agent_leaderboard
                LIMIT 10
            ");
            $topAgents = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get recent transactions
            $stmt = $db->prepare("
                SELECT 
                    ft.*,
                    m.member_number,
                    m.package
                FROM financial_transactions ft
                LEFT JOIN members m ON ft.member_id = m.id
                WHERE DATE(ft.transaction_date) BETWEEN ? AND ?
                ORDER BY ft.transaction_date DESC
                LIMIT 20
            ");
            $stmt->execute([$fromDate, $toDate]);
            $recentTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            // Tables don't exist yet, use empty data
            error_log("Financial dashboard tables not found: " . $e->getMessage());
        }
        
        $data = [
            'title' => 'Financial Dashboard - ' . APP_NAME,
            'kpis' => $kpis,
            'monthly_summary' => $monthlySummary,
            'top_agents' => $topAgents,
            'recent_transactions' => $recentTransactions,
            'from_date' => $fromDate,
            'to_date' => $toDate
        ];
        
        $this->view('admin/financial-dashboard', $data);
    }

    /**
     * Export Financial Report
     */
    public function exportFinancialReport()
    {
        $this->requireAdminAccess();
        
        $db = Database::getInstance()->getConnection();
        
        // Date range from filters or default to current month
        $fromDate = $_GET['from_date'] ?? date('Y-m-01');
        $toDate = $_GET['to_date'] ?? date('Y-m-d');
        
        // Initialize default empty data
        $kpis = [
            'total_payments' => 0,
            'total_commissions' => 0,
            'total_upgrades' => 0,
            'total_refunds' => 0,
            'paying_members' => 0,
            'earning_agents' => 0,
            'net_revenue' => 0,
            'total_revenue' => 0
        ];
        $monthlySummary = [];
        $recentTransactions = [];
        
        try {
            // Get KPIs
            $stmt = $db->prepare("
                SELECT 
                    SUM(CASE WHEN transaction_type = 'payment' THEN amount ELSE 0 END) as total_payments,
                    SUM(CASE WHEN transaction_type = 'commission' THEN amount ELSE 0 END) as total_commissions,
                    SUM(CASE WHEN transaction_type = 'upgrade' THEN amount ELSE 0 END) as total_upgrades,
                    SUM(CASE WHEN transaction_type = 'refund' THEN amount ELSE 0 END) as total_refunds,
                    COUNT(DISTINCT CASE WHEN transaction_type = 'payment' THEN member_id END) as paying_members,
                    COUNT(DISTINCT CASE WHEN transaction_type = 'commission' THEN agent_id END) as earning_agents
                FROM financial_transactions
                WHERE DATE(transaction_date) BETWEEN ? AND ?
                AND status = 'completed'
            ");
            $stmt->execute([$fromDate, $toDate]);
            $fetchedKpis = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($fetchedKpis) {
                $kpis = $fetchedKpis;
                $kpis['net_revenue'] = ($kpis['total_payments'] + $kpis['total_upgrades']) - 
                                       ($kpis['total_commissions'] + $kpis['total_refunds']);
                $kpis['total_revenue'] = $kpis['total_payments'] + $kpis['total_upgrades'];
            }
            
            // Get monthly summary
            $stmt = $db->query("
                SELECT * FROM vw_financial_summary
                ORDER BY month DESC
                LIMIT 12
            ");
            $monthlySummary = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get all transactions in the period
            $stmt = $db->prepare("
                SELECT 
                    ft.*,
                    m.member_number,
                    m.package
                FROM financial_transactions ft
                LEFT JOIN members m ON ft.member_id = m.id
                WHERE DATE(ft.transaction_date) BETWEEN ? AND ?
                ORDER BY ft.transaction_date DESC
            ");
            $stmt->execute([$fromDate, $toDate]);
            $recentTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            // Tables don't exist yet, continue with empty data
            error_log("Financial export tables not found: " . $e->getMessage());
        }
        
        // Generate CSV
        $filename = 'financial_report_' . $fromDate . '_to_' . $toDate . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Report Header
        fputcsv($output, ['Financial Report']);
        fputcsv($output, ['Period:', $fromDate . ' to ' . $toDate]);
        fputcsv($output, ['Generated:', date('Y-m-d H:i:s')]);
        fputcsv($output, []);
        
        // KPIs Summary
        fputcsv($output, ['KEY PERFORMANCE INDICATORS']);
        fputcsv($output, ['Metric', 'Value']);
        fputcsv($output, ['Total Revenue', 'KES ' . number_format($kpis['total_revenue'], 2)]);
        fputcsv($output, ['Total Payments', 'KES ' . number_format($kpis['total_payments'], 2)]);
        fputcsv($output, ['Total Commissions', 'KES ' . number_format($kpis['total_commissions'], 2)]);
        fputcsv($output, ['Total Upgrades', 'KES ' . number_format($kpis['total_upgrades'], 2)]);
        fputcsv($output, ['Total Refunds', 'KES ' . number_format($kpis['total_refunds'], 2)]);
        fputcsv($output, ['Net Revenue', 'KES ' . number_format($kpis['net_revenue'], 2)]);
        fputcsv($output, ['Paying Members', $kpis['paying_members']]);
        fputcsv($output, ['Earning Agents', $kpis['earning_agents']]);
        fputcsv($output, []);
        
        // Transactions
        if (!empty($recentTransactions)) {
            fputcsv($output, ['TRANSACTIONS']);
            fputcsv($output, ['Date', 'Type', 'Member Number', 'Package', 'Amount', 'Status']);
            
            foreach ($recentTransactions as $txn) {
                fputcsv($output, [
                    date('Y-m-d H:i', strtotime($txn['transaction_date'])),
                    ucfirst($txn['transaction_type']),
                    $txn['member_number'] ?? 'N/A',
                    $txn['package'] ?? 'N/A',
                    'KES ' . number_format($txn['amount'], 2),
                    ucfirst($txn['status'])
                ]);
            }
            fputcsv($output, []);
        }
        
        // Monthly Summary
        if (!empty($monthlySummary)) {
            fputcsv($output, ['MONTHLY SUMMARY']);
            fputcsv($output, ['Month', 'Payments', 'Commissions', 'Upgrades', 'Refunds', 'Net Revenue', 'Members']);
            
            foreach ($monthlySummary as $month) {
                $net = $month['total_payments'] + $month['total_upgrades'] - 
                       $month['total_commissions'] - $month['total_refunds'];
                       
                fputcsv($output, [
                    date('F Y', strtotime($month['month'] . '-01')),
                    'KES ' . number_format($month['total_payments'], 2),
                    'KES ' . number_format($month['total_commissions'], 2),
                    'KES ' . number_format($month['total_upgrades'], 2),
                    'KES ' . number_format($month['total_refunds'], 2),
                    'KES ' . number_format($net, 2),
                    $month['paying_members']
                ]);
            }
        }
        
        fclose($output);
        exit;
    }
}
?>
