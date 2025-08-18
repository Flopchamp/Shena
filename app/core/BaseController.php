<?php
/**
 * Base Controller Class
 */
abstract class BaseController 
{
    protected $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    protected function view($template, $data = [])
    {
        extract($data);
        
        $templatePath = VIEWS_PATH . '/' . str_replace('.', '/', $template) . '.php';
        
        if (!file_exists($templatePath)) {
            throw new Exception("Template {$template} not found");
        }
        
        include $templatePath;
    }
    
    protected function json($data, $code = 200)
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function redirect($url)
    {
        header("Location: {$url}");
        exit;
    }
    
    protected function requireAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
    }
    
    protected function requireAdmin()
    {
        $this->requireAuth();
        
        if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['super_admin', 'manager'])) {
            $this->redirect('/error/403');
        }
    }
    
    protected function validateCsrf()
    {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            throw new Exception("CSRF token mismatch");
        }
    }
    
    protected function generateCsrfToken()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    protected function sanitizeInput($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitizeInput'], $data);
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
    
    protected function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    protected function validatePhone($phone)
    {
        // Kenyan phone number validation
        $pattern = '/^(\+254|254|0)?([17][0-9]{8})$/';
        return preg_match($pattern, $phone);
    }
}
