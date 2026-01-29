<?php
/**
 * Notification Preference Model
 * Manages user notification preferences for emails and SMS
 * 
 * @package Shena\Models
 */

class NotificationPreference extends BaseModel
{
    protected $table = 'notification_preferences';
    
    /**
     * Get notification preferences for a user
     * 
     * @param int $userId User ID
     * @return array|false Preference settings or false
     */
    public function getUserPreferences($userId)
    {
        $sql = "SELECT * FROM notification_preferences WHERE user_id = ?";
        $prefs = $this->db->query($sql, [$userId])->fetch();
        
        // Create default preferences if none exist
        if (!$prefs) {
            $this->createDefaultPreferences($userId);
            return $this->getUserPreferences($userId);
        }
        
        return $prefs;
    }
    
    /**
     * Create default notification preferences for a user
     * 
     * @param int $userId User ID
     * @return bool Success status
     */
    public function createDefaultPreferences($userId)
    {
        $sql = "INSERT INTO notification_preferences (
                    user_id, email_enabled, sms_enabled, 
                    payment_reminders, grace_period_alerts, 
                    claim_updates, general_announcements
                ) VALUES (?, 1, 1, 1, 1, 1, 1)";
        
        return $this->db->query($sql, [$userId]);
    }
    
    /**
     * Update user notification preferences
     * 
     * @param int $userId User ID
     * @param array $preferences Updated preferences
     * @return bool Success status
     */
    public function updatePreferences($userId, $preferences)
    {
        $fields = [];
        $params = [];
        
        $allowedFields = [
            'email_enabled', 'sms_enabled', 'payment_reminders',
            'grace_period_alerts', 'claim_updates', 'general_announcements',
            'promotional_messages', 'preferred_language',
            'quiet_hours_start', 'quiet_hours_end'
        ];
        
        foreach ($allowedFields as $field) {
            if (isset($preferences[$field])) {
                $fields[] = "$field = ?";
                // Convert boolean values to 1/0
                if (is_bool($preferences[$field])) {
                    $params[] = $preferences[$field] ? 1 : 0;
                } else {
                    $params[] = $preferences[$field];
                }
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $params[] = $userId;
        
        $sql = "UPDATE notification_preferences SET " . implode(', ', $fields) . " WHERE user_id = ?";
        
        return $this->db->query($sql, $params);
    }
    
    /**
     * Check if user should receive email notifications
     * 
     * @param int $userId User ID
     * @param string $type Notification type (payment_reminders, grace_period_alerts, etc.)
     * @return bool True if should receive
     */
    public function shouldReceiveEmail($userId, $type = null)
    {
        $prefs = $this->getUserPreferences($userId);
        
        if (!$prefs || !$prefs['email_enabled']) {
            return false;
        }
        
        // Check specific notification type if provided
        if ($type && isset($prefs[$type])) {
            return (bool)$prefs[$type];
        }
        
        return true;
    }
    
    /**
     * Check if user should receive SMS notifications
     * 
     * @param int $userId User ID
     * @param string $type Notification type
     * @return bool True if should receive
     */
    public function shouldReceiveSms($userId, $type = null)
    {
        $prefs = $this->getUserPreferences($userId);
        
        if (!$prefs || !$prefs['sms_enabled']) {
            return false;
        }
        
        // Check specific notification type if provided
        if ($type && isset($prefs[$type])) {
            return (bool)$prefs[$type];
        }
        
        return true;
    }
    
    /**
     * Check if user is in quiet hours
     * 
     * @param int $userId User ID
     * @return bool True if in quiet hours
     */
    public function isInQuietHours($userId)
    {
        $prefs = $this->getUserPreferences($userId);
        
        if (!$prefs || !$prefs['quiet_hours_start'] || !$prefs['quiet_hours_end']) {
            return false;
        }
        
        $currentTime = date('H:i:s');
        $start = $prefs['quiet_hours_start'];
        $end = $prefs['quiet_hours_end'];
        
        // Handle overnight quiet hours (e.g., 22:00 to 06:00)
        if ($start > $end) {
            return $currentTime >= $start || $currentTime <= $end;
        }
        
        return $currentTime >= $start && $currentTime <= $end;
    }
    
    /**
     * Disable all notifications for a user (unsubscribe)
     * 
     * @param int $userId User ID
     * @return bool Success status
     */
    public function unsubscribeAll($userId)
    {
        $sql = "UPDATE notification_preferences 
                SET email_enabled = 0, sms_enabled = 0,
                    payment_reminders = 0, grace_period_alerts = 0,
                    claim_updates = 0, general_announcements = 0,
                    promotional_messages = 0
                WHERE user_id = ?";
        
        return $this->db->query($sql, [$userId]);
    }
    
    /**
     * Get users who should receive a specific notification type
     * 
     * @param string $type Notification type
     * @param string $channel 'email' or 'sms'
     * @return array List of user IDs
     */
    public function getUsersForNotification($type, $channel = 'email')
    {
        $channelField = $channel . '_enabled';
        
        $sql = "SELECT user_id FROM notification_preferences 
                WHERE $channelField = 1 AND $type = 1";
        
        $results = $this->db->query($sql)->fetchAll();
        
        return array_column($results, 'user_id');
    }
    
    /**
     * Get count of users with email enabled
     * 
     * @return int Count
     */
    public function getEmailEnabledCount()
    {
        $sql = "SELECT COUNT(*) as count FROM notification_preferences WHERE email_enabled = 1";
        $result = $this->db->query($sql)->fetch();
        return $result['count'];
    }
    
    /**
     * Get count of users with SMS enabled
     * 
     * @return int Count
     */
    public function getSmsEnabledCount()
    {
        $sql = "SELECT COUNT(*) as count FROM notification_preferences WHERE sms_enabled = 1";
        $result = $this->db->query($sql)->fetch();
        return $result['count'];
    }
}
