<?php
/**
 * Email Service - Handles email sending via SMTP
 */
class EmailService 
{
    private $config;
    
    public function __construct()
    {
        $this->config = [
            'host' => MAIL_HOST,
            'port' => MAIL_PORT,
            'username' => MAIL_USERNAME,
            'password' => MAIL_PASSWORD,
            'from_email' => MAIL_FROM_EMAIL,
            'from_name' => MAIL_FROM_NAME
        ];
    }
    
    public function sendEmail($to, $subject, $body, $isHtml = true)
    {
        try {
            // Using PHPMailer-like approach with native PHP mail
            $headers = [
                'From: ' . $this->config['from_name'] . ' <' . $this->config['from_email'] . '>',
                'Reply-To: ' . $this->config['from_email'],
                'X-Mailer: PHP/' . phpversion()
            ];
            
            if ($isHtml) {
                $headers[] = 'MIME-Version: 1.0';
                $headers[] = 'Content-type: text/html; charset=UTF-8';
            }
            
            $headerString = implode("\r\n", $headers);
            
            return mail($to, $subject, $body, $headerString);
            
        } catch (Exception $e) {
            error_log('Email sending failed: ' . $e->getMessage());
            return false;
        }
    }
    
    public function sendWelcomeEmail($email, $data)
    {
        $subject = 'Welcome to Shena Companion Welfare Association';
        
        $body = $this->getEmailTemplate('welcome', [
            'name' => $data['name'],
            'member_number' => $data['member_number']
        ]);
        
        return $this->sendEmail($email, $subject, $body);
    }
    
    public function sendAccountActivationEmail($email, $data)
    {
        $subject = 'Your Shena Companion Account Has Been Activated';
        
        $body = $this->getEmailTemplate('activation', [
            'name' => $data['name'],
            'member_number' => $data['member_number']
        ]);
        
        return $this->sendEmail($email, $subject, $body);
    }
    
    public function sendPaymentReminderEmail($email, $data)
    {
        $subject = 'Monthly Contribution Payment Reminder';
        
        $body = $this->getEmailTemplate('payment_reminder', [
            'name' => $data['name'],
            'member_number' => $data['member_number'],
            'amount' => $data['amount'],
            'due_date' => $data['due_date']
        ]);
        
        return $this->sendEmail($email, $subject, $body);
    }
    
    public function sendPaymentConfirmationEmail($email, $data)
    {
        $subject = 'Payment Confirmation - Shena Companion';
        
        $body = $this->getEmailTemplate('payment_confirmation', [
            'name' => $data['name'],
            'amount' => $data['amount'],
            'transaction_id' => $data['transaction_id'],
            'payment_date' => $data['payment_date']
        ]);
        
        return $this->sendEmail($email, $subject, $body);
    }
    
    public function sendClaimNotificationEmail($member, $claimData)
    {
        $adminEmail = 'admin@shenacompanion.org'; // Configure this
        $subject = 'New Claim Submitted - Member: ' . $member['member_number'];
        
        $body = $this->getEmailTemplate('claim_notification', [
            'member_name' => $member['first_name'] . ' ' . $member['last_name'],
            'member_number' => $member['member_number'],
            'deceased_name' => $claimData['deceased_name'],
            'claim_amount' => $claimData['claim_amount'],
            'date_of_death' => $claimData['date_of_death']
        ]);
        
        return $this->sendEmail($adminEmail, $subject, $body);
    }
    
    public function sendClaimStatusUpdateEmail($email, $data)
    {
        $subject = 'Claim Status Update - ' . ucfirst($data['status']);
        
        $body = $this->getEmailTemplate('claim_status', [
            'name' => $data['name'],
            'status' => $data['status'],
            'claim_id' => $data['claim_id'],
            'notes' => $data['notes'] ?? '',
            'approved_amount' => $data['approved_amount'] ?? null
        ]);
        
        return $this->sendEmail($email, $subject, $body);
    }
    
    public function sendContactFormEmail($data)
    {
        $adminEmail = 'admin@shenacompanion.org'; // Configure this
        $subject = 'Contact Form Submission: ' . $data['subject'];
        
        $body = $this->getEmailTemplate('contact_form', $data);
        
        return $this->sendEmail($adminEmail, $subject, $body);
    }
    
    public function sendBulkEmail($recipients, $subject, $body)
    {
        $results = [];
        
        foreach ($recipients as $recipient) {
            $email = is_array($recipient) ? $recipient['email'] : $recipient;
            $results[] = $this->sendEmail($email, $subject, $body);
            
            // Add small delay to avoid overwhelming the mail server
            usleep(100000); // 0.1 seconds
        }
        
        return $results;
    }
    
    private function getEmailTemplate($template, $data = [])
    {
        $templatePath = VIEWS_PATH . '/emails/' . $template . '.php';
        
        if (file_exists($templatePath)) {
            ob_start();
            extract($data);
            include $templatePath;
            return ob_get_clean();
        }
        
        // Fallback to simple text template
        return $this->getSimpleTemplate($template, $data);
    }
    
    private function getSimpleTemplate($template, $data)
    {
        switch ($template) {
            case 'welcome':
                return "
                <html>
                <body>
                    <h2>Welcome to Shena Companion Welfare Association</h2>
                    <p>Dear {$data['name']},</p>
                    <p>Thank you for registering with Shena Companion Welfare Association.</p>
                    <p>Your member number is: <strong>{$data['member_number']}</strong></p>
                    <p>Your membership application is currently under review. We will notify you once it's approved.</p>
                    <br>
                    <p>Best regards,<br>Shena Companion Welfare Association</p>
                </body>
                </html>";
                
            case 'payment_reminder':
                return "
                <html>
                <body>
                    <h2>Payment Reminder</h2>
                    <p>Dear {$data['name']},</p>
                    <p>This is a reminder that your monthly contribution of KES {$data['amount']} is due.</p>
                    <p>Member Number: {$data['member_number']}</p>
                    <p>Please make your payment through M-Pesa Paybill: 4163987</p>
                    <br>
                    <p>Best regards,<br>Shena Companion Welfare Association</p>
                </body>
                </html>";
                
            default:
                return "
                <html>
                <body>
                    <h2>Shena Companion Welfare Association</h2>
                    <p>Thank you for your interest in our services.</p>
                    <br>
                    <p>Best regards,<br>Shena Companion Welfare Association</p>
                </body>
                </html>";
        }
    }
}
