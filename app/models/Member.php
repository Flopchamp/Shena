<?php
/**
 * Member Model - Handles member-specific data and operations
 */
class Member extends BaseModel 
{
    protected $table = 'members';
    
    public function findByUserId($userId)
    {
        $sql = "SELECT m.*, u.email, u.phone, u.status as user_status 
                FROM {$this->table} m 
                JOIN users u ON m.user_id = u.id 
                WHERE m.user_id = :user_id";
        return $this->db->fetch($sql, ['user_id' => $userId]);
    }
    
    public function getMemberWithUser($memberId)
    {
        $sql = "SELECT m.*, u.email, u.phone, u.status as user_status, u.created_at as registration_date
                FROM {$this->table} m 
                JOIN users u ON m.user_id = u.id 
                WHERE m.id = :id";
        return $this->db->fetch($sql, ['id' => $memberId]);
    }
    
    public function getAllMembersWithUsers($conditions = [], $orderBy = 'created_at DESC', $limit = null)
    {
        $sql = "SELECT m.*, u.email, u.phone, u.status as user_status, u.created_at as registration_date
                FROM {$this->table} m 
                JOIN users u ON m.user_id = u.id";
        
        if (!empty($conditions)) {
            $where = [];
            foreach (array_keys($conditions) as $field) {
                // Handle table prefixes for conditions
                if (strpos($field, '.') === false) {
                    $field = 'm.' . $field;
                }
                $where[] = "{$field} = :{$field}";
            }
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        return $this->db->fetchAll($sql, $conditions);
    }
    
    public function calculateAge($dateOfBirth)
    {
        $today = new DateTime();
        $dob = new DateTime($dateOfBirth);
        return $today->diff($dob)->y;
    }
    
    public function calculateMonthlyContribution($package, $age)
    {
        global $membership_packages;
        
        if (!isset($membership_packages[$package])) {
            return 0;
        }
        
        $packageData = $membership_packages[$package];
        $basePrice = $packageData['base_price'];
        
        // Apply age-based multipliers for individual packages
        if ($package === 'individual' && isset($packageData['age_multipliers'])) {
            $multiplier = 1.0; // default
            
            foreach ($packageData['age_multipliers'] as $ageRange => $mult) {
                list($minAge, $maxAge) = explode('-', $ageRange);
                if ($age >= $minAge && $age <= $maxAge) {
                    $multiplier = $mult;
                    break;
                }
            }
            
            $basePrice *= $multiplier;
        }
        
        // Apply package discounts
        if (isset($packageData['discount'])) {
            $basePrice *= (1 - $packageData['discount']);
        }
        
        return round($basePrice, 2);
    }
    
    public function getGracePeriodMonths($age)
    {
        return $age >= 80 ? GRACE_PERIOD_80_AND_ABOVE : GRACE_PERIOD_UNDER_80;
    }
    
    public function getMembersInGracePeriod()
    {
        $sql = "SELECT m.*, u.email, u.phone 
                FROM {$this->table} m 
                JOIN users u ON m.user_id = u.id 
                WHERE m.grace_period_expires IS NOT NULL 
                AND m.grace_period_expires > NOW() 
                AND m.status = 'grace_period'";
        return $this->db->fetchAll($sql);
    }
    
    public function getExpiredMembers()
    {
        $sql = "SELECT m.*, u.email, u.phone 
                FROM {$this->table} m 
                JOIN users u ON m.user_id = u.id 
                WHERE (m.grace_period_expires IS NOT NULL AND m.grace_period_expires < NOW()) 
                OR m.status = 'defaulted'";
        return $this->db->fetchAll($sql);
    }
    
    public function getMembersByPackage($package)
    {
        return $this->getAllMembersWithUsers(['package' => $package]);
    }
    
    public function updateGracePeriod($memberId, $gracePeriodExpires)
    {
        return $this->update($memberId, [
            'grace_period_expires' => $gracePeriodExpires,
            'status' => 'grace_period'
        ]);
    }
    
    public function reactivateMember($memberId)
    {
        return $this->update($memberId, [
            'status' => 'active',
            'grace_period_expires' => null,
            'reactivated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Statistical methods for dashboard
     */
    public function getTotalMembers()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $result = $this->db->fetch($sql);
        return $result['count'] ?? 0;
    }
    
    public function getActiveMembers()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'active'";
        $result = $this->db->fetch($sql);
        return $result['count'] ?? 0;
    }
    
    public function getPendingMembers()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'pending' OR status = 'inactive'";
        $result = $this->db->fetch($sql);
        return $result['count'] ?? 0;
    }
    
    public function getMembersByStatus($status)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = :status";
        $result = $this->db->fetch($sql, ['status' => $status]);
        return $result['count'] ?? 0;
    }
    
