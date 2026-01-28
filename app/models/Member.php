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
            $where_clauses = [];
            foreach ($conditions as $column => $value) {
                $where_clauses[] = "m.{$column} = :{$column}";
            }
            $sql .= " WHERE " . implode(' AND ', $where_clauses);
        }
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        return $this->db->fetchAll($sql, $conditions);
    }
    
    /**
     * Calculate age from date of birth
     * 
     * @param string $dateOfBirth Date in format YYYY-MM-DD
     * @return int Age in years
     */
    public function calculateAge($dateOfBirth)
    {
        return calculateAge($dateOfBirth); // Use global function from functions.php
    }
    
    /**
     * Get monthly contribution for a package (in KES)
     * 
     * @param string $packageKey Package identifier
     * @return int|null Monthly contribution amount
     */
    public function getMonthlyContribution($packageKey)
    {
        $package = getPackageDetails($packageKey);
        return $package['monthly_contribution'] ?? null;
    }
    
    /**
     * Get maturity period for a member based on age
     * Maturity period is the waiting time before coverage becomes active
     * 
     * @param int $age Member's age
     * @return int Maturity period in months (4 or 5)
     */
    public function getMaturityPeriodMonths($age)
    {
        return getMaturityPeriodMonths($age); // Use global function from functions.php
    }
    
    /**
     * Get all available packages for a specific age
     * 
     * @param int $age Member's age
     * @return array Available packages for this age
     */
    public function getAvailablePackagesByAge($age)
    {
        global $membership_packages;
        
        if (!isAgeEligible($age)) {
            return [];
        }

        $availablePackages = [];
        foreach ($membership_packages as $key => $package) {
            if ($age >= $package['age_min'] && $age <= $package['age_max']) {
                $availablePackages[$key] = $package;
            }
        }

        return $availablePackages;
    }
    
    /**
     * Get recommended individual package for age
     * 
     * @param int $age Member's age
     * @return array|null Package details
     */
    public function getIndividualPackageByAge($age)
    {
        global $membership_packages;
        
        if ($age >= 18 && $age <= 69) {
            return $membership_packages['individual_below_70'];
        } elseif ($age >= 71 && $age <= 80) {
            return $membership_packages['individual_71_80'];
        } elseif ($age >= 81 && $age <= 90) {
            return $membership_packages['individual_81_90'];
        } elseif ($age >= 91 && $age <= 100) {
            return $membership_packages['individual_91_100'];
        }
        
        return null;
    }

    /**
     * Get recommended executive package for age
     * 
     * @param int $age Member's age
     * @return array|null Package details
     */
    public function getExecutivePackageByAge($age)
    {
        global $membership_packages;
        
        if ($age >= 18 && $age <= 69) {
            return $membership_packages['executive_below_70'];
        } elseif ($age >= 70 && $age <= 100) {
            return $membership_packages['executive_above_70'];
        }
        
        return null;
    }
    
    public function getMembersInGracePeriod()
    {
        $sql = "SELECT m.*, u.email, u.phone 
                FROM {$this->table} m
                JOIN users u ON m.user_id = u.id
                WHERE m.status = 'grace_period'";
        return $this->db->fetchAll($sql);
    }
    
    public function getExpiredMembers()
    {
        $sql = "SELECT m.*, u.email, u.phone 
                FROM {$this->table} m
                JOIN users u ON m.user_id = u.id
                WHERE m.status = 'defaulted'
                OR m.status = 'inactive'";
        return $this->db->fetchAll($sql);
    }
    
    public function getMembersByPackage($package)
    {
        return $this->findAll(['package' => $package], 'created_at DESC');
    }
    
    public function updateGracePeriod($memberId, $gracePeriodExpires)
    {
        return $this->update($memberId, ['grace_period_expires' => $gracePeriodExpires]);
    }
    
    public function reactivateMember($memberId)
    {
        return $this->update($memberId, ['status' => 'active', 'grace_period_expires' => null]);
    }
    
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
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'pending'";
        $result = $this->db->fetch($sql);
        return $result['count'] ?? 0;
    }
    
    public function getMembersByStatus($status)
    {
        return $this->findAll(['status' => $status], 'created_at DESC');
    }
    
    public function getRecentMembers($limit = 10)
    {
        $sql = "SELECT m.*, u.email, u.phone, u.created_at as registration_date
                FROM {$this->table} m
                JOIN users u ON m.user_id = u.id
                ORDER BY m.created_at DESC
                LIMIT :limit";
        return $this->db->fetchAll($sql, ['limit' => $limit]);
    }

    public function getNewRegistrations($startDate, $endDate)
    {
        $sql = "SELECT COUNT(*) as count 
                FROM {$this->table} m
                WHERE m.created_at BETWEEN :start_date AND :end_date";
        $result = $this->db->fetch($sql, ['start_date' => $startDate, 'end_date' => $endDate]);
        return $result['count'] ?? 0;
    }

    public function getActivations($startDate, $endDate)
    {
        $sql = "SELECT COUNT(*) as count 
                FROM {$this->table} m
                WHERE m.status = 'active'
                AND m.maturity_ends BETWEEN :start_date AND :end_date";
        $result = $this->db->fetch($sql, ['start_date' => $startDate, 'end_date' => $endDate]);
        return $result['count'] ?? 0;
    }

    public function getInactiveMembers()
    {
        $sql = "SELECT m.*, u.email, u.phone 
                FROM {$this->table} m
                JOIN users u ON m.user_id = u.id
                WHERE m.status IN ('inactive', 'defaulted')";
        return $this->db->fetchAll($sql);
    }

    public function getAllMembers()
    {
        return $this->findAll([], 'created_at DESC');
    }

    public function getMembersByPackageReport()
    {
        $sql = "SELECT package, COUNT(*) as count, COUNT(CASE WHEN status = 'active' THEN 1 END) as active_count
                FROM {$this->table}
                GROUP BY package
                ORDER BY count DESC";
        return $this->db->fetchAll($sql);
    }

    public function getAllMembersWithDetails($search = '', $status = 'all', $package = 'all')
    {
        $sql = "SELECT m.*, u.email, u.phone, u.first_name, u.last_name
                FROM {$this->table} m
                JOIN users u ON m.user_id = u.id
                WHERE 1=1";

        $params = [];

        if (!empty($search)) {
            $sql .= " AND (u.first_name LIKE :search OR u.last_name LIKE :search OR m.member_number LIKE :search OR u.phone LIKE :search)";
            $params['search'] = '%' . $search . '%';
        }

        if ($status !== 'all') {
            $sql .= " AND m.status = :status";
            $params['status'] = $status;
        }

        if ($package !== 'all') {
            $sql .= " AND m.package = :package";
            $params['package'] = $package;
        }

        $sql .= " ORDER BY m.created_at DESC";

        return $this->db->fetchAll($sql, $params);
    }

    public function activateMember($memberId, $reason = '')
    {
        return $this->update($memberId, ['status' => 'active']);
    }

    public function deactivateMember($memberId, $reason = '')
    {
        return $this->update($memberId, ['status' => 'inactive']);
    }

    public function getActiveMembersList()
    {
        $sql = "SELECT m.*, u.email, u.phone, u.first_name, u.last_name
                FROM {$this->table} m
                JOIN users u ON m.user_id = u.id
                WHERE m.status = 'active'
                ORDER BY m.created_at DESC";
        return $this->db->fetchAll($sql);
    }

    public function getInactiveMembersList()
    {
        $sql = "SELECT m.*, u.email, u.phone, u.first_name, u.last_name
                FROM {$this->table} m
                JOIN users u ON m.user_id = u.id
                WHERE m.status IN ('inactive', 'defaulted')
                ORDER BY m.created_at DESC";
        return $this->db->fetchAll($sql);
    }
}