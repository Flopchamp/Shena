<?php
/**
 * Helper Functions - Global utility functions
 */

/**
 * Escape HTML output
 */
function e($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Get session flash message
 */
function getFlashMessage($key, $default = null)
{
    if (isset($_SESSION[$key])) {
        $message = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $message;
    }
    return $default;
}

/**
 * Set session flash message
 */
function setFlashMessage($key, $message)
{
    $_SESSION[$key] = $message;
}

/**
 * Format currency
 */
function formatCurrency($amount, $currency = 'KES')
{
    return $currency . ' ' . number_format($amount, 2);
}

/**
 * Format date
 */
function formatDate($date, $format = 'M j, Y')
{
    if (empty($date) || $date === '0000-00-00' || $date === '0000-00-00 00:00:00') {
        return 'N/A';
    }
    return date($format, strtotime($date));
}

/**
 * Format datetime
 */
function formatDateTime($datetime, $format = 'M j, Y g:i A')
{
    if (empty($datetime) || $datetime === '0000-00-00 00:00:00') {
        return 'N/A';
    }
    return date($format, strtotime($datetime));
}

/**
 * Calculate time ago
 */
function timeAgo($datetime)
{
    $time = time() - strtotime($datetime);
    
    if ($time < 60) {
        return 'just now';
    } elseif ($time < 3600) {
        return floor($time / 60) . ' minutes ago';
    } elseif ($time < 86400) {
        return floor($time / 3600) . ' hours ago';
    } elseif ($time < 2592000) {
        return floor($time / 86400) . ' days ago';
    } else {
        return formatDate($datetime);
    }
}

/**
 * Generate random string
 */
function generateRandomString($length = 10)
{
    return bin2hex(random_bytes($length / 2));
}

/**
 * Check if user is logged in
 */
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

/**
 * Check if user has role
 */
function hasRole($roles)
{
    if (!is_array($roles)) {
        $roles = [$roles];
    }
    
    return isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], $roles);
}

/**
 * Check if user is admin
 */
function isAdmin()
{
    return hasRole(['super_admin', 'manager']);
}

/**
 * Get current user ID
 */
function getCurrentUserId()
{
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user role
 */
function getCurrentUserRole()
{
    return $_SESSION['user_role'] ?? null;
}

/**
 * Redirect if not logged in
 */
function requireLogin()
{
    if (!isLoggedIn()) {
        header('Location: /login');
        exit;
    }
}

/**
 * Redirect if not admin
 */
function requireAdmin()
{
    requireLogin();
    
    if (!isAdmin()) {
        header('Location: /error/403');
        exit;
    }
}

/**
 * Generate URL
 */
function url($path = '')
{
    return APP_URL . '/' . ltrim($path, '/');
}

/**
 * Get asset URL
 */
function asset($path)
{
    return APP_URL . '/public/' . ltrim($path, '/');
}

/**
 * Include CSS file
 */
function css($file)
{
    echo '<link rel="stylesheet" href="' . asset('css/' . $file) . '">';
}

/**
 * Include JS file
 */
function js($file)
{
    echo '<script src="' . asset('js/' . $file) . '"></script>';
}

/**
 * Validate Kenyan phone number
 */
function validateKenyanPhone($phone)
{
    $pattern = '/^(\+254|254|0)?([17][0-9]{8})$/';
    return preg_match($pattern, $phone);
}

/**
 * Format Kenyan phone number
 */
function formatKenyanPhone($phone)
{
    // Remove any non-digit characters
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    // Handle different formats
    if (substr($phone, 0, 3) === '254') {
        return '+' . $phone;
    } elseif (substr($phone, 0, 1) === '0') {
        return '+254' . substr($phone, 1);
    } elseif (strlen($phone) === 9) {
        return '+254' . $phone;
    }
    
    return $phone;
}

/**
 * Calculate age from date of birth
 */
function calculateAge($dateOfBirth)
{
    $today = new DateTime();
    $dob = new DateTime($dateOfBirth);
    return $today->diff($dob)->y;
}

/**
 * Get membership package details
 */
function getPackageDetails($package)
{
    global $membership_packages;
    return $membership_packages[$package] ?? null;
}

/**
 * Get grace period months based on age
 */
function getGracePeriodMonths($age)
{
    return $age >= 80 ? GRACE_PERIOD_80_AND_ABOVE : GRACE_PERIOD_UNDER_80;
}

/**
 * Check if file upload is valid
 */
function isValidUpload($file, $allowedTypes = null)
{
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        return false;
    }
    
    $allowedTypes = $allowedTypes ?? ALLOWED_FILE_TYPES;
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    return in_array($fileExt, $allowedTypes);
}

/**
 * Upload file securely
 */
function uploadFile($file, $directory, $allowedTypes = null)
{
    if (!isValidUpload($file, $allowedTypes)) {
        return false;
    }
    
    $uploadDir = UPLOADS_PATH . '/' . trim($directory, '/') . '/';
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $fileName = generateRandomString(20) . '.' . $fileExt;
    $filePath = $uploadDir . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        return [
            'file_name' => $fileName,
            'file_path' => $filePath,
            'original_name' => $file['name'],
            'file_size' => $file['size'],
            'mime_type' => $file['type']
        ];
    }
    
    return false;
}

/**
 * Log activity
 */
function logActivity($action, $details = '', $userId = null)
{
    try {
        $userId = $userId ?? getCurrentUserId();
        
        if ($userId) {
            $db = Database::getInstance();
            $db->insert('activity_logs', [
                'user_id' => $userId,
                'action' => $action,
                'details' => $details,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]);
        }
    } catch (Exception $e) {
        error_log('Activity logging error: ' . $e->getMessage());
    }
}

/**
 * Get status badge class
 */
function getStatusBadgeClass($status)
{
    $classes = [
        'active' => 'badge-success',
        'inactive' => 'badge-secondary',
        'pending' => 'badge-warning',
        'defaulted' => 'badge-danger',
        'grace_period' => 'badge-info',
        'approved' => 'badge-success',
        'rejected' => 'badge-danger',
        'submitted' => 'badge-primary',
        'completed' => 'badge-success',
        'failed' => 'badge-danger'
    ];
    
    return $classes[$status] ?? 'badge-secondary';
}

/**
 * Truncate text
 */
function truncate($text, $length = 100, $suffix = '...')
{
    if (strlen($text) <= $length) {
        return $text;
    }
    
    return substr($text, 0, $length) . $suffix;
}

/**
 * Generate pagination HTML
 */
function pagination($currentPage, $totalPages, $baseUrl)
{
    if ($totalPages <= 1) {
        return '';
    }
    
    $html = '<nav aria-label="Page navigation"><ul class="pagination">';
    
    // Previous button
    if ($currentPage > 1) {
        $prevPage = $currentPage - 1;
        $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . $prevPage . '">Previous</a></li>';
    }
    
    // Page numbers
    $start = max(1, $currentPage - 2);
    $end = min($totalPages, $currentPage + 2);
    
    for ($i = $start; $i <= $end; $i++) {
        $active = $i === $currentPage ? 'active' : '';
        $html .= '<li class="page-item ' . $active . '"><a class="page-link" href="' . $baseUrl . '?page=' . $i . '">' . $i . '</a></li>';
    }
    
    // Next button
    if ($currentPage < $totalPages) {
        $nextPage = $currentPage + 1;
        $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . $nextPage . '">Next</a></li>';
    }
    
    $html .= '</ul></nav>';
    
    return $html;
}
