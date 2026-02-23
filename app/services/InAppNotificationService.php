<?php
/**
 * In-App Notification Service
 * Stores notifications using communications tables for in-app display.
 */
class InAppNotificationService
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function notifyAdmins(array $payload, $senderId = null)
    {
        $admins = $this->db->fetchAll(
            "SELECT id FROM users WHERE role IN ('super_admin', 'manager') AND status IN ('active', 'pending')"
        );
        $adminIds = array_map(function ($row) {
            return (int)$row['id'];
        }, $admins);

        return $this->notifyUsers($adminIds, $payload, $senderId);
    }

    /**
     * Notify a single user
     * 
     * @param int $userId The user ID to notify
     * @param array $payload Notification data (subject, message, action_url, action_text)
     * @param int|null $senderId The sender's user ID
     * @return int|null The communication ID or null on failure
     */
    public function notifyUser($userId, array $payload, $senderId = null)
    {
        return $this->notifyUsers([$userId], $payload, $senderId);
    }

    public function notifyUsers(array $userIds, array $payload, $senderId = null)
    {
        $cleanIds = array_values(array_unique(array_filter($userIds, function ($id) {
            return is_numeric($id) && (int)$id > 0;
        })));

        if (empty($cleanIds)) {
            return null;
        }

        $subject = trim($payload['subject'] ?? 'Notification');
        $message = trim($payload['message'] ?? '');
        $actionUrl = $payload['action_url'] ?? null;
        $actionText = $payload['action_text'] ?? null;

        $criteria = [
            'user_ids' => $cleanIds,
            'channel' => 'in_app'
        ];

        $this->db->execute(
            "INSERT INTO communications (
                sender_id,
                recipient_id,
                recipient_type,
                recipient_criteria,
                subject,
                message,
                action_url,
                action_text,
                type,
                status,
                sent_at
            ) VALUES (
                :sender_id,
                NULL,
                'individual',
                :recipient_criteria,
                :subject,
                :message,
                :action_url,
                :action_text,
                'email',
                'sent',
                NOW()
            )",
            [
                'sender_id' => $senderId,
                'recipient_criteria' => json_encode($criteria),
                'subject' => $subject,
                'message' => $message,
                'action_url' => $actionUrl,
                'action_text' => $actionText
            ]
        );

        $communicationId = (int)$this->db->getConnection()->lastInsertId();

        $stmt = $this->db->getConnection()->prepare(
            "INSERT INTO communication_recipients (
                communication_id,
                user_id,
                type,
                status,
                sent_at
            ) VALUES (
                :communication_id,
                :user_id,
                'email',
                'sent',
                NOW()
            )"
        );

        foreach ($cleanIds as $userId) {
            $stmt->execute([
                ':communication_id' => $communicationId,
                ':user_id' => (int)$userId
            ]);
        }

        return $communicationId;
    }
}
