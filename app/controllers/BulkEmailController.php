<?php
/**
 * Bulk Email Controller
 * Handles bulk email campaign creation and management
 * 
 * @package Shena\Controllers
 */

require_once __DIR__ . '/../services/BulkEmailService.php';
require_once __DIR__ . '/../services/EmailService.php';
require_once __DIR__ . '/../models/Member.php';

class BulkEmailController extends BaseController
{
    private $bulkEmailService;
    private $emailService;
    private $memberModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->bulkEmailService = new BulkEmailService();
        $this->emailService = new EmailService();
        $this->memberModel = new Member();
    }
    
    /**
     * Display bulk email campaigns list
     */
    public function index()
    {
        $this->requireRole(['admin', 'super_admin', 'manager']);
        
        $filters = [
            'status' => $_GET['status'] ?? '',
            'date_from' => $_GET['date_from'] ?? '',
            'date_to' => $_GET['date_to'] ?? ''
        ];
        
        // Get campaigns
        $campaigns = $this->bulkEmailService->getAllCampaigns($filters);
        
        // Get templates
        $templates = $this->bulkEmailService->getTemplates();
        
        // Get statistics
        $stats = [
            'active_campaigns' => $this->bulkEmailService->getActiveCampaignCount(),
            'sent_today' => $this->bulkEmailService->getSentCountToday(),
            'total_sent' => $this->bulkEmailService->getTotalSent(),
            'failed_count' => $this->bulkEmailService->getFailedCount()
        ];
        
        $data = [
            'title' => 'Email Campaigns - Shena Companion',
            'campaigns' => $campaigns,
            'templates' => $templates,
            'stats' => $stats
        ];
        
        $this->view('admin.email-campaigns', $data);
    }
    
    /**
     * Create new email campaign
     */
    public function createCampaign()
    {
        $this->requireRole(['admin', 'super_admin', 'manager']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }
        
        $title = trim($_POST['title'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $targetAudience = $_POST['target_audience'] ?? 'all_members';
        $scheduledAt = $_POST['scheduled_at'] ?? null;
        $sendNow = isset($_POST['send_now']);
        
        if (empty($title) || empty($subject) || empty($message)) {
            $this->json(['error' => 'Title, subject, and message are required'], 400);
            return;
        }
        
        // Get recipients based on target audience
        $recipients = $this->bulkEmailService->getRecipients($targetAudience, $_POST);
        
        if (empty($recipients)) {
            $this->json(['error' => 'No recipients found for the selected audience'], 400);
            return;
        }
        
        // Create campaign
        $campaignId = $this->bulkEmailService->createCampaign([
            'title' => $title,
            'subject' => $subject,
            'message' => $message,
            'message_type' => 'email',
            'target_audience' => $targetAudience,
            'scheduled_at' => $scheduledAt && !$sendNow ? $scheduledAt : null,
            'total_recipients' => count($recipients),
            'created_by' => $_SESSION['user_id'] ?? 0
        ]);
        
        // Add recipients
        $this->bulkEmailService->addRecipients($campaignId, $recipients);
        
        // Send now if requested
        if ($sendNow) {
            $this->bulkEmailService->sendCampaign($campaignId);
            $this->json([
                'success' => true,
                'message' => 'Email campaign created and sending started',
                'campaign_id' => $campaignId
            ]);
        } else {
            $this->json([
                'success' => true,
                'message' => 'Email campaign created successfully',
                'campaign_id' => $campaignId
            ]);
        }
    }
    
    /**
     * Send campaign immediately
     */
    public function sendCampaign()
    {
        $this->requireRole(['admin', 'super_admin', 'manager']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }
        
        $campaignId = $_POST['campaign_id'] ?? 0;
        
        if (!$campaignId) {
            $this->json(['error' => 'Campaign ID is required'], 400);
            return;
        }
        
        $result = $this->bulkEmailService->sendCampaign($campaignId);
        
        if ($result['success']) {
            $this->json([
                'success' => true,
                'message' => 'Campaign sending started',
                'sent' => $result['sent'] ?? 0,
                'failed' => $result['failed'] ?? 0
            ]);
        } else {
            $this->json(['error' => $result['message'] ?? 'Failed to send campaign'], 500);
        }
    }
    
    /**
     * Cancel a scheduled campaign
     */
    public function cancelCampaign()
    {
        $this->requireRole(['admin', 'super_admin', 'manager']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }
        
        $campaignId = $_POST['campaign_id'] ?? 0;
        
        if (!$campaignId) {
            $this->json(['error' => 'Campaign ID is required'], 400);
            return;
        }
        
        $result = $this->bulkEmailService->cancelCampaign($campaignId);
        
        if ($result) {
            $this->json(['success' => true, 'message' => 'Campaign cancelled successfully']);
        } else {
            $this->json(['error' => 'Failed to cancel campaign'], 500);
        }
    }
    
    /**
     * View single campaign details
     */
    public function viewCampaign($id)
    {
        $this->requireRole(['admin', 'super_admin', 'manager']);
        
        $campaign = $this->bulkEmailService->getCampaign($id);
        
        if (!$campaign) {
            $_SESSION['error'] = 'Campaign not found';
            header('Location: /admin/communications');
            exit;
        }
        
        $recipients = $this->bulkEmailService->getCampaignRecipients($id);
        
        $this->view('admin.email-campaign-details', [
            'title' => 'Campaign Details - ' . $campaign['title'],
            'campaign' => $campaign,
            'recipients' => $recipients
        ]);
    }
    
    /**
     * Get email templates
     */
    public function templates()
    {
        $this->requireRole(['admin', 'super_admin', 'manager']);
        
        $templates = $this->bulkEmailService->getTemplates();
        
        $this->json(['success' => true, 'templates' => $templates]);
    }
    
    /**
     * Send quick email (single or to selected members)
     */
    public function quickEmail()
    {
        $this->requireRole(['admin', 'super_admin', 'manager']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }
        
        $recipients = $_POST['recipients'] ?? []; // Array of emails
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        if (empty($recipients) || empty($subject) || empty($message)) {
            $this->json(['error' => 'Recipients, subject, and message are required'], 400);
            return;
        }
        
        $sent = 0;
        $failed = 0;
        
        foreach ($recipients as $email) {
            try {
                $result = $this->emailService->sendEmail($email, $subject, $message, true);
                if ($result) {
                    $sent++;
                } else {
                    $failed++;
                }
            } catch (Exception $e) {
                error_log('Quick email error: ' . $e->getMessage());
                $failed++;
            }
        }
        
        $this->json([
            'success' => true,
            'message' => "Sent {$sent} emails, {$failed} failed",
            'sent' => $sent,
            'failed' => $failed
        ]);
    }
    
    /**
     * Pause a running campaign
     */
    public function pauseCampaign()
    {
        $this->requireRole(['admin', 'super_admin', 'manager']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }
        
        $campaignId = $_POST['campaign_id'] ?? 0;
        
        if (!$campaignId) {
            $this->json(['error' => 'Campaign ID is required'], 400);
            return;
        }
        
        $result = $this->bulkEmailService->pauseCampaign($campaignId);
        
        if ($result) {
            $this->json(['success' => true, 'message' => 'Campaign paused successfully']);
        } else {
            $this->json(['error' => 'Failed to pause campaign'], 500);
        }
    }
    
    /**
     * Reschedule a campaign
     */
    public function reschedule()
    {
        $this->requireRole(['admin', 'super_admin', 'manager']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }
        
        $campaignId = $_POST['campaign_id'] ?? 0;
        $scheduledAt = $_POST['scheduled_at'] ?? null;
        
        if (!$campaignId || !$scheduledAt) {
            $this->json(['error' => 'Campaign ID and scheduled date are required'], 400);
            return;
        }
        
        $result = $this->bulkEmailService->rescheduleCampaign($campaignId, $scheduledAt);
        
        if ($result) {
            $this->json(['success' => true, 'message' => 'Campaign rescheduled successfully']);
        } else {
            $this->json(['error' => 'Failed to reschedule campaign'], 500);
        }
    }
    
    /**
     * Retry failed recipients
     */
    public function retryFailed()
    {
        $this->requireRole(['admin', 'super_admin', 'manager']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }
        
        $campaignId = $_POST['campaign_id'] ?? 0;
        
        if (!$campaignId) {
            $this->json(['error' => 'Campaign ID is required'], 400);
            return;
        }
        
        $result = $this->bulkEmailService->retryFailedRecipients($campaignId);
        
        $this->json([
            'success' => true,
            'message' => "Retried {$result['retried']} failed emails",
            'sent' => $result['sent'] ?? 0,
            'failed' => $result['failed'] ?? 0
        ]);
    }
}
