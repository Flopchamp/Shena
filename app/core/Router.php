<?php
/**
 * Router Class - Handles URL routing
 */
class Router 
{
    private $routes = [];
    
    public function __construct()
    {
        $this->loadRoutes();
    }
    
    private function loadRoutes()
    {
        // Public Routes
        $this->addRoute('GET', '/', 'HomeController@index');
        $this->addRoute('GET', '/about', 'HomeController@about');
        $this->addRoute('GET', '/membership', 'HomeController@membership');
        $this->addRoute('GET', '/services', 'HomeController@services');
        $this->addRoute('GET', '/contact', 'HomeController@contact');
        $this->addRoute('POST', '/contact', 'HomeController@submitContact');
        
        // Authentication Routes
        $this->addRoute('GET', '/login', 'AuthController@showLogin');
        $this->addRoute('POST', '/login', 'AuthController@login');
        $this->addRoute('GET', '/register', 'AuthController@showRegister');
        $this->addRoute('POST', '/register', 'AuthController@register');
        $this->addRoute('GET', '/logout', 'AuthController@logout');
        
        // Member Routes (Protected)
        $this->addRoute('GET', '/dashboard', 'MemberController@dashboard');
        $this->addRoute('GET', '/profile', 'MemberController@profile');
        $this->addRoute('POST', '/profile', 'MemberController@updateProfile');
        $this->addRoute('GET', '/payments', 'MemberController@payments');
        $this->addRoute('GET', '/beneficiaries', 'MemberController@beneficiaries');
        $this->addRoute('POST', '/beneficiaries', 'MemberController@addBeneficiary');
        $this->addRoute('GET', '/claims', 'MemberController@claims');
        $this->addRoute('POST', '/claims', 'MemberController@submitClaim');
        
        // Admin Routes (Super Admin & Manager)
        $this->addRoute('GET', '/admin-login', 'AdminController@showLogin');
        $this->addRoute('POST', '/admin-login', 'AdminController@login');
        $this->addRoute('GET', '/admin/login', 'AdminController@showLogin');
        $this->addRoute('POST', '/admin/login', 'AdminController@login');
        $this->addRoute('GET', '/admin', 'AdminController@dashboard');
        $this->addRoute('GET', '/admin/dashboard', 'AdminController@dashboard');
        $this->addRoute('GET', '/admin/members', 'AdminController@members');
        $this->addRoute('GET', '/admin/member/{id}', 'AdminController@viewMember');
        $this->addRoute('POST', '/admin/member/activate', 'AdminController@activateMember');
        $this->addRoute('POST', '/admin/member/deactivate', 'AdminController@deactivateMember');
        $this->addRoute('POST', '/admin/member/{id}/activate', 'AdminController@activateMember');
        $this->addRoute('POST', '/admin/member/{id}/deactivate', 'AdminController@deactivateMember');
        $this->addRoute('GET', '/admin/payments', 'AdminController@payments');
        $this->addRoute('GET', '/admin/claims', 'AdminController@claims');
        $this->addRoute('POST', '/admin/claims/{id}/approve', 'AdminController@approveClaim');
        $this->addRoute('POST', '/admin/claims/{id}/reject', 'AdminController@rejectClaim');
        $this->addRoute('GET', '/admin/reports', 'AdminController@reports');
        $this->addRoute('GET', '/admin/communications', 'AdminController@communications');
        $this->addRoute('POST', '/admin/send-message', 'AdminController@sendMessage');
        $this->addRoute('POST', '/admin/communications/send-email', 'AdminController@sendEmail');
        $this->addRoute('POST', '/admin/communications/send-sms', 'AdminController@sendSMS');
        $this->addRoute('GET', '/admin/settings', 'AdminController@settings');
        $this->addRoute('POST', '/admin/settings', 'AdminController@updateSettings');
        
        // API Routes
        $this->addRoute('POST', '/api/mpesa/callback', 'PaymentController@mpesaCallback');
        $this->addRoute('POST', '/api/payment/initiate', 'PaymentController@initiatePayment');
        
        // Error Routes
        $this->addRoute('GET', '/error/404', 'ErrorController@notFound');
        $this->addRoute('GET', '/error/500', 'ErrorController@serverError');
        $this->addRoute('GET', '/error/403', 'ErrorController@forbidden');
    }
    
    public function addRoute($method, $path, $action)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'action' => $action
        ];
    }
    
    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove trailing slash except for root
        if ($path !== '/' && substr($path, -1) === '/') {
            $path = rtrim($path, '/');
        }
        
        foreach ($this->routes as $route) {
            if ($this->matchRoute($route, $method, $path)) {
                $this->executeRoute($route, $path);
                return;
            }
        }
        
        // No route found - 404
        header('HTTP/1.0 404 Not Found');
        $this->executeAction('ErrorController@notFound');
    }
    
    private function matchRoute($route, $method, $path)
    {
        if ($route['method'] !== $method) {
            return false;
        }
        
        $routePath = $route['path'];
        
        // Convert route parameters {id} to regex
        $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $routePath);
        $pattern = '#^' . $pattern . '$#';
        
        return preg_match($pattern, $path);
    }
    
    private function executeRoute($route, $path)
    {
        // Extract parameters from URL
        $routePath = $route['path'];
        $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $routePath);
        $pattern = '#^' . $pattern . '$#';
        
        preg_match($pattern, $path, $matches);
        array_shift($matches); // Remove full match
        
        $this->executeAction($route['action'], $matches);
    }
    
    private function executeAction($action, $params = [])
    {
        list($controller, $method) = explode('@', $action);
        
        if (!class_exists($controller)) {
            throw new Exception("Controller {$controller} not found");
        }
        
        $controllerInstance = new $controller();
        
        if (!method_exists($controllerInstance, $method)) {
            throw new Exception("Method {$method} not found in {$controller}");
        }
        
        call_user_func_array([$controllerInstance, $method], $params);
    }
}
