<?php
/**
 * Payment Controller - Handles payment processing and M-Pesa callbacks
 */
class PaymentController extends BaseController 
{
    private $paymentService;
    private $memberModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->paymentService = new PaymentService();
        $this->memberModel = new Member();
    }
    
    public function initiatePayment()
    {
        try {
            // This endpoint expects JSON data
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                error_log('JSON decode error: ' . json_last_error_msg());
                $this->json(['error' => 'Invalid JSON data'], 400);
                return;
            }
            
            if (!$input) {
                $this->json(['error' => 'Invalid request data'], 400);
                return;
            }
            
            $memberId = $input['member_id'] ?? null;
            $amount = $input['amount'] ?? null;
            $phoneNumber = $input['phone_number'] ?? null;
            $paymentType = $input['payment_type'] ?? 'monthly';
            
            // Validate input
            if (!$memberId || !$amount || !$phoneNumber) {
                $this->json(['error' => 'Missing required fields'], 400);
                return;
            }
            
            // Get member details
            $member = $this->memberModel->getMemberWithUser($memberId);
            if (!$member) {
                $this->json(['error' => 'Member not found'], 404);
                return;
            }
            
            // Format phone number
            $phoneNumber = $this->formatPhoneNumber($phoneNumber);
            if (!$phoneNumber) {
                $this->json(['error' => 'Invalid phone number'], 400);
                return;
            }
            
            // Initiate M-Pesa STK Push
            $response = $this->paymentService->initiateSTKPush(
                $phoneNumber,
                $amount,
                $member['member_number'],
                ucfirst($paymentType) . ' Contribution'
            );
            
            if ($response && isset($response['CheckoutRequestID'])) {
                // Record payment attempt
                $this->paymentService->recordPaymentAttempt(
                    $memberId,
                    $amount,
                    $phoneNumber,
                    $response['CheckoutRequestID'],
                    $paymentType
                );
                
                $this->json([
                    'success' => true,
                    'message' => 'Payment initiated successfully. Please check your phone for M-Pesa prompt.',
                    'checkout_request_id' => $response['CheckoutRequestID']
                ]);
            } else {
                $this->json(['error' => 'Failed to initiate payment'], 500);
            }
            
        } catch (Exception $e) {
            error_log('Payment initiation error: ' . $e->getMessage());
            $this->json(['error' => 'Payment initiation failed'], 500);
        }
    }
    
    public function mpesaCallback()
    {
        try {
            // Get the callback data
            $callbackData = json_decode(file_get_contents('php://input'), true);
            
            if (!$callbackData) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid callback data']);
                return;
            }
            
            // Log the callback for debugging
            error_log('M-Pesa Callback: ' . json_encode($callbackData));
            
            // Process the callback
            $result = $this->paymentService->processCallback($callbackData);
            
            // Return appropriate response
            if ($result['status'] === 'success') {
                echo json_encode(['ResultCode' => 0, 'ResultDesc' => 'Success']);
            } else {
                echo json_encode(['ResultCode' => 1, 'ResultDesc' => $result['message']]);
            }
            
        } catch (Exception $e) {
            error_log('M-Pesa callback error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['ResultCode' => 1, 'ResultDesc' => 'Callback processing failed']);
        }
    }
    
    public function queryPaymentStatus()
    {
        try {
            $checkoutRequestId = $_GET['checkout_request_id'] ?? null;
            
            if (!$checkoutRequestId) {
                $this->json(['error' => 'Checkout request ID is required'], 400);
                return;
            }
            
            $response = $this->paymentService->queryTransactionStatus($checkoutRequestId);
            
            if ($response) {
                $this->json([
                    'success' => true,
                    'status' => $response
                ]);
            } else {
                $this->json(['error' => 'Failed to query payment status'], 500);
            }
            
        } catch (Exception $e) {
            error_log('Payment status query error: ' . $e->getMessage());
            $this->json(['error' => 'Status query failed'], 500);
        }
    }
    
    private function formatPhoneNumber($phone)
    {
        // Remove any non-digit characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Handle different formats
        if (substr($phone, 0, 3) === '254') {
            return $phone;
        } elseif (substr($phone, 0, 1) === '0') {
            return '254' . substr($phone, 1);
        } elseif (strlen($phone) === 9) {
            return '254' . $phone;
        }
        
        return false; // Invalid format
    }
}
