<?php
/**
 * Payment Service - Handles M-Pesa and other payment integrations
 */
class PaymentService 
{
    private $config;
    
    public function __construct()
    {
        $this->config = [
            'consumer_key' => MPESA_CONSUMER_KEY,
            'consumer_secret' => MPESA_CONSUMER_SECRET,
            'business_shortcode' => MPESA_BUSINESS_SHORTCODE,
            'passkey' => MPESA_PASSKEY,
            'callback_url' => MPESA_CALLBACK_URL
        ];
    }
    
    public function getAccessToken()
    {
        try {
            $url = 'https://sandbox-api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
            
            $credentials = base64_encode($this->config['consumer_key'] . ':' . $this->config['consumer_secret']);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Basic ' . $credentials,
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode == 200) {
                $data = json_decode($response, true);
                return $data['access_token'];
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log('M-Pesa access token error: ' . $e->getMessage());
            return false;
        }
    }
    
    public function initiateSTKPush($phoneNumber, $amount, $memberNumber, $description = 'Monthly Contribution')
    {
        try {
            $accessToken = $this->getAccessToken();
            
            if (!$accessToken) {
                throw new Exception('Failed to get M-Pesa access token');
            }
            
            $timestamp = date('YmdHis');
            $password = base64_encode($this->config['business_shortcode'] . $this->config['passkey'] . $timestamp);
            
            $data = [
                'BusinessShortCode' => $this->config['business_shortcode'],
                'Password' => $password,
                'Timestamp' => $timestamp,
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => $amount,
                'PartyA' => $phoneNumber,
                'PartyB' => $this->config['business_shortcode'],
                'PhoneNumber' => $phoneNumber,
                'CallBackURL' => $this->config['callback_url'],
                'AccountReference' => $memberNumber,
                'TransactionDesc' => $description
            ];
            
            $url = 'https://sandbox-api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json'
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode == 200) {
                return json_decode($response, true);
            } else {
                error_log('M-Pesa STK Push failed: ' . $response);
                return false;
            }
            
        } catch (Exception $e) {
            error_log('M-Pesa STK Push error: ' . $e->getMessage());
            return false;
        }
    }
    
    public function processCallback($callbackData)
    {
        try {
            $paymentModel = new Payment();
            
            // Parse the callback data
            $body = $callbackData['Body'] ?? [];
            $stkCallback = $body['stkCallback'] ?? [];
            
            $merchantRequestId = $stkCallback['MerchantRequestID'] ?? '';
            $checkoutRequestId = $stkCallback['CheckoutRequestID'] ?? '';
            $resultCode = $stkCallback['ResultCode'] ?? '';
            $resultDesc = $stkCallback['ResultDesc'] ?? '';
            
            if ($resultCode == 0) {
                // Payment successful
                $callbackMetadata = $stkCallback['CallbackMetadata'] ?? [];
                $items = $callbackMetadata['Item'] ?? [];
                
                $amount = 0;
                $mpesaReceiptNumber = '';
                $phoneNumber = '';
                $transactionDate = '';
                
                foreach ($items as $item) {
                    switch ($item['Name']) {
                        case 'Amount':
                            $amount = $item['Value'];
                            break;
                        case 'MpesaReceiptNumber':
                            $mpesaReceiptNumber = $item['Value'];
                            break;
                        case 'PhoneNumber':
                            $phoneNumber = $item['Value'];
                            break;
                        case 'TransactionDate':
                            $transactionDate = $item['Value'];
                            break;
                    }
                }
                
                // Find the payment record by checkout request ID
                $payment = $paymentModel->findAll(['transaction_reference' => $checkoutRequestId]);
                
                if (!empty($payment)) {
                    $paymentId = $payment[0]['id'];
                    
                    // Update payment status
                    $paymentModel->confirmPayment($paymentId, $mpesaReceiptNumber);
                    
                    // Retrieve updated payment record to check type and member
                    $confirmedPayment = $paymentModel->find($paymentId);
                    $memberId = $confirmedPayment['member_id'];
                    $paymentType = $confirmedPayment['payment_type'] ?? 'monthly';
                    
                    // -- Automate Reactivation Logic --
                    // Check if member is defaulted or payment is explicitly for reactivation
                    $memberModel = new Member();
                    $member = $memberModel->find($memberId);
                    
                    if ($member && ($member['status'] === 'defaulted' || $paymentType === 'reactivation')) {
                         // Calculate Arrears
                         // Arrears = (Months since coverage ended) * Monthly Contribution
                         // But simple policy check: Pay Arrears + Reactivation Fee (100)
                         
                         // We need to calculate how many months missed.
                         // Coverage ends date vs Today.
                         $today = new DateTimeImmutable('today');
                         $coverageEnds = $member['coverage_ends'] ? new DateTimeImmutable($member['coverage_ends']) : $today;
                         
                         // If coverage ends is in future/today, maybe not defaulted? 
                         // But Status says defaulted.
                         
                         $monthsMissed = 0;
                         if ($coverageEnds < $today) {
                             $diff = $today->diff($coverageEnds);
                             $monthsMissed = ($diff->y * 12) + $diff->m + ($diff->d > 0 ? 1 : 0);
                         }
                         
                         // If defaulted, at least 2 months missed typically?
                         if ($monthsMissed < 0) $monthsMissed = 0;
                         
                         // Enforce minimum arrears calculation if detailed logic needed
                         // For now, valid payment check:
                         $reactivationFee = defined('REACTIVATION_FEE') ? REACTIVATION_FEE : 100;
                         $monthlyContribution = $member['monthly_contribution'];
                         
                         $arrearsAmount = $monthsMissed * $monthlyContribution;
                         $totalRequired = $arrearsAmount + $reactivationFee;
                         
                         // Allow small variance or just check if payment covers significant portion?
                         // Policy says "Pay all outstanding contributions plus reactivation fee".
                         // If the user initiates a specific "Reactivation" payment, they should have been quoted this amount.
                         // We assume the amount paid ($amount) is what was requested.
                         
                         // We verify if this amount is roughly sufficient (e.g. within 100 bob or exact?)
                         // Let's be strict but safe: check if type is reactivation OR (defaulted AND amount big enough)
                         
                         if ($paymentType === 'reactivation' || ($member['status'] === 'defaulted' && $amount >= $totalRequired)) {
                             $memberModel->reactivateMember($memberId);
                             error_log("Member #{$memberId} automatically reactivated after payment of {$amount}.");
                         }
                    }
                    
                    // Send confirmation SMS and email
                    $this->sendPaymentConfirmation($payment[0], $amount, $mpesaReceiptNumber);
                    
                    return ['status' => 'success', 'message' => 'Payment processed successfully'];
                }
            } else {
                // Payment failed
                $payment = $paymentModel->findAll(['transaction_reference' => $checkoutRequestId]);
                
                if (!empty($payment)) {
                    $paymentId = $payment[0]['id'];
                    $paymentModel->failPayment($paymentId, $resultDesc);
                }
                
                return ['status' => 'failed', 'message' => $resultDesc];
            }
            
        } catch (Exception $e) {
            error_log('M-Pesa callback processing error: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Callback processing failed'];
        }
    }
    
    public function recordPaymentAttempt($memberId, $amount, $phoneNumber, $checkoutRequestId, $paymentType = 'monthly')
    {
        $paymentModel = new Payment();
        
        return $paymentModel->recordPayment([
            'member_id' => $memberId,
            'amount' => $amount,
            'payment_type' => $paymentType,
            'payment_method' => 'mpesa',
            'phone_number' => $phoneNumber,
            'status' => 'pending',
            'transaction_reference' => $checkoutRequestId
        ]);
    }
    
    public function queryTransactionStatus($checkoutRequestId)
    {
        try {
            $accessToken = $this->getAccessToken();
            
            if (!$accessToken) {
                return false;
            }
            
            $timestamp = date('YmdHis');
            $password = base64_encode($this->config['business_shortcode'] . $this->config['passkey'] . $timestamp);
            
            $data = [
                'BusinessShortCode' => $this->config['business_shortcode'],
                'Password' => $password,
                'Timestamp' => $timestamp,
                'CheckoutRequestID' => $checkoutRequestId
            ];
            
            $url = 'https://sandbox-api.safaricom.co.ke/mpesa/stkpushquery/v1/query';
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json'
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode == 200) {
                return json_decode($response, true);
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log('M-Pesa query error: ' . $e->getMessage());
            return false;
        }
    }
    
    private function sendPaymentConfirmation($payment, $amount, $transactionId)
    {
        try {
            // Get member details
            $memberModel = new Member();
            $member = $memberModel->getMemberWithUser($payment['member_id']);
            
            if (!$member) {
                return;
            }
            
            // Send email confirmation
            $emailService = new EmailService();
            $emailService->sendPaymentConfirmationEmail($member['email'], [
                'name' => $member['first_name'] . ' ' . $member['last_name'],
                'amount' => $amount,
                'transaction_id' => $transactionId,
                'payment_date' => date('Y-m-d H:i:s')
            ]);
            
            // Send SMS confirmation
            $smsService = new SmsService();
            $smsService->sendPaymentConfirmationSms($member['phone'], [
                'amount' => $amount,
                'transaction_id' => $transactionId
            ]);
            
        } catch (Exception $e) {
            error_log('Payment confirmation error: ' . $e->getMessage());
        }
    }
    
    public function processManualPayment($memberId, $amount, $paymentMethod, $reference, $notes = null)
    {
        $paymentModel = new Payment();
        
        return $paymentModel->recordPayment([
            'member_id' => $memberId,
            'amount' => $amount,
            'payment_type' => 'monthly',
            'payment_method' => $paymentMethod,
            'status' => 'completed',
            'reference' => $reference,
            'notes' => $notes,
            'payment_date' => date('Y-m-d H:i:s')
        ]);
    }
}
