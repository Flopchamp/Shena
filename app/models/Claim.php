<?php
/**
 * Claim Model - Handles funeral claim processing and management
 */
class Claim extends BaseModel 
{
    protected $table = 'claims';
    
    public function getMemberClaims($memberId)
    {
        $sql = "SELECT c.*, m.member_number, u.first_name, u.last_name 
                FROM {$this->table} c 
                JOIN members m ON c.member_id = m.id 
                JOIN users u ON m.user_id = u.id 
                WHERE c.member_id = :member_id 
                ORDER BY c.created_at DESC";
        
        return $this->db->fetchAll($sql, ['member_id' => $memberId]);
    }
    
    public function getClaimsByStatus($status)
    {
        $sql = "SELECT c.*, m.member_number, u.first_name, u.last_name 
                FROM {$this->table} c 
                JOIN members m ON c.member_id = m.id 
                JOIN users u ON m.user_id = u.id 
                WHERE c.status = :status 
                ORDER BY c.created_at DESC";
        
        return $this->db->fetchAll($sql, ['status' => $status]);
    }
    
    public function getClaimsByDateRange($startDate, $endDate)
    {
        $sql = "SELECT c.*, m.member_number, u.first_name, u.last_name 
                FROM {$this->table} c 
                JOIN members m ON c.member_id = m.id 
                JOIN users u ON m.user_id = u.id 
                WHERE c.created_at BETWEEN :start_date AND :end_date 
                ORDER BY c.created_at DESC";
        
        return $this->db->fetchAll($sql, [
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }
    
    public function submitClaim($data)
    {
        // Align required fields with database schema and policy booklet
        // Schema columns: deceased_name, deceased_id_number, date_of_death, place_of_death, claim_amount, etc.
        $requiredFields = ['member_id', 'deceased_name', 'deceased_id_number', 'date_of_death', 'place_of_death', 'claim_amount'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                throw new Exception("Missing required field: {$field}");
            }
        }
        
        // Set deceased type (member or dependent)
        if (!isset($data['deceased_type'])) {
            $data['deceased_type'] = isset($data['dependent_id']) && $data['dependent_id'] ? 'dependent' : 'member';
        }
        
        // Validate dependent exists if claiming for dependent
        if ($data['deceased_type'] === 'dependent' && isset($data['dependent_id'])) {
            $dependentModel = new Dependent();
            $dependent = $dependentModel->find($data['dependent_id']);
            if (!$dependent || $dependent['member_id'] != $data['member_id']) {
                throw new Exception("Invalid dependent for this member");
            }
            if (!$dependent['is_covered']) {
                throw new Exception("Dependent is not currently covered");
            }
        }
        
        $data['created_at'] = date('Y-m-d H:i:s');
        // New claims start in "submitted" status, matching admin dashboards and policy
        $data['status'] = 'submitted';
        
        return $this->create($data);
    }
    
    public function approveClaim($claimId, $approvedAmount = null, $notes = null)
    {
        $data = [
            'status' => 'approved',
            'approved_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // If admin did not specify an approved amount, default to the claimed amount
        if ($approvedAmount === null) {
            $existing = $this->find($claimId);
            if ($existing && isset($existing['claim_amount'])) {
                $approvedAmount = $existing['claim_amount'];
            }
        }
        
        if ($approvedAmount !== null) {
            $data['approved_amount'] = $approvedAmount;
        }
        
        if ($notes) {
            $data['processing_notes'] = $notes;
        }
        
        return $this->update($claimId, $data);
    }
    
    public function rejectClaim($claimId, $reason)
    {
        return $this->update($claimId, [
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'rejected_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function processClaim($claimId, $paymentReference = null)
    {
        $data = [
            'status' => 'processed',
            'processed_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($paymentReference) {
            $data['payment_reference'] = $paymentReference;
        }
        
        return $this->update($claimId, $data);
    }
    
    public function getClaimStatistics()
    {
        $sql = "SELECT 
                    COUNT(*) as total_claims,
                    COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_claims,
                    COUNT(CASE WHEN status = 'approved' THEN 1 END) as approved_claims,
                    COUNT(CASE WHEN status = 'rejected' THEN 1 END) as rejected_claims,
                    COUNT(CASE WHEN status = 'processed' THEN 1 END) as processed_claims,
                    SUM(claim_amount) as total_claimed_amount,
                    SUM(CASE WHEN status = 'approved' OR status = 'processed' THEN approved_amount ELSE 0 END) as total_approved_amount
                FROM {$this->table}";
        
        return $this->db->fetch($sql);
    }
    
    public function getPendingClaimsCount()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'submitted'";
        $result = $this->db->fetch($sql);
        return $result['count'] ?? 0;
    }
    
    public function getApprovedClaimsCount()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'approved'";
        $result = $this->db->fetch($sql);
        return $result['count'] ?? 0;
    }
    
    public function getTotalClaimsValue()
    {
        $sql = "SELECT COALESCE(SUM(approved_amount), 0) as total_value
                FROM {$this->table} 
                WHERE status IN ('approved', 'paid')";
        
        $result = $this->db->fetch($sql);
        return $result['total_value'] ?? 0;
    }
    
    public function getPendingClaims()
    {
        $sql = "SELECT c.*, m.member_number, u.first_name, u.last_name 
                FROM {$this->table} c 
                JOIN members m ON c.member_id = m.id 
                JOIN users u ON m.user_id = u.id 
                WHERE c.status = 'pending' 
                ORDER BY c.created_at DESC";
        
        return $this->db->fetchAll($sql);
    }
    
    public function getApprovedClaims()
    {
        $sql = "SELECT c.*, m.member_number, u.first_name, u.last_name 
                FROM {$this->table} c 
                JOIN members m ON c.member_id = m.id 
                JOIN users u ON m.user_id = u.id 
                WHERE c.status = 'approved' 
                ORDER BY c.approved_at DESC";
        
        return $this->db->fetchAll($sql);
    }
    
    public function getProcessedClaims()
    {
        $sql = "SELECT c.*, m.member_number, u.first_name, u.last_name 
                FROM {$this->table} c 
                JOIN members m ON c.member_id = m.id 
                JOIN users u ON m.user_id = u.id 
                WHERE c.status = 'processed' 
                ORDER BY c.processed_at DESC";
        
        return $this->db->fetchAll($sql);
    }
    
    public function getRecentClaims($limit = 10)
    {
        $sql = "SELECT c.*, m.member_number, u.first_name, u.last_name 
                FROM {$this->table} c 
                JOIN members m ON c.member_id = m.id 
                JOIN users u ON m.user_id = u.id 
                ORDER BY c.created_at DESC 
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, ['limit' => $limit]);
    }
    
    public function getClaimDetails($claimId)
    {
        $sql = "SELECT 
                    c.*,
                    m.member_number,
                    m.package_id,
                    u.first_name,
                    u.last_name,
                    u.email,
                    u.phone
                FROM {$this->table} c
                JOIN members m ON c.member_id = m.id
                JOIN users u ON m.user_id = u.id
                WHERE c.id = :claim_id";
        
        return $this->db->fetch($sql, ['claim_id' => $claimId]);
    }
    
    public function getAllClaimsWithDetails($conditions = [])
    {
        $sql = "SELECT 
                    c.*,
                    m.member_number,
                    u.first_name,
                    u.last_name,
                    u.email,
                    u.phone
                FROM {$this->table} c
                JOIN members m ON c.member_id = m.id
                JOIN users u ON m.user_id = u.id";
        
        $params = [];
        $where_clauses = [];
        
        if (!empty($conditions)) {
            foreach ($conditions as $field => $value) {
                if ($field === 'status' && $value !== 'all') {
                    $where_clauses[] = "c.status = :status";
                    $params['status'] = $value;
                }
                if ($field === 'start_date') {
                    $where_clauses[] = "c.created_at >= :start_date";
                    $params['start_date'] = $value;
                }
                if ($field === 'end_date') {
                    $where_clauses[] = "c.created_at <= :end_date";
                    $params['end_date'] = $value;
                }
            }
        }
        
        if (!empty($where_clauses)) {
            $sql .= " WHERE " . implode(" AND ", $where_clauses);
        }
        
        $sql .= " ORDER BY c.created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getClaimReport($startDate, $endDate)
    {
        $sql = "SELECT 
                    DATE(c.created_at) as claim_date,
                    COUNT(*) as total_claims,
                    SUM(c.claim_amount) as total_amount,
                    SUM(CASE WHEN c.status = 'approved' OR c.status = 'processed' THEN c.approved_amount ELSE 0 END) as approved_amount,
                    COUNT(CASE WHEN c.status = 'approved' THEN 1 END) as approved_count,
                    COUNT(CASE WHEN c.status = 'rejected' THEN 1 END) as rejected_count
                FROM {$this->table} c
                WHERE c.created_at BETWEEN :start_date AND :end_date
                GROUP BY DATE(c.created_at)
                ORDER BY claim_date DESC";
        
        return $this->db->fetchAll($sql, [
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }
    
    public function getMonthlyClaims()
    {
        $sql = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as count,
                    SUM(claim_amount) as total_amount
                FROM {$this->table} 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month DESC";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get claim with dependent information
     * 
     * @param int $claimId Claim ID
     * @return array|null Claim details with dependent info
     */
    public function getClaimWithDependent($claimId)
    {
        $sql = "SELECT c.*, m.member_number, u.first_name, u.last_name, u.email, u.phone,
                       d.full_name as dependent_name, d.relationship as dependent_relationship,
                       d.date_of_birth as dependent_dob
                FROM {$this->table} c 
                JOIN members m ON c.member_id = m.id 
                JOIN users u ON m.user_id = u.id
                LEFT JOIN dependents d ON c.dependent_id = d.id
                WHERE c.id = :claim_id";
        
        return $this->db->fetch($sql, ['claim_id' => $claimId]);
    }
    
    /**
     * Get all claims with dependent information
     * 
     * @param array $conditions Filter conditions
     * @return array List of claims
     */
    public function getAllClaimsWithDependents($conditions = [])
    {
        $sql = "SELECT c.*, m.member_number, u.first_name, u.last_name,
                       d.full_name as dependent_name, d.relationship as dependent_relationship
                FROM {$this->table} c 
                JOIN members m ON c.member_id = m.id 
                JOIN users u ON m.user_id = u.id
                LEFT JOIN dependents d ON c.dependent_id = d.id";
        
        if (!empty($conditions)) {
            $where_clauses = [];
            foreach ($conditions as $column => $value) {
                $where_clauses[] = "c.{$column} = :{$column}";
            }
            $sql .= " WHERE " . implode(' AND ', $where_clauses);
        }
        
        $sql .= " ORDER BY c.created_at DESC";
        
        return $this->db->fetchAll($sql, $conditions);
    }
}
