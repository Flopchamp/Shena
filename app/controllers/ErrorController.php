<?php
/**
 * Error Controller - Handles error pages
 */
class ErrorController extends BaseController 
{
    public function notFound()
    {
        http_response_code(404);
        
        $data = [
            'title' => '404 - Page Not Found',
            'error_code' => '404',
            'error_message' => 'The page you are looking for could not be found.'
        ];
        
        $this->view('errors.404', $data);
    }
    
    public function serverError()
    {
        http_response_code(500);
        
        $data = [
            'title' => '500 - Server Error',
            'error_code' => '500',
            'error_message' => 'Internal server error. Please try again later.'
        ];
        
        $this->view('errors.500', $data);
    }
    
    public function forbidden()
    {
        http_response_code(403);
        
        $data = [
            'title' => '403 - Access Forbidden',
            'error_code' => '403',
            'error_message' => 'You do not have permission to access this resource.'
        ];
        
        $this->view('errors.403', $data);
    }
}
