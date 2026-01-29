<?php
/**
 * Member Model
 * Manages member information and operations
 * 
 * @package Shena\Models
 */

class Member extends BaseModel
{
    protected $table = 'members';
    
    /**
     * Get all members with optional filters
     * 
     * @param array $filters Optional filters (status, search, package)
     * @return array List of members
     */
    public function getAllMembers($filters = [])
    {
        $sql = "SELECT m.*, u.email, u.phone, u.first_name, u.last_name, u.role
                FROM {$this->table} m
                JOIN users u ON m.user_id = u.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['status'])) {
            $sql .= " AND m.status = :status";
            $params['status'] = $filters['status'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (m.member_number LIKE :search 
                      OR u.first_name LIKE :search 
                      OR u.last_name LIKE :search 
                      OR u.email LIKE :search 
                      OR u.phone LIKE :search)";
            $params['search'] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($filters['package'])) {
            $sql .= " AND m.package = :package";
            $params['package'] = $filters['package'];
        }
        
        $sql .= " ORDER BY m.created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get member by ID
     * 
     * @param int $id Member ID
     * @return array|null Member data or null
     */
    public function getMemberById($id)
    {
        $sql = "SELECT m.*, u.email, u.phone, u.first_name, u.last_name
                FROM {$this->table} m
                JOIN users u ON m.user_id = u.id
                WHERE m.id = :id";
        
        return $this->db->fetch($sql, ['id' => $id]);
    }
    
    /**
     * Get member by user ID
     * 
     * @param int $userId User ID
     * @return array|null Member data or null
     */
    public function getMemberByUserId($userId)
    {
        $sql = "SELECT m.*, u.email, u.phone, u.first_name, u.last_name
                FROM {$this->table} m
                JOIN users u ON m.user_id = u.id
                WHERE m.user_id = :user_id";
        
        return $this->db->fetch($sql, ['user_id' => $userId]);
    }
    
    /**
     * Alias for getMemberByUserId
     * 
     * @param int $userId User ID
     * @return array|null Member data or null
     */
    public function findByUserId($userId)
    {
        return $this->getMemberByUserId($userId);
    }
    
    /**
     * Get member by member number
     * 
     * @param string $memberNumber Member number
     * @return array|null Member data or null
     */
    public function getMemberByNumber($memberNumber)
    {
        $sql = "SELECT m.*, u.email, u.phone, u.first_name, u.last_name
                FROM {$this->table} m
                JOIN users u ON m.user_id = u.id
                WHERE m.member_number = :member_number";
        
        return $this->db->fetch($sql, ['member_number' => $memberNumber]);
    }
    
    /**
     * Create a new member
     * 
     * @param array $data Member data
     * @return int|false Member ID or false on failure
     */
    public function createMember($data)
    {
        return $this->create($data);
    }
    
    /**
     * Update member information
     * 
     * @param int $id Member ID
     * @param array $data Update data
     * @return bool Success status
     */
    public function updateMember($id, $data)
    {
        return $this->update($id, $data);
    }
    
    /**
     * Get members registered by a specific agent
     * 
     * @param int $agentId Agent ID
     * @param int $limit Optional limit for results
     * @return array List of members
     */
    public function getMembersByAgent($agentId, $limit = null)
    {
        $sql = "SELECT m.*, u.email, u.phone, u.first_name, u.last_name
                FROM {$this->table} m
                JOIN users u ON m.user_id = u.id
                WHERE m.agent_id = :agent_id
                ORDER BY m.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT :limit";
            return $this->db->fetchAll($sql, ['agent_id' => $agentId, 'limit' => $limit]);
        }
        
        return $this->db->fetchAll($sql, ['agent_id' => $agentId]);
    }
    
    /**
     * Count total active members
     * 
     * @return int Member count
     */
    public function countActiveMembers()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'active'";
        $result = $this->db->fetch($sql);
        return $result['count'] ?? 0;
    }
    
    /**
     * Get members with expiring coverage
     * 
     * @param int $days Days until coverage expires
     * @return array List of members
     */
    public function getMembersWithExpiringCoverage($days = 7)
    {
        $sql = "SELECT m.*, u.email, u.phone, u.first_name, u.last_name
                FROM {$this->table} m
                JOIN users u ON m.user_id = u.id
                WHERE m.coverage_ends IS NOT NULL
                AND m.coverage_ends BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL :days DAY)
                AND m.status = 'active'
                ORDER BY m.coverage_ends ASC";
        
        return $this->db->fetchAll($sql, ['days' => $days]);
    }
    
    /**
     * Calculate age from date of birth
     * 
     * @param string $dateOfBirth Date of birth (YYYY-MM-DD)
     * @return int Age in years
     */
    public function calculateAge($dateOfBirth)
    {
        if (empty($dateOfBirth) || $dateOfBirth === '0000-00-00') {
            return 0;
        }
        
        $birthDate = new DateTime($dateOfBirth);
        $today = new DateTime('today');
        return $birthDate->diff($today)->y;
    }
    
    /**
     * Find member by national ID
     * 
     * @param string $nationalId National ID number
     * @return array|null Member data or null
     */
    public function findByNationalId($nationalId)
    {
        $sql = "SELECT m.*, u.email, u.phone, u.first_name, u.last_name
                FROM {$this->table} m
                JOIN users u ON m.user_id = u.id
                WHERE m.id_number = :id_number";
        
        return $this->db->fetch($sql, ['id_number' => $nationalId]);
    }
    
    /**
     * Get last member registered in a specific year (for member number generation)
     * 
     * @param int $year Year (e.g., 2026)
     * @return array|null Last member data or null
     */
    public function getLastMemberByYear($year)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE YEAR(created_at) = :year 
                ORDER BY member_number DESC 
                LIMIT 1";
        
        return $this->db->fetch($sql, ['year' => $year]);
    }
}
