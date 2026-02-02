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
        $member = $this->memberModel->findByUserId($_SESSION['user_id']);

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
    
    /**
     * Verify pending/failed M-Pesa transaction
     */
    public function verifyTransaction()
    {
        header('Content-Type: application/json');
        
        try {
            $member = $this->memberModel->findByUserId($_SESSION['user_id']);
            if (!$member) {
                $this->json(['success' => false, 'message' => 'Member not found'], 404);
                return;
            }
            
            $transactionCode = $_POST['transaction_code'] ?? '';
            $phoneNumber = $_POST['phone_number'] ?? '';
            
            // Validate inputs
            if (empty($transactionCode)) {
                $this->json(['success' => false, 'message' => 'Please enter M-Pesa transaction code'], 400);
                return;
            }
            
            if (empty($phoneNumber)) {
                $this->json(['success' => false, 'message' => 'Please enter your phone number'], 400);
                return;
            }
            
            // Format phone number
            $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
            if (strlen($phoneNumber) === 10 && substr($phoneNumber, 0, 1) === '0') {
                $phoneNumber = '254' . substr($phoneNumber, 1);
            }
            
            // Format transaction code (remove spaces, uppercase)
            $transactionCode = strtoupper(preg_replace('/\s+/', '', $transactionCode));
            
            // Search for payment record for this member
            $sql = "SELECT * FROM payments 
                    WHERE member_id = :member_id
                    AND (mpesa_receipt_number = :code OR transaction_reference LIKE :code_pattern)
                    AND phone_number LIKE :phone
                    AND status IN ('pending', 'failed', 'initiated')
                    ORDER BY created_at DESC 
                    LIMIT 1";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([
                ':member_id' => $member['id'],
                ':code' => $transactionCode,
                ':code_pattern' => '%' . $transactionCode . '%',
                ':phone' => '%' . substr($phoneNumber, -9) . '%'
            ]);
            
            $payment = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$payment) {
                // Try alternative search - by phone and recent pending payments for this member
                $sql = "SELECT * FROM payments 
                        WHERE member_id = :member_id
                        AND phone_number LIKE :phone
                        AND status IN ('pending', 'failed', 'initiated')
                        AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                        ORDER BY created_at DESC 
                        LIMIT 1";
                
                $stmt = $this->db->getConnection()->prepare($sql);
                $stmt->execute([
                    ':member_id' => $member['id'],
                    ':phone' => '%' . substr($phoneNumber, -9) . '%'
                ]);
                $payment = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            
            if (!$payment) {
                $this->json([
                    'success' => false, 
                    'message' => 'No matching pending payment found. Please verify your transaction code and phone number.'
                ], 404);
                return;
            }
            
            // Update payment status
            $this->db->getConnection()->beginTransaction();
            
            try {
                // Update payment record
                $updatePayment = "UPDATE payments SET 
                                status = 'completed',
                                mpesa_receipt_number = :receipt,
                                transaction_date = NOW(),
                                verified_at = NOW(),
                                verified_by = 'manual_verification'
                              WHERE id = :id";
                
                $stmt = $this->db->getConnection()->prepare($updatePayment);
                $stmt->execute([
                    ':receipt' => $transactionCode,
                    ':id' => $payment['id']
                ]);
                
                // Update member last payment date
                $updateMember = "UPDATE members SET 
                               last_payment_date = NOW()
                             WHERE id = :id";
                
                $stmt = $this->db->getConnection()->prepare($updateMember);
                $stmt->execute([':id' => $member['id']]);
                
                $this->db->getConnection()->commit();
                
                $this->json([
                    'success' => true,
                    'message' => 'Payment verified successfully! Your account has been updated.',
                    'amount' => $payment['amount']
                ]);
                
            } catch (Exception $e) {
                $this->db->getConnection()->rollBack();
                error_log('Transaction verification error: ' . $e->getMessage());
                $this->json(['success' => false, 'message' => 'Failed to verify payment'], 500);
            }
            
        } catch (Exception $e) {
            error_log('Verify transaction error: ' . $e->getMessage());
            $this->json(['success' => false, 'message' => 'An error occurred'], 500);
        }
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
            
            // Service-based claim data per SHENA Policy 2026
            $claimData = [
                'member_id' => $member['id'],
                'beneficiary_id' => (int)($_POST['beneficiary_id'] ?? 0),
                'deceased_name' => $this->sanitizeInput($_POST['deceased_name'] ?? ''),
                'deceased_id_number' => $this->sanitizeInput($_POST['deceased_id_number'] ?? ''),
                'date_of_birth' => $_POST['date_of_birth'] ?? null,
                'date_of_death' => $_POST['date_of_death'] ?? '',
                'place_of_death' => $this->sanitizeInput($_POST['place_of_death'] ?? ''),
                'cause_of_death' => $this->sanitizeInput($_POST['cause_of_death'] ?? ''),
                'mortuary_name' => $this->sanitizeInput($_POST['mortuary_name'] ?? ''),
                'mortuary_bill_amount' => (float)($_POST['mortuary_bill_amount'] ?? 0),
                'mortuary_days_count' => (int)($_POST['mortuary_days_count'] ?? 0),
                'service_delivery_type' => 'standard_services', // Default to service delivery
                'notes' => $this->sanitizeInput($_POST['notes'] ?? '')
            ];
            
            // Validate required fields (no claim_amount required for service-based)
            $required = ['beneficiary_id', 'deceased_name', 'deceased_id_number', 'date_of_death', 'place_of_death'];
            foreach ($required as $field) {
                if (empty($claimData[$field])) {
                    $_SESSION['error'] = 'Please fill in all required fields.';
                    $this->redirect('/claims');
                    return;
                }
            }
            
            // Validate mortuary days (max 14 per policy)
            if ($claimData['mortuary_days_count'] > 14) {
                $_SESSION['error'] = 'Mortuary preservation is covered for a maximum of 14 days per policy.';
                $this->redirect('/claims');
                return;
            }
            
            // Validate beneficiary belongs to member
            $beneficiary = $this->beneficiaryModel->find($claimData['beneficiary_id']);
            if (!$beneficiary || $beneficiary['member_id'] != $member['id']) {
                $_SESSION['error'] = 'Invalid beneficiary selected.';
                $this->redirect('/claims');
                return;
            }
            
            // Check member eligibility per policy Section 9
            // Must be active, maturity period completed, not in default
            if ($member['status'] === 'defaulted') {
                $_SESSION['error'] = 'Cannot submit claim. Membership is in default status. Please clear outstanding contributions.';
                $this->redirect('/claims');
                return;
            }
            
            if ($member['status'] !== 'active') {
                $_SESSION['error'] = 'Cannot submit claim. Membership must be active.';
                $this->redirect('/claims');
                return;
            }
            
            // Check maturity period
            if (!empty($member['maturity_ends'])) {
                $maturityDate = new DateTime($member['maturity_ends']);
                $today = new DateTime();
                
                if ($today < $maturityDate) {
                    $daysRemaining = $today->diff($maturityDate)->days;
                    $_SESSION['error'] = "Cannot submit claim. Maturity period not completed. {$daysRemaining} days remaining.";
                    $this->redirect('/claims');
                    return;
                }
            }
            
            // Submit claim
            $claimId = $this->claimModel->submitClaim($claimData);

            // Handle required claim documents per policy Section 8
            // Required: ID copy, Chief letter, Mortuary invoice
            $claimDocumentModel = new ClaimDocument();

            $documentFields = [
                'id_copy' => ['required' => true, 'label' => 'ID/Birth Certificate Copy'],
                'chief_letter' => ['required' => true, 'label' => 'Chief Letter'],
                'mortuary_invoice' => ['required' => true, 'label' => 'Mortuary Invoice'],
                'death_certificate' => ['required' => false, 'label' => 'Death Certificate']
            ];

            foreach ($documentFields as $inputName => $config) {
                if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] === UPLOAD_ERR_NO_FILE) {
                    if ($config['required']) {
                        // Delete the claim if required document missing
                        $this->claimModel->delete($claimId);
                        $_SESSION['error'] = "Required document missing: {$config['label']}. Please upload all required documents.";
                        $this->redirect('/claims');
                        return;
                    }
                    continue;
                }

                $uploadResult = uploadFile($_FILES[$inputName], 'claims/' . $claimId);
                if ($uploadResult === false) {
                    if ($config['required']) {
                        $this->claimModel->delete($claimId);
                        $_SESSION['error'] = "Failed to upload required document: {$config['label']}. Please try again.";
                        $this->redirect('/claims');
                        return;
                    }
                    continue;
                }

                $claimDocumentModel->addDocument([
                    'claim_id' => $claimId,
                    'document_type' => $inputName,
                    'file_name' => $uploadResult['file_name'],
                    'file_path' => $uploadResult['file_path'],
                    'file_size' => $uploadResult['file_size'],
                    'mime_type' => $uploadResult['mime_type'],
                    'uploaded_by' => $_SESSION['user_id'] ?? null
                ]);
            }
            
            // Send notification email to admin
            if (class_exists('EmailService')) {
                try {
                    $emailService = new EmailService();
                    $emailService->sendClaimNotificationEmail($member, $claimData);
                } catch (Exception $e) {
                    error_log('Email notification failed: ' . $e->getMessage());
                }
            }
            
            $_SESSION['success'] = 'Claim submitted successfully. SHENA Companion will review your claim and contact you within 1-3 business days.';
            
        } catch (Exception $e) {
            error_log('Submit claim error: ' . $e->getMessage());
            $_SESSION['error'] = 'Failed to submit claim: ' . $e->getMessage();
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
    
    /**
     * Search members by member number, ID number, or name
     * Used for reconciliation and admin searches
     */
    public function search()
    {
        $query = $_GET['q'] ?? '';
        
        if (empty($query)) {
            $this->json([]);
            return;
        }
        
        // Search by member number, ID number, or name
        $members = $this->memberModel->search($query);
        $this->json($members);
    }
    
    /**
     * View package upgrade page
     */
    public function viewUpgrade()
    {
        $member = $this->memberModel->findByUserId($_SESSION['user_id']);
        
        if (!$member) {
            $_SESSION['error'] = 'Member profile not found.';
            $this->redirect('/member/dashboard');
            return;
        }
        
        // Check if upgrade is possible
        if ($member['package'] === 'premium') {
            $_SESSION['info'] = 'You are already on the Premium package.';
            $this->redirect('/member/dashboard');
            return;
        }
        
        require_once 'app/services/PlanUpgradeService.php';
        $upgradeService = new PlanUpgradeService();
        
        // Check for pending upgrades
        $pendingUpgrades = $upgradeService->getMemberPendingUpgrades($member['id']);
        
        // Calculate upgrade cost
        try {
            $calculation = $upgradeService->calculateUpgradeCost($member['id']);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('/member/dashboard');
            return;
        }
        
        // Get upgrade history
        $upgradeHistory = $upgradeService->getMemberUpgradeHistory($member['id']);
        
        $this->view('member/upgrade', [
            'member' => $member,
            'calculation' => $calculation,
            'pendingUpgrades' => $pendingUpgrades,
            'upgradeHistory' => $upgradeHistory
        ]);
    }
    
    /**
     * Request package upgrade
     */
    public function requestUpgrade()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Invalid request method'], 405);
            return;
        }
        
        $member = $this->memberModel->findByUserId($_SESSION['user_id']);
        
        if (!$member) {
            $this->json(['error' => 'Member not found'], 404);
            return;
        }
        
        require_once 'app/services/PlanUpgradeService.php';
        $upgradeService = new PlanUpgradeService();
        
        try {
            // Check for existing pending upgrades
            $pendingUpgrades = $upgradeService->getMemberPendingUpgrades($member['id']);
            if (!empty($pendingUpgrades)) {
                $this->json(['error' => 'You already have a pending upgrade request'], 400);
                return;
            }
            
            // Create upgrade request
            $upgradeRequestId = $upgradeService->createUpgradeRequest($member['id'], 'premium');
            
            // Initiate M-Pesa payment
            $phoneNumber = $_POST['phone_number'] ?? $member['phone'];
            $paymentResponse = $upgradeService->initiateUpgradePayment($upgradeRequestId, $phoneNumber);
            
            $this->json([
                'success' => true,
                'message' => $paymentResponse['message'],
                'upgrade_request_id' => $upgradeRequestId,
                'checkout_request_id' => $paymentResponse['checkout_request_id'],
                'amount' => $paymentResponse['amount']
            ]);
            
        } catch (Exception $e) {
            $this->json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Check upgrade payment status
     */
    public function checkUpgradeStatus()
    {
        $upgradeRequestId = $_GET['upgrade_request_id'] ?? null;
        
        if (!$upgradeRequestId) {
            $this->json(['error' => 'Upgrade request ID is required'], 400);
            return;
        }
        
        require_once 'app/services/PlanUpgradeService.php';
        $upgradeService = new PlanUpgradeService();
        
        try {
            $request = $upgradeService->getUpgradeRequest($upgradeRequestId);
            
            if (!$request) {
                $this->json(['error' => 'Upgrade request not found'], 404);
                return;
            }
            
            // Verify this upgrade belongs to the logged-in member
            $member = $this->memberModel->findByUserId($_SESSION['user_id']);
            if ($request['member_id'] != $member['id']) {
                $this->json(['error' => 'Unauthorized'], 403);
                return;
            }
            
            $this->json([
                'success' => true,
                'status' => $request['status'],
                'upgrade_request' => $request
            ]);
            
        } catch (Exception $e) {
            $this->json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Cancel upgrade request
     */
    public function cancelUpgrade()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Invalid request method'], 405);
            return;
        }
        
        $upgradeRequestId = $_POST['upgrade_request_id'] ?? null;
        
        if (!$upgradeRequestId) {
            $this->json(['error' => 'Upgrade request ID is required'], 400);
            return;
        }
        
        require_once 'app/services/PlanUpgradeService.php';
        $upgradeService = new PlanUpgradeService();
        
        try {
            $request = $upgradeService->getUpgradeRequest($upgradeRequestId);
            
            // Verify this upgrade belongs to the logged-in member
            $member = $this->memberModel->findByUserId($_SESSION['user_id']);
            if ($request['member_id'] != $member['id']) {
                $this->json(['error' => 'Unauthorized'], 403);
                return;
            }
            
            $upgradeService->cancelUpgrade($upgradeRequestId, 'Cancelled by member');
            
            $this->json([
                'success' => true,
                'message' => 'Upgrade request cancelled successfully'
            ]);
            
        } catch (Exception $e) {
            $this->json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * View notification settings page
     */
    public function viewNotificationSettings()
    {
        $member = $this->memberModel->findByUserId($_SESSION['user_id']);
        
        if (!$member) {
            $_SESSION['error'] = 'Member profile not found.';
            $this->redirect('/dashboard');
            return;
        }
        
        // Get notification preferences
        $db = Database::getInstance();
        $stmt = $db->getConnection()->prepare("
            SELECT * FROM notification_preferences WHERE user_id = ?
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $preferences = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Default preferences if none exist
        if (!$preferences) {
            $preferences = [
                'email_payment_reminders' => 1,
                'email_payment_confirmations' => 1,
                'email_claim_updates' => 1,
                'email_newsletters' => 1,
                'sms_payment_reminders' => 1,
                'sms_payment_confirmations' => 1,
                'sms_claim_updates' => 1,
                'sms_important_alerts' => 1,
                'notification_frequency' => 'immediate',
                'marketing_communications' => 0
            ];
        }
        
        $data = [
            'member' => $member,
            'preferences' => $preferences,
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('member.notification-settings', $data);
    }
    
    /**
     * Update notification preferences
     */
    public function updateNotificationSettings()
    {
        try {
            $this->validateCsrf();
            
            $member = $this->memberModel->findByUserId($_SESSION['user_id']);
            
            if (!$member) {
                $_SESSION['error'] = 'Member profile not found.';
                $this->redirect('/dashboard');
                return;
            }
            
            $db = Database::getInstance();
            
            // Prepare preferences data
            $preferences = [
                'email_payment_reminders' => isset($_POST['email_payment_reminders']) ? 1 : 0,
                'email_payment_confirmations' => isset($_POST['email_payment_confirmations']) ? 1 : 0,
                'email_claim_updates' => isset($_POST['email_claim_updates']) ? 1 : 0,
                'email_newsletters' => isset($_POST['email_newsletters']) ? 1 : 0,
                'sms_payment_reminders' => isset($_POST['sms_payment_reminders']) ? 1 : 0,
                'sms_payment_confirmations' => isset($_POST['sms_payment_confirmations']) ? 1 : 0,
                'sms_claim_updates' => isset($_POST['sms_claim_updates']) ? 1 : 0,
                'sms_important_alerts' => 1, // Always enabled
                'notification_frequency' => $_POST['notification_frequency'] ?? 'immediate',
                'marketing_communications' => isset($_POST['marketing_communications']) ? 1 : 0
            ];
            
            // Check if preferences exist
            $stmt = $db->getConnection()->prepare("
                SELECT id FROM notification_preferences WHERE user_id = ?
            ");
            $stmt->execute([$_SESSION['user_id']]);
            $exists = $stmt->fetch();
            
            if ($exists) {
                // Update existing preferences
                $stmt = $db->getConnection()->prepare("
                    UPDATE notification_preferences SET
                        email_payment_reminders = :email_payment_reminders,
                        email_payment_confirmations = :email_payment_confirmations,
                        email_claim_updates = :email_claim_updates,
                        email_newsletters = :email_newsletters,
                        sms_payment_reminders = :sms_payment_reminders,
                        sms_payment_confirmations = :sms_payment_confirmations,
                        sms_claim_updates = :sms_claim_updates,
                        sms_important_alerts = :sms_important_alerts,
                        notification_frequency = :notification_frequency,
                        marketing_communications = :marketing_communications,
                        updated_at = NOW()
                    WHERE user_id = :user_id
                ");
                $preferences['user_id'] = $_SESSION['user_id'];
                $stmt->execute($preferences);
            } else {
                // Insert new preferences
                $stmt = $db->getConnection()->prepare("
                    INSERT INTO notification_preferences (
                        user_id, email_payment_reminders, email_payment_confirmations,
                        email_claim_updates, email_newsletters, sms_payment_reminders,
                        sms_payment_confirmations, sms_claim_updates, sms_important_alerts,
                        notification_frequency, marketing_communications
                    ) VALUES (
                        :user_id, :email_payment_reminders, :email_payment_confirmations,
                        :email_claim_updates, :email_newsletters, :sms_payment_reminders,
                        :sms_payment_confirmations, :sms_claim_updates, :sms_important_alerts,
                        :notification_frequency, :marketing_communications
                    )
                ");
                $preferences['user_id'] = $_SESSION['user_id'];
                $stmt->execute($preferences);
            }
            
            $_SESSION['success'] = 'Notification preferences updated successfully!';
            
        } catch (Exception $e) {
            error_log('Notification settings update error: ' . $e->getMessage());
            $_SESSION['error'] = 'Failed to update preferences. Please try again.';
        }
        
        $this->redirect('/member/notification-settings');
    }
    
    /**
     * Display notifications page
     */
    public function notifications()
    {
        $member = $this->memberModel->findByUserId($_SESSION['user_id']);
        
        if (!$member) {
            $_SESSION['error'] = 'Member profile not found.';
            $this->redirect('/login');
            return;
        }
        
        // TODO: Get notifications from database
        // For now, we'll pass empty array and let the view handle sample data
        $notifications = [];
        
        $data = [
            'title' => 'Notifications - Shena Companion Welfare Association',
            'member' => $member,
            'notifications' => $notifications,
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('member.notifications', $data);
    }
    
    /**
     * Mark notification as read
     */
    public function markNotificationAsRead()
    {
        $this->validateCsrf();
        // TODO: Implement notification mark as read logic
        echo json_encode(['success' => true]);
    }
    
    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsAsRead()
    {
        $this->validateCsrf();
        // TODO: Implement mark all as read logic
        echo json_encode(['success' => true]);
    }
    
    /**
     * Delete a notification
     */
    public function deleteNotification()
    {
        $this->validateCsrf();
        // TODO: Implement notification delete logic
        echo json_encode(['success' => true]);
    }
    
    /**
     * Clear all notifications
     */
    public function clearAllNotifications()
    {
        $this->validateCsrf();
        // TODO: Implement clear all notifications logic
        echo json_encode(['success' => true]);
    }
}
