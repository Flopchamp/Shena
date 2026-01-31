<?php
/**
 * Settings Controller
 * Manages system settings including email fallback configuration
 */

require_once __DIR__ . '/../core/BaseController.php';

class SettingsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->requireRole(['super_admin', 'manager']);
    }
    
    /**
     * Display settings page
     */
    public function index()
    {
        $settings = $this->getAllSettings();
        $notificationStats = $this->getNotificationStats();
        
        $this->render('admin/settings', [
            'title' => 'System Settings',
            'settings' => $settings,
            'notificationStats' => $notificationStats
        ]);
    }
    
    /**
     * Update a setting
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        $settingKey = $_POST['setting_key'] ?? '';
        $settingValue = $_POST['setting_value'] ?? '';
        
        if (empty($settingKey)) {
            $this->jsonResponse(['success' => false, 'message' => 'Setting key is required']);
            return;
        }
        
        try {
            $db = $this->db->getConnection();
            $sql = "UPDATE settings SET setting_value = ?, updated_at = NOW() 
                    WHERE setting_key = ?";
            $stmt = $db->prepare($sql);
            
            if ($stmt->execute([$settingValue, $settingKey])) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Setting updated successfully'
                ]);
            } else {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Failed to update setting'
                ]);
            }
            
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get all settings
     */
    private function getAllSettings()
    {
        try {
            $db = $this->db->getConnection();
            $sql = "SELECT * FROM settings ORDER BY setting_key ASC";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            
            $settings = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $settings[$row['setting_key']] = $row;
            }
            
            return $settings;
            
        } catch (Exception $e) {
            error_log('Failed to get settings: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get notification statistics
     */
    private function getNotificationStats()
    {
        try {
            require_once __DIR__ . '/../services/NotificationService.php';
            $notificationService = new NotificationService();
            
            // Get stats for today
            $today = date('Y-m-d 00:00:00');
            $statsToday = $notificationService->getStats($today);
            
            // Get stats for last 7 days
            $lastWeek = date('Y-m-d 00:00:00', strtotime('-7 days'));
            $statsWeek = $notificationService->getStats($lastWeek);
            
            // Get stats for last 30 days
            $lastMonth = date('Y-m-d 00:00:00', strtotime('-30 days'));
            $statsMonth = $notificationService->getStats($lastMonth);
            
            return [
                'today' => $this->formatStats($statsToday),
                'week' => $this->formatStats($statsWeek),
                'month' => $this->formatStats($statsMonth)
            ];
            
        } catch (Exception $e) {
            error_log('Failed to get notification stats: ' . $e->getMessage());
            return [
                'today' => [],
                'week' => [],
                'month' => []
            ];
        }
    }
    
    /**
     * Format stats array
     */
    private function formatStats($statsArray)
    {
        $formatted = [
            'sms_success' => 0,
            'sms_failed' => 0,
            'email_success' => 0,
            'email_failed' => 0,
            'total_failed' => 0
        ];
        
        foreach ($statsArray as $stat) {
            $key = $stat['method'] . '_' . $stat['status'];
            $formatted[$key] = $stat['count'];
        }
        
        return $formatted;
    }
    
    /**
     * Test email fallback
     */
    public function testFallback()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ?? '';
        $name = $_POST['name'] ?? 'Test User';
        
        if (empty($phone) || empty($email)) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Phone and email are required'
            ]);
            return;
        }
        
        try {
            require_once __DIR__ . '/../services/NotificationService.php';
            $notificationService = new NotificationService();
            
            $recipient = [
                'phone' => $phone,
                'email' => $email,
                'name' => $name
            ];
            
            $result = $notificationService->send(
                $recipient,
                'This is a test message from Shena Companion. Email fallback is working correctly!',
                'Test: Email Fallback',
                null,
                true
            );
            
            $this->jsonResponse($result);
            
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}
