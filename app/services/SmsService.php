<?php
/**
 * SMS Service - Handles SMS sending via Twilio
 */
class SmsService 
{
    private $config;
    
    public function __construct()
    {
        $this->config = [
            'sid' => TWILIO_SID,
            'auth_token' => TWILIO_AUTH_TOKEN,
            'phone_number' => TWILIO_PHONE_NUMBER
        ];
    }
    
    public function sendSms($to, $message)
    {
        try {
            // Format phone number for Kenyan numbers
            $to = $this->formatPhoneNumber($to);
            
            // Twilio API endpoint
            $url = "https://api.twilio.com/2010-04-01/Accounts/{$this->config['sid']}/Messages.json";
            
            $data = [
                'From' => $this->config['phone_number'],
                'To' => $to,
                'Body' => $message
            ];
            
            $postData = http_build_query($data);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, $this->config['sid'] . ':' . $this->config['auth_token']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/x-www-form-urlencoded'
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode == 201) {
                return json_decode($response, true);
            } else {
                error_log('SMS sending failed: ' . $response);
                return false;
            }
            
        } catch (Exception $e) {
            error_log('SMS sending error: ' . $e->getMessage());
            return false;
        }
    }
    
    public function sendWelcomeSms($phone, $data)
    {
        $message = "Welcome to Shena Companion Welfare Association! Your member number is {$data['member_number']}. Thank you for joining us.";
        return $this->sendSms($phone, $message);
    }
    
    public function sendActivationSms($phone, $data)
    {
        $message = "Congratulations! Your Shena Companion account has been activated. Member No: {$data['member_number']}. You can now login to your dashboard.";
        return $this->sendSms($phone, $message);
    }
    
    public function sendPaymentReminderSms($phone, $data)
    {
        $message = "Payment Reminder: Your monthly contribution of KES {$data['amount']} is due. Pay via M-Pesa Paybill 4163987. Member: {$data['member_number']}";
        return $this->sendSms($phone, $message);
    }
    
    public function sendPaymentConfirmationSms($phone, $data)
    {
        $message = "Payment confirmed! KES {$data['amount']} received. Transaction ID: {$data['transaction_id']}. Thank you. - Shena Companion";
        return $this->sendSms($phone, $message);
    }
    
    public function sendClaimStatusSms($phone, $data)
    {
        $status = ucfirst($data['status']);
        $message = "Claim Update: Your claim has been {$status}. ";
        
        if ($data['status'] === 'approved' && isset($data['approved_amount'])) {
            $message .= "Approved amount: KES {$data['approved_amount']}. ";
        }
        
        $message .= "Check your dashboard for details. - Shena Companion";
        
        return $this->sendSms($phone, $message);
    }
    
    public function sendGracePeriodWarning($phone, $data)
    {
        $message = "Grace Period Warning: Your account will expire on {$data['expiry_date']}. Please make your payment to avoid deactivation. Member: {$data['member_number']}";
        return $this->sendSms($phone, $message);
    }
    
    public function sendAccountDeactivationSms($phone, $data)
    {
        $message = "Account Deactivated: Your membership has been suspended due to non-payment. Pay KES " . REACTIVATION_FEE . " reactivation fee + dues to restore. Member: {$data['member_number']}";
        return $this->sendSms($phone, $message);
    }
    
    public function sendBulkSms($recipients, $message)
    {
        $results = [];
        
        foreach ($recipients as $recipient) {
            $phone = is_array($recipient) ? $recipient['phone'] : $recipient;
            $results[] = $this->sendSms($phone, $message);
            
            // Add delay to respect rate limits
            sleep(1);
        }
        
        return $results;
    }
    
    public function sendCustomMessage($phone, $message, $memberNumber = null)
    {
        if ($memberNumber) {
            $message .= " - Member: {$memberNumber}";
        }
        
        $message .= " - Shena Companion";
        
        return $this->sendSms($phone, $message);
    }
    
    private function formatPhoneNumber($phone)
    {
        // Remove any non-digit characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Handle Kenyan phone number formats
        if (substr($phone, 0, 3) === '254') {
            // Already in international format
            return '+' . $phone;
        } elseif (substr($phone, 0, 1) === '0') {
            // Local format starting with 0
            return '+254' . substr($phone, 1);
        } elseif (strlen($phone) === 9) {
            // 9 digits without country code or leading 0
            return '+254' . $phone;
        }
        
        // Return as is if format is unclear
        return '+' . $phone;
    }
    
    public function validatePhoneNumber($phone)
    {
        $formatted = $this->formatPhoneNumber($phone);
        
        // Kenyan phone numbers should be +254 followed by 9 digits
        return preg_match('/^\+254[17][0-9]{8}$/', $formatted);
    }
}
