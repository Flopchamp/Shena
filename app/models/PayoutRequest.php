<?php
/**
 * Payout Request Model
 * Manages agent payout requests with clear status workflow
 * 
 * @package Shena\Models
 */

class PayoutRequest extends BaseModel
{
    protected $table = 'payout_requests';
    
    /**
     * Create a new payout request
     * 
     * @param array $data Payout request data
     * @return int|false Payout request ID or false on failure
     */
    public function createPayoutRequest($data)
    {
        $sql = "INSERT INTO payout_requests (
                    agent_id, amount, payment_method, payment_details, 
                    status, notes, requested_at
                ) VALUES (?, ?, ?, ?, 'requested', ?, NOW())";
        
        $params = [
            $data['agent_id'],
            $data['amount'],
            $data['payment_method'],
            $data['payment_details'],
            $data['notes'] ?? null
        ];
        
        $stmt = $this->db->query($sql, $params);
        if ($stmt) {
            return $this->db->getConnection()->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Get payout request by ID
     * 
     * @param int $payoutId Payout request ID
     * @return array|false Payout data or false
     */
    public function getPayoutById($payoutId)
    {
        $sql = "SELECT pr.*, a.first_name, a.last_name, a.agent_number, a.phone as agent_phone
                FROM payout_requests pr
                JOIN agents a ON pr.agent_id = a.id
                WHERE pr.id = ?";
        
        return $this->db->query($sql, [$payoutId])->fetch();
    }
    
    /**
     * Get all payout requests for an agent
     * 
     * @param int $agentId Agent ID
     * @return array List of payout requests
     */
    public function getAgentPayouts($agentId)
    {
        $sql = "SELECT pr.*, 
                       CASE 
                           WHEN pr.status = 'requested' THEN 'Requested'
                           WHEN pr.status = 'processing' THEN 'Processing'
                           WHEN pr.status = 'paid' THEN 'Paid'
                           WHEN pr.status = 'rejected' THEN 'Rejected'
                       END as display_status
                FROM payout_requests pr
                WHERE pr.agent_id = ?
                ORDER BY pr.requested_at DESC";
        
        return $this->db->query($sql, [$agentId])->fetchAll();
    }
    
    /**
     * Get all unprocessed (requested) payouts for admin
     * 
     * @return array List of unprocessed payout requests
     */
    public function getUnprocessedPayouts()
    {
        $sql = "SELECT pr.*, a.first_name, a.last_name, a.agent_number, a.phone as agent_phone
                FROM payout_requests pr
                JOIN agents a ON pr.agent_id = a.id
                WHERE pr.status = 'requested'
                ORDER BY pr.requested_at ASC";
        
        return $this->db->query($sql)->fetchAll();
    }
    
    /**
     * Get all payout requests for admin with optional status filter
     * 
     * @param string $status Optional status filter
     * @return array List of payout requests
     */
    public function getAllPayouts($status = null)
    {
        $sql = "SELECT pr.*, a.first_name, a.last_name, a.agent_number, a.phone as agent_phone,
                       u.first_name as processed_by_first, u.last_name as processed_by_last
                FROM payout_requests pr
                JOIN agents a ON pr.agent_id = a.id
                LEFT JOIN users u ON pr.processed_by = u.id
                WHERE 1=1";
        
        $params = [];
        
        if ($status) {
            $sql .= " AND pr.status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY pr.requested_at DESC";
        
        return $this->db->query($sql, $params)->fetchAll();
    }
    
    /**
     * Process (approve) a payout request
     * 
     * @param int $payoutId Payout request ID
     * @param int $processedBy User ID of admin processing
     * @param string $paymentReference Payment reference/transaction ID
     * @param string $adminNotes Optional admin notes
     * @return bool Success status
     */
    public function processPayout($payoutId, $processedBy, $paymentReference, $adminNotes = null)
    {
        $sql = "UPDATE payout_requests 
                SET status = 'processing', 
                    processed_by = ?, 
                    processed_at = NOW(),
                    payment_reference = ?,
                    admin_notes = ?
                WHERE id = ? AND status = 'requested'";
        
        return $this->db->query($sql, [$processedBy, $paymentReference, $adminNotes, $payoutId]);
    }
    
    /**
     * Mark payout as paid/completed
     * 
     * @param int $payoutId Payout request ID
     * @return bool Success status
     */
    public function markAsPaid($payoutId)
    {
        $sql = "UPDATE payout_requests 
                SET status = 'paid'
                WHERE id = ? AND status = 'processing'";
        
        $stmt = $this->db->query($sql, [$payoutId]);
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Reject a payout request
     * 
     * @param int $payoutId Payout request ID
     * @param int $processedBy User ID of admin rejecting
     * @param string $adminNotes Reason for rejection
     * @return bool Success status
     */
    public function rejectPayout($payoutId, $processedBy, $adminNotes)
    {
        $sql = "UPDATE payout_requests 
                SET status = 'rejected', 
                    processed_by = ?, 
                    processed_at = NOW(),
                    admin_notes = ?
                WHERE id = ? AND status = 'requested'";
        
        return $this->db->query($sql, [$processedBy, $adminNotes, $payoutId]);
    }
    
    /**
     * Get payout statistics for an agent
     * 
     * @param int $agentId Agent ID
     * @return array Statistics
     */
    public function getAgentPayoutStats($agentId)
    {
        $sql = "SELECT 
                    COUNT(*) as total_requests,
                    COUNT(CASE WHEN status = 'requested' THEN 1 END) as pending_count,
                    COUNT(CASE WHEN status = 'processing' THEN 1 END) as processing_count,
                    COUNT(CASE WHEN status = 'paid' THEN 1 END) as paid_count,
                    COUNT(CASE WHEN status = 'rejected' THEN 1 END) as rejected_count,
                    SUM(CASE WHEN status = 'paid' THEN amount ELSE 0 END) as total_paid,
                    SUM(CASE WHEN status = 'requested' THEN amount ELSE 0 END) as pending_amount
                FROM payout_requests
                WHERE agent_id = ?";
        
        return $this->db->query($sql, [$agentId])->fetch();
    }
    
    /**
     * Get available balance for an agent (commissions earned but not yet paid out)
     * 
     * @param int $agentId Agent ID
     * @return float Available balance
     */
    public function getAvailableBalance($agentId)
    {
        // Get total paid commissions
        $sql = "SELECT COALESCE(SUM(commission_amount), 0) as total_commissions
                FROM agent_commissions
                WHERE agent_id = ? AND status = 'paid'";
        
        $result = $this->db->query($sql, [$agentId])->fetch();
        $totalCommissions = $result ? (float)$result['total_commissions'] : 0;
        
        // Get total paid out
        $sql = "SELECT COALESCE(SUM(amount), 0) as total_paid_out
                FROM payout_requests
                WHERE agent_id = ? AND status IN ('processing', 'paid')";
        
        $result = $this->db->query($sql, [$agentId])->fetch();
        $totalPaidOut = $result ? (float)$result['total_paid_out'] : 0;
        
        return $totalCommissions - $totalPaidOut;
    }
}
