<?php
/**
 * Home Controller - Handles public website pages
 */
class HomeController extends BaseController 
{
    public function index()
    {
        $data = [
            'title' => 'Welcome to Shena Companion Welfare Association',
            'page' => 'home'
        ];
        
        $this->view('public.home', $data);
    }
    
    public function about()
    {
        $data = [
            'title' => 'About Us - Shena Companion Welfare Association',
            'page' => 'about'
        ];
        
        $this->view('public.about', $data);
    }
    
    public function membership()
    {
        global $membership_packages;
        
        $data = [
            'title' => 'Membership Packages - Shena Companion Welfare Association',
            'page' => 'membership',
            'packages' => $membership_packages
        ];
        
        $this->view('public.membership', $data);
    }
    
    public function services()
    {
        $data = [
            'title' => 'Our Services - Shena Companion Welfare Association',
            'page' => 'services'
        ];
        
        $this->view('public.services', $data);
    }
    
    public function contact()
    {
        $data = [
            'title' => 'Contact Us - Shena Companion Welfare Association',
            'page' => 'contact',
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('public.contact', $data);
    }
    
    public function submitContact()
    {
        try {
            $this->validateCsrf();
            
            $name = $this->sanitizeInput($_POST['name'] ?? '');
            $email = $this->sanitizeInput($_POST['email'] ?? '');
            $phone = $this->sanitizeInput($_POST['phone'] ?? '');
            $subject = $this->sanitizeInput($_POST['subject'] ?? '');
            $message = $this->sanitizeInput($_POST['message'] ?? '');
            
            // Validate required fields
            if (empty($name) || empty($email) || empty($message)) {
                $_SESSION['error'] = 'Please fill in all required fields.';
                $this->redirect('/contact');
                return;
            }
            
            // Validate email
            if (!$this->validateEmail($email)) {
                $_SESSION['error'] = 'Please enter a valid email address.';
                $this->redirect('/contact');
                return;
            }
            
            // Validate phone if provided
            if (!empty($phone) && !$this->validatePhone($phone)) {
                $_SESSION['error'] = 'Please enter a valid Kenyan phone number.';
                $this->redirect('/contact');
                return;
            }
            
            // Send email notification to admin
            $emailService = new EmailService();
            $emailSent = $emailService->sendContactFormEmail([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'subject' => $subject,
                'message' => $message
            ]);
            
            if ($emailSent) {
                $_SESSION['success'] = 'Thank you for contacting us. We will get back to you soon.';
            } else {
                $_SESSION['error'] = 'There was an error sending your message. Please try again.';
            }
            
        } catch (Exception $e) {
            error_log('Contact form error: ' . $e->getMessage());
            $_SESSION['error'] = 'There was an error processing your request. Please try again.';
        }
        
        $this->redirect('/contact');
    }
}