    public function getRecentMembers($limit = 10)
    {
        $sql = "SELECT m.*, u.first_name, u.last_name, u.email, u.phone, u.created_at as registration_date
                FROM {$this->table} m 
                JOIN users u ON m.user_id = u.id 
                ORDER BY m.created_at DESC 
                LIMIT " . (int)$limit;
        
        return $this->db->fetchAll($sql, []);
    }

    /**
     * Get new registrations within a date range
     */
    public function getNewRegistrations($startDate, $endDate)
    {
        $sql = "SELECT m.*, u.first_name, u.last_name, u.email, u.phone 
                FROM {$this->table} m 
                JOIN users u ON m.user_id = u.id 
                WHERE m.created_at BETWEEN ? AND ? 
                ORDER BY m.created_at DESC";
        
        return $this->db->fetchAll($sql, [$startDate, $endDate]);
    }

    /**
     * Get member activations within a date range
     */
    public function getActivations($startDate, $endDate)
    {
        $sql = "SELECT m.*, u.first_name, u.last_name, u.email, u.phone 
                FROM {$this->table} m 
                JOIN users u ON m.user_id = u.id 
                WHERE m.status = 'active' 
                AND (m.updated_at BETWEEN ? AND ? OR m.reactivated_at BETWEEN ? AND ?)
                ORDER BY COALESCE(m.reactivated_at, m.updated_at) DESC";
        
        return $this->db->fetchAll($sql, [$startDate, $endDate, $startDate, $endDate]);
    }

    /**
     * Get inactive members count
     */
    public function getInactiveMembers()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'inactive'";
        $result = $this->db->fetch($sql);
        return $result['count'] ?? 0;
    }

    /**
     * Get all members (for communications)
     */
    public function getAllMembers()
    {
        $sql = "SELECT m.*, u.first_name, u.last_name, u.email, u.phone 
                FROM {$this->table} m 
                JOIN users u ON m.user_id = u.id 
                ORDER BY u.first_name, u.last_name";
        
        return $this->db->fetchAll($sql, []);
    }

    /**
     * Get members by package report
     */
    public function getMembersByPackageReport()
    {
        $sql = "SELECT 
                    package,
                    COUNT(*) as total_members,
                    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_members,
                    SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive_members
                FROM {$this->table} 
                GROUP BY package 
                ORDER BY package";
        
        return $this->db->fetchAll($sql, []);
    }

    /**
     * Get all members with details for admin management
     */
    public function getAllMembersWithDetails($search = '', $status = 'all', $package = 'all')
    {
        $sql = "SELECT m.*, u.first_name, u.last_name, u.email, u.phone 
                FROM {$this->table} m 
                JOIN users u ON m.user_id = u.id 
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (u.first_name LIKE ? OR u.last_name LIKE ? OR m.member_number LIKE ? OR u.email LIKE ?)";
            $searchTerm = "%$search%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        }
        
        if ($status !== 'all') {
            $sql .= " AND m.status = ?";
            $params[] = $status;
        }
        
        if ($package !== 'all') {
            $sql .= " AND m.package = ?";
            $params[] = $package;
        }
        
        $sql .= " ORDER BY m.created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Activate member
     */
    public function activateMember($memberId, $reason = '')
    {
        $sql = "UPDATE {$this->table} SET status = 'active', updated_at = NOW() WHERE id = :id";
        return $this->db->query($sql, ['id' => $memberId]);
    }

    /**
     * Deactivate member
     */
    public function deactivateMember($memberId, $reason = '')
    {
        $sql = "UPDATE {$this->table} SET status = 'inactive', updated_at = NOW() WHERE id = :id";
        return $this->db->query($sql, ['id' => $memberId]);
    }

    /**
     * Get active members list for communications
     */
    public function getActiveMembersList()
    {
        $sql = "SELECT m.*, u.first_name, u.last_name, u.email, u.phone 
                FROM {$this->table} m 
                JOIN users u ON m.user_id = u.id 
                WHERE m.status = 'active' 
                ORDER BY u.first_name, u.last_name";
        
        return $this->db->fetchAll($sql, []);
    }

    /**
     * Get inactive members list for communications
     */
    public function getInactiveMembersList()
    {
        $sql = "SELECT m.*, u.first_name, u.last_name, u.email, u.phone 
                FROM {$this->table} m 
                JOIN users u ON m.user_id = u.id 
                WHERE m.status = 'inactive' 
                ORDER BY u.first_name, u.last_name";
        
        return $this->db->fetchAll($sql, []);
    }
}
