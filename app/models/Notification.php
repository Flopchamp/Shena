<?php
/**
 * Notification Model - Handles system notifications and alerts
 */
class Notification extends BaseModel 
{
    protected $table = 'notifications';
    
    /**
     * Create admin notification
     */
    public function createAdminNotification($type, $message, $actionUrl = null, $metadata = [])
    {
        $data = [
            'type' => $type,
            'message' => $message,
            'action_url' => $actionUrl,
            'metadata' => json_encode($metadata),
            'is_read' => false,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->create($data);
    }
    
    /**
     * Get unread admin notifications
     */
    public function getUnreadAdminNotifications($limit = 10)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE is_read = FALSE 
                ORDER BY created_at DESC 
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, ['limit' => $limit]);
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId)
    {
        return $this->update($notificationId, ['is_read' => true]);
    }
    
    /**
     * Get notifications by type
     */
    public function getByType($type, $limit = 50)
    {
        return $this->findAll(['type' => $type], 'created_at DESC', $limit);
    }
}
