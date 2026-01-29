<?php
/**
 * Bulk SMS Controller
 * Handles bulk SMS campaign creation and management
 * 
 * @package Shena\Controllers
 */

require_once __DIR__ . '/../services/BulkSmsService.php';
require_once __DIR__ . '/../models/Member.php';

class BulkSmsController extends BaseController
{
    private $bulkSmsService;
    private $memberModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->bulkSmsService = new BulkSmsService();
        $this->memberModel = new Member();
    }
    
    /**
     * Display bulk SMS campaigns list
     */
    public function index()
    {
        $this->requireRole(['admin', 'super_admin', 'manager']);
        
        $filters = [
            'status' => $_GET['status'] ?? '',
            'date_from' => $_GET['date_from'] ?? '',
            'date_to' => $_GET['date_to'] ?? ''
        ];
        
        $campaigns = $this->bulkSmsService->getAllCampaigns($filters);
        
        $this->render('admin/bulk-sms/index', [
            'campaigns' => $campaigns,
            'filters' => $filters,
            'pageTitle' => 'Bulk SMS Campaigns'
        ]);
    }
    
    /**
     * Display campaign creation form
     */
    public function create()
    {
        $this->requireRole(['admin', 'super_admin', 'manager']);
        
        // Get member statistics for targeting
        $stats = $this->getMemberStats();
        
        $this->render('admin/bulk-sms/create', [
            'stats' => $stats,
            'pageTitle' => 'Create SMS Campaign'
        ]);
    }
    
    /**
     * Store new campaign
     */
    public function store()
    {
        $this->requireRole(['admin', 'super_admin', 'manager']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/bulk-sms/create');
            return;
        }
        
        // Validate input
        $errors = $this->validateCampaignData($_POST);
        
        if (!empty($errors)) {
            $_SESSION['old_input'] = $_POST;
            $_SESSION['errors'] = $errors;
            redirect('/admin/bulk-sms/create');
            return;
        }
        
        // Prepare campaign data
        $campaignData = [
            'title' => $_POST['title'],
            'message' => $_POST['message'],
            'target_audience' => $_POST['target_audience'],
            'scheduled_at' => !empty($_POST['scheduled_at']) ? $_POST['scheduled_at'] : null
        ];
        
        // Add custom filters if target is 'custom'
        if ($_POST['target_audience'] === 'custom') {
            $customFilters = [];
            
            if (!empty($_POST['filter_package'])) {
                $customFilters['package'] = $_POST['filter_package'];
            }
            if (!empty($_POST['filter_status'])) {
                $customFilters['status'] = $_POST['filter_status'];
            }
            if (!empty($_POST['filter_county'])) {
                $customFilters['county'] = $_POST['filter_county'];
            }
            if (!empty($_POST['filter_joined_after'])) {
                $customFilters['joined_after'] = $_POST['filter_joined_after'];
            }
            if (!empty($_POST['filter_joined_before'])) {
                $customFilters['joined_before'] = $_POST['filter_joined_before'];
            }
            
            $campaignData['custom_filters'] = $customFilters;
        }
        
        // Create campaign
        $campaignId = $this->bulkSmsService->createCampaign($campaignData, $_SESSION['user_id']);
        
        if (!$campaignId) {
            $this->setFlashMessage('Failed to create campaign', 'error');
            redirect('/admin/bulk-sms/create');
            return;
        }
        
        // Get recipients and queue them
        $recipients = $this->bulkSmsService->getRecipients(
            $campaignData['target_audience'],
            $campaignData['custom_filters'] ?? []
        );
        
        if (empty($recipients)) {
            $this->setFlashMessage('No recipients found for the selected criteria', 'warning');
            redirect('/admin/bulk-sms/view/' . $campaignId);
            return;
        }
        
        // Queue recipients
        $this->bulkSmsService->queueRecipients($campaignId, $recipients);
        
        $this->setFlashMessage("Campaign created with {count($recipients)} recipients", 'success');
        redirect('/admin/bulk-sms/view/' . $campaignId);
    }
    
    /**
     * Display campaign details
     */
    public function show($campaignId)
    {
        $this->requireRole(['admin', 'super_admin', 'manager']);
        
        $campaign = $this->bulkSmsService->getCampaignById($campaignId);
        
        if (!$campaign) {
            $this->setFlashMessage('Campaign not found', 'error');
            redirect('/admin/bulk-sms');
            return;
        }
        
        $stats = $this->bulkSmsService->getCampaignStats($campaignId);
        
        $this->render('admin/bulk-sms/view', [
            'campaign' => $campaign,
            'stats' => $stats,
            'pageTitle' => 'Campaign: ' . $campaign['title']
        ]);
    }
    
    /**
     * Send campaign immediately
     */
    public function send($campaignId)
    {
        $this->requireRole(['admin', 'super_admin', 'manager']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/bulk-sms/view/' . $campaignId);
            return;
        }
        
        $campaign = $this->bulkSmsService->getCampaignById($campaignId);
        
        if (!$campaign) {
            $this->setFlashMessage('Campaign not found', 'error');
            redirect('/admin/bulk-sms');
            return;
        }
        
        if ($campaign['status'] !== 'draft') {
            $this->setFlashMessage('Only draft campaigns can be sent', 'error');
            redirect('/admin/bulk-sms/view/' . $campaignId);
            return;
        }
        
        // Send campaign (in batches)
        $batchSize = isset($_POST['batch_size']) ? (int)$_POST['batch_size'] : 50;
        $result = $this->bulkSmsService->sendCampaign($campaignId, $batchSize);
        
        if ($result['success']) {
            $message = "Sent: {$result['sent_count']}, Failed: {$result['failed_count']}, Pending: {$result['pending_count']}";
            $this->setFlashMessage($message, 'success');
        } else {
            $this->setFlashMessage($result['error'], 'error');
        }
        
        redirect('/admin/bulk-sms/view/' . $campaignId);
    }
    
    /**
     * Delete campaign
     */
    public function delete($campaignId)
    {
        $this->requireRole(['admin', 'super_admin']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/bulk-sms');
            return;
        }
        
        if ($this->bulkSmsService->deleteCampaign($campaignId)) {
            $this->setFlashMessage('Campaign deleted successfully', 'success');
        } else {
            $this->setFlashMessage('Failed to delete campaign (only draft campaigns can be deleted)', 'error');
        }
        
        redirect('/admin/bulk-sms');
    }
    
    /**
     * Preview recipients for campaign
     * Returns JSON response
     */
    public function previewRecipients()
    {
        $this->requireRole(['admin', 'super_admin', 'manager']);
        
        header('Content-Type: application/json');
        
        $targetAudience = $_GET['target_audience'] ?? 'all_members';
        $customFilters = [];
        
        if ($targetAudience === 'custom') {
            if (!empty($_GET['filter_package'])) {
                $customFilters['package'] = $_GET['filter_package'];
            }
            if (!empty($_GET['filter_status'])) {
                $customFilters['status'] = $_GET['filter_status'];
            }
            if (!empty($_GET['filter_county'])) {
                $customFilters['county'] = $_GET['filter_county'];
            }
        }
        
        $recipients = $this->bulkSmsService->getRecipients($targetAudience, $customFilters);
        
        echo json_encode([
            'count' => count($recipients),
            'sample' => array_slice($recipients, 0, 10) // First 10 for preview
        ]);
        exit;
    }
    
    /**
     * Get member statistics for targeting
     * 
     * @return array Statistics
     */
    private function getMemberStats()
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT 
                    COUNT(*) as total_members,
                    COUNT(CASE WHEN m.status = 'active' THEN 1 END) as active_count,
                    COUNT(CASE WHEN m.status = 'grace_period' THEN 1 END) as grace_period_count,
                    COUNT(CASE WHEN m.status = 'defaulted' THEN 1 END) as defaulted_count,
                    COUNT(CASE WHEN m.package = 'individual' THEN 1 END) as individual_count,
                    COUNT(CASE WHEN m.package = 'couple' THEN 1 END) as couple_count,
                    COUNT(CASE WHEN m.package = 'family' THEN 1 END) as family_count,
                    COUNT(CASE WHEN np.sms_enabled = 1 THEN 1 END) as sms_enabled_count
                FROM members m
                LEFT JOIN users u ON m.user_id = u.id
                LEFT JOIN notification_preferences np ON u.id = np.user_id";
        
        $stmt = $db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Validate campaign data
     * 
     * @param array $data Form data
     * @return array Validation errors
     */
    private function validateCampaignData($data)
    {
        $errors = [];
        
        if (empty($data['title'])) {
            $errors['title'] = 'Campaign title is required';
        }
        
        if (empty($data['message'])) {
            $errors['message'] = 'Message content is required';
        } elseif (strlen($data['message']) > 480) {
            $errors['message'] = 'Message cannot exceed 480 characters (3 SMS segments)';
        }
        
        if (empty($data['target_audience'])) {
            $errors['target_audience'] = 'Target audience is required';
        }
        
        if (!empty($data['scheduled_at'])) {
            $scheduledTime = strtotime($data['scheduled_at']);
            if ($scheduledTime < time()) {
                $errors['scheduled_at'] = 'Scheduled time must be in the future';
            }
        }
        
        return $errors;
    }
}
