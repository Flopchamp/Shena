<?php
/**
 * Member Controller - Handles member dashboard and operations
 */
class MemberController extends BaseController 
{
    private $userModel;
    private $memberModel;
    private $paymentModel;
    private $beneficiaryModel;
    private $claimModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        
        $this->userModel = new User();
        $this->memberModel = new Member();
        $this->paymentModel = new Payment();
        $this->beneficiaryModel = new Beneficiary();
        $this->claimModel = new Claim();
    }
    
    public function dashboard()
    {
        $member = $this->memberModel->findByUserId($_SESSION['user_id']);
        
        if (!$member) {
            $_SESSION['error'] = 'Member profile not found.';
            $this->redirect('/login');
            return;
        }
        
        // Get recent payments
        $recentPayments = $this->paymentModel->getMemberPayments($member['id'], 5);

        // Get all payments for stats
        $allPayments = $this->paymentModel->getMemberPayments($member['id']);

        // Get beneficiaries
        $beneficiaries = $this->beneficiaryModel->getActiveBeneficiaries($member['id']);

        // Get recent claims
        $recentClaims = $this->claimModel->getMemberClaims($member['id']);

        // Check payment status for current month
        $currentYear = date('Y');
        $currentMonth = date('n');
        $currentMonthPayment = $this->paymentModel->getMonthlyPaymentStatus($member['id'], $currentYear, $currentMonth);

        // Dashboard stats
        $stats = [
            'total_payments' => is_array($allPayments) ? count(array_filter($allPayments, fn($p) => $p['status'] === 'completed')) : 0,
            'active_claims' => is_array($recentClaims) ? count(array_filter($recentClaims, fn($c) => isset($c['status']) && $c['status'] === 'active')) : 0
        ];

        $data = [
            'title' => 'Dashboard - Shena Companion Welfare Association',
            'member' => $member,
            'recent_payments' => $recentPayments,
            'beneficiaries' => $beneficiaries,
            'recent_claims' => $recentClaims,
            'current_month_paid' => !empty($currentMonthPayment),
            'current_month_payment' => $currentMonthPayment,
            'stats' => $stats
        ];

        $this->view('member.dashboard', $data);
    }
    
    public function profile()
    {
        $member = $this->memberModel->getMemberWithUser($_SESSION['user_id']);
        
        if (!$member) {
            $_SESSION['error'] = 'Member profile not found.';
            $this->redirect('/dashboard');
            return;
        }
        
        $data = [
            'title' => 'My Profile - Shena Companion Welfare Association',
            'member' => $member,
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('member.profile', $data);
    }
    
    public function updateProfile()
    {
        try {
            $this->validateCsrf();
            
            $member = $this->memberModel->findByUserId($_SESSION['user_id']);
            if (!$member) {
                $_SESSION['error'] = 'Member profile not found.';
                $this->redirect('/profile');
                return;
            }
            
            // Update user data
            $userData = [
                'first_name' => $this->sanitizeInput($_POST['first_name'] ?? ''),
                'last_name' => $this->sanitizeInput($_POST['last_name'] ?? ''),
                'phone' => $this->sanitizeInput($_POST['phone'] ?? '')
            ];
            
            // Update member data
            $memberData = [
                'address' => $this->sanitizeInput($_POST['address'] ?? ''),
                'next_of_kin' => $this->sanitizeInput($_POST['next_of_kin'] ?? ''),
                'next_of_kin_phone' => $this->sanitizeInput($_POST['next_of_kin_phone'] ?? '')
            ];
            
            // Validate phone
            if (!empty($userData['phone']) && !$this->validatePhone($userData['phone'])) {
                $_SESSION['error'] = 'Please enter a valid phone number.';
                $this->redirect('/profile');
                return;
            }
            
            // Update records
            try {
                $this->userModel->update($_SESSION['user_id'], $userData);
                $this->memberModel->update($member['id'], $memberData);
            } catch (Exception $e) {
                error_log('Database update error: ' . $e->getMessage());
                throw new Exception('Failed to update profile in database.');
            }
            
            $_SESSION['success'] = 'Profile updated successfully.';
            
        } catch (Exception $e) {
            error_log('Profile update error: ' . $e->getMessage());
            $_SESSION['error'] = 'Failed to update profile. Please try again.';
        }
        
        $this->redirect('/profile');
    }
    
    public function payments()
    {
        $member = $this->memberModel->findByUserId($_SESSION['user_id']);
        if (!$member) {
            $_SESSION['error'] = 'Member profile not found.';
            $this->redirect('/dashboard');
            return;
        }
        
        $payments = $this->paymentModel->getMemberPayments($member['id']);

        // Calculate total paid and pending count
        $total_paid = 0;
        $pending_count = 0;
        if (!empty($payments)) {
            foreach ($payments as $payment) {
                if ($payment['status'] === 'completed') {
                    $total_paid += (float)$payment['amount'];
                } elseif ($payment['status'] === 'pending') {
                    $pending_count++;
                }
            }
        }

        $data = [
            'title' => 'Payment History - Shena Companion Welfare Association',
            'member' => $member,
            'payments' => $payments,
            'total_paid' => $total_paid,
            'pending_count' => $pending_count
        ];

        $this->view('member.payments', $data);
    }
    
    public function beneficiaries()
    {
        $member = $this->memberModel->findByUserId($_SESSION['user_id']);
        if (!$member) {
            $_SESSION['error'] = 'Member profile not found.';
            $this->redirect('/dashboard');
            return;
        }
        
        $beneficiaries = $this->beneficiaryModel->getMemberBeneficiaries($member['id']);
        
        $data = [
            'title' => 'My Beneficiaries - Shena Companion Welfare Association',
            'member' => $member,
            'beneficiaries' => $beneficiaries,
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('member.beneficiaries', $data);
    }
    
    public function addBeneficiary()
    {
        error_log('addBeneficiary called');
        error_log('POST data: ' . print_r($_POST, true));
        
        try {
            $this->validateCsrf();
            error_log('CSRF validated');
            
            $member = $this->memberModel->findByUserId($_SESSION['user_id']);
            if (!$member) {
                error_log('Member not found');
                $_SESSION['error'] = 'Member profile not found.';
                $this->redirect('/beneficiaries');
                return;
            }
            
            error_log('Member found: ' . $member['id']);
            
            $beneficiaryData = [
                'member_id' => $member['id'],
                'full_name' => $this->sanitizeInput($_POST['full_name'] ?? ''),
                'relationship' => $this->sanitizeInput($_POST['relationship'] ?? ''),
                'id_number' => $this->sanitizeInput($_POST['id_number'] ?? ''),
                'phone_number' => $this->sanitizeInput($_POST['phone_number'] ?? ''),
                'percentage' => (float)($_POST['percentage'] ?? 100)
            ];
            
            error_log('Beneficiary data: ' . print_r($beneficiaryData, true));
            
            // Validate required fields
            if (empty($beneficiaryData['full_name']) || empty($beneficiaryData['relationship']) || 
                empty($beneficiaryData['id_number'])) {
                error_log('Validation failed: missing required fields');
                $_SESSION['error'] = 'Please fill in all required fields.';
                $this->redirect('/beneficiaries');
                return;
            }
            
            // Validate percentage
            if ($beneficiaryData['percentage'] <= 0 || $beneficiaryData['percentage'] > 100) {
                error_log('Validation failed: invalid percentage');
                $_SESSION['error'] = 'Percentage must be between 1 and 100.';
                $this->redirect('/beneficiaries');
                return;
            }
            
            // Check total percentage
            $currentTotal = $this->beneficiaryModel->validateBeneficiaryPercentages($member['id']);
            error_log('Current total percentage: ' . $currentTotal);
            
            if (($currentTotal + $beneficiaryData['percentage']) > 100) {
                error_log('Validation failed: percentage exceeds 100');
                $_SESSION['error'] = 'Total beneficiary percentage cannot exceed 100%.';
                $this->redirect('/beneficiaries');
                return;
            }
            
            $beneficiaryId = $this->beneficiaryModel->addBeneficiary($beneficiaryData);
            error_log('Beneficiary added with ID: ' . $beneficiaryId);
            
            $_SESSION['success'] = 'Beneficiary added successfully.';
            
        } catch (Exception $e) {
            error_log('Add beneficiary error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            $_SESSION['error'] = 'Failed to add beneficiary: ' . $e->getMessage();
        }
        
        $this->redirect('/beneficiaries');
    }
    
    public function claims()
    {
        $member = $this->memberModel->findByUserId($_SESSION['user_id']);
        if (!$member) {
            $_SESSION['error'] = 'Member profile not found.';
            $this->redirect('/dashboard');
            return;
        }
        
        $claims = $this->claimModel->getMemberClaims($member['id']);
        $beneficiaries = $this->beneficiaryModel->getActiveBeneficiaries($member['id']);
        
        $data = [
            'title' => 'Claims - Shena Companion Welfare Association',
            'member' => $member,
            'claims' => $claims,
            'beneficiaries' => $beneficiaries,
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('member.claims', $data);
    }
    
    public function submitClaim()
    {
        try {
            $this->validateCsrf();
            
            $member = $this->memberModel->findByUserId($_SESSION['user_id']);
            if (!$member) {
                $_SESSION['error'] = 'Member profile not found.';
                $this->redirect('/claims');
                return;
            }
            
            $claimData = [
                'member_id' => $member['id'],
                'beneficiary_id' => (int)($_POST['beneficiary_id'] ?? 0),
                'deceased_name' => $this->sanitizeInput($_POST['deceased_name'] ?? ''),
                'deceased_id_number' => $this->sanitizeInput($_POST['deceased_id_number'] ?? ''),
                'date_of_death' => $_POST['date_of_death'] ?? '',
                'place_of_death' => $this->sanitizeInput($_POST['place_of_death'] ?? ''),
                'cause_of_death' => $this->sanitizeInput($_POST['cause_of_death'] ?? ''),
                'mortuary_name' => $this->sanitizeInput($_POST['mortuary_name'] ?? ''),
                'mortuary_bill_amount' => (float)($_POST['mortuary_bill_amount'] ?? 0),
                'claim_amount' => (float)($_POST['claim_amount'] ?? 0),
                'notes' => $this->sanitizeInput($_POST['notes'] ?? '')
            ];
            
            // Validate required fields
            $required = ['beneficiary_id', 'deceased_name', 'deceased_id_number', 'date_of_death', 'place_of_death', 'claim_amount'];
            foreach ($required as $field) {
                if (empty($claimData[$field])) {
                    $_SESSION['error'] = 'Please fill in all required fields.';
                    $this->redirect('/claims');
                    return;
                }
            }
            
            // Validate beneficiary belongs to member
            $beneficiary = $this->beneficiaryModel->find($claimData['beneficiary_id']);
            if (!$beneficiary || $beneficiary['member_id'] != $member['id']) {
                $_SESSION['error'] = 'Invalid beneficiary selected.';
                $this->redirect('/claims');
                return;
            }
            
            $claimId = $this->claimModel->submitClaim($claimData);
            
            // Send notification email to admin
            $emailService = new EmailService();
            $emailService->sendClaimNotificationEmail($member, $claimData);
            
            $_SESSION['success'] = 'Claim submitted successfully. You will be notified of the status via email.';
            
        } catch (Exception $e) {
            error_log('Submit claim error: ' . $e->getMessage());
            $_SESSION['error'] = 'Failed to submit claim. Please try again.';
        }
        
        $this->redirect('/claims');
    }
    
    public function updateBeneficiary()
    {
        try {
            $this->validateCsrf();
            
            $member = $this->memberModel->findByUserId($_SESSION['user_id']);
            if (!$member) {
                $_SESSION['error'] = 'Member profile not found.';
                $this->redirect('/beneficiaries');
                return;
            }
            
            $beneficiaryId = (int)($_POST['beneficiary_id'] ?? 0);
            
            // Verify beneficiary belongs to member
            $beneficiary = $this->beneficiaryModel->find($beneficiaryId);
            if (!$beneficiary || $beneficiary['member_id'] != $member['id']) {
                $_SESSION['error'] = 'Unauthorized action.';
                $this->redirect('/beneficiaries');
                return;
            }
            
            $updateData = [
                'full_name' => $this->sanitizeInput($_POST['full_name'] ?? ''),
                'relationship' => $this->sanitizeInput($_POST['relationship'] ?? ''),
                'id_number' => $this->sanitizeInput($_POST['id_number'] ?? ''),
                'phone_number' => $this->sanitizeInput($_POST['phone_number'] ?? ''),
                'percentage' => (float)($_POST['percentage'] ?? 0)
            ];
            
            // Validate percentage
            $currentTotal = $this->beneficiaryModel->validateBeneficiaryPercentages($member['id'], $beneficiaryId);
            if (($currentTotal + $updateData['percentage']) > 100) {
                $_SESSION['error'] = 'Total beneficiary percentage cannot exceed 100%.';
                $this->redirect('/beneficiaries');
                return;
            }
            
            $this->beneficiaryModel->updateBeneficiary($beneficiaryId, $updateData);
            $_SESSION['success'] = 'Beneficiary updated successfully.';
            
        } catch (Exception $e) {
            error_log('Update beneficiary error: ' . $e->getMessage());
            $_SESSION['error'] = 'Failed to update beneficiary.';
        }
        
        $this->redirect('/beneficiaries');
    }
    
    public function deleteBeneficiary()
    {
        try {
            $member = $this->memberModel->findByUserId($_SESSION['user_id']);
            if (!$member) {
                $_SESSION['error'] = 'Member profile not found.';
                $this->redirect('/beneficiaries');
                return;
            }
            
            $beneficiaryId = (int)($_POST['beneficiary_id'] ?? 0);
            
            if (!$beneficiaryId) {
                $_SESSION['error'] = 'Invalid beneficiary.';
                $this->redirect('/beneficiaries');
                return;
            }
            
            // Verify beneficiary belongs to member
            $beneficiary = $this->beneficiaryModel->find($beneficiaryId);
            if (!$beneficiary || $beneficiary['member_id'] != $member['id']) {
                $_SESSION['error'] = 'Unauthorized action.';
                $this->redirect('/beneficiaries');
                return;
            }
            
            $this->beneficiaryModel->delete($beneficiaryId);
            $_SESSION['success'] = 'Beneficiary deleted successfully.';
            
        } catch (Exception $e) {
            error_log('Delete beneficiary error: ' . $e->getMessage());
            $_SESSION['error'] = 'Failed to delete beneficiary.';
        }
        
        $this->redirect('/beneficiaries');
    }
}


