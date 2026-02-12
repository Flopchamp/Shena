<?php
/**
 * Claim Service Checklist Model
 * Tracks individual service deliveries for each claim
 */
class ClaimServiceChecklist extends BaseModel 
{
    protected $table = 'claim_service_checklist';
    
    /**
     * Initialize checklist for a new claim
     * Creates entries for all 5 core services
     */
    public function initializeChecklist($claimId)
    {
        $services = [
            'mortuary_bill',
            'body_dressing',
            'coffin',
            'transportation',
            'equipment'
        ];
        
        foreach ($services as $service) {
            $this->create([
                'claim_id' => $claimId,
                'service_type' => $service,
                'completed' => 0
            ]);
        }
        
        return true;
    }
    
    /**
     * Mark a service as completed
     */
    public function markServiceCompleted($claimId, $serviceType, $completedBy, $notes = null)
    {
        $sql = "UPDATE {$this->table} 
                SET completed = TRUE,
                    completed_at = NOW(),
                    completed_by = :completed_by,
                    service_notes = :notes
                WHERE claim_id = :claim_id 
                AND service_type = :service_type";
        
        return $this->db->execute($sql, [
            'claim_id' => $claimId,
            'service_type' => $serviceType,
            'completed_by' => $completedBy,
            'notes' => $notes
        ]);
    }
    
    /**
     * Get checklist for a claim
     */
    public function getClaimChecklist($claimId)
    {
        $sql = "SELECT cs.*, u.first_name, u.last_name 
                FROM {$this->table} cs
                LEFT JOIN users u ON cs.completed_by = u.id
                WHERE cs.claim_id = :claim_id
                ORDER BY cs.id ASC";
        
        return $this->db->fetchAll($sql, ['claim_id' => $claimId]);
    }
    
    /**
     * Check if all services are completed
     */
    public function areAllServicesCompleted($claimId)
    {
        $sql = "SELECT COUNT(*) as total,
                       SUM(CASE WHEN completed = TRUE THEN 1 ELSE 0 END) as completed
                FROM {$this->table}
                WHERE claim_id = :claim_id";
        
        $result = $this->db->fetch($sql, ['claim_id' => $claimId]);
        
        return $result['total'] > 0 && $result['total'] == $result['completed'];
    }
    
    /**
     * Get completion percentage
     */
    public function getCompletionPercentage($claimId)
    {
        $sql = "SELECT COUNT(*) as total,
                       SUM(CASE WHEN completed = TRUE THEN 1 ELSE 0 END) as completed
                FROM {$this->table}
                WHERE claim_id = :claim_id";
        
        $result = $this->db->fetch($sql, ['claim_id' => $claimId]);
        
        if ($result['total'] == 0) {
            return 0;
        }
        
        return round(($result['completed'] / $result['total']) * 100);
    }
}
