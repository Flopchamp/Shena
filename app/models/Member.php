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
     * Calculate monthly contribution for a given configured package and age.
     *
     * This method is the concrete implementation expected by AuthController::register()
     * and simply defers to the configuration-driven package definitions so that
     * contributions stay aligned with the policy booklet.
     *
     * @param string $packageKey Key in $membership_packages (e.g. individual_below_70)
     * @param int    $age        Member age (18-100). Currently used only for validation.
     * @return float             Monthly contribution amount
     * @throws Exception         If the package is not available for the given age
     */
    public function calculateMonthlyContribution($packageKey, $age)
    {
        global $membership_packages;
        
        if (!isset($membership_packages[$packageKey])) {
            throw new Exception("Unknown membership package: {$packageKey}");
        }
        
        $package = $membership_packages[$packageKey];
        
        // Ensure the chosen package is valid for the member's age, as per policy ranges
        if ($age < $package['age_min'] || $age > $package['age_max']) {
            throw new Exception("Selected package is not valid for age {$age}");
        }
        
        return (float)($package['monthly_contribution'] ?? 0);
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
        // Keep backwards-compatible behaviour by filtering on the high-level category.
        // Callers that need specific package breakdowns should use package_key instead.
        return $this->findAll(['package' => $package], 'created_at DESC');
    }

    /**
     * Apply effects of a successful completed monthly payment on a member's record.
     *
     * - Extends coverage_ends by one month from either today or the existing coverage_ends,
     *   whichever is later.
     * - Clears any grace_period_expires.
     * - If the maturity period has already ended, moves the member into "active" status.
     *
     * This encodes the policy that cover is only active after the maturity period, but
     * allows contributions to be collected beforehand.
     *
     * @param int    $memberId
     * @param string $paymentDate Y-m-d H:i:s
     */
    public function applySuccessfulMonthlyPayment($memberId, $paymentDate)
    {
        $member = $this->find($memberId);
        if (!$member) {
            return;
        }

        $paymentDay = date('Y-m-d', strtotime($paymentDate));
        $currentCoverage = !empty($member['coverage_ends']) ? $member['coverage_ends'] : null;

        if ($currentCoverage && $currentCoverage > $paymentDay) {
            $baseDate = $currentCoverage;
        } else {
            $baseDate = $paymentDay;
        }

        $newCoverageEnds = date('Y-m-d', strtotime('+1 month', strtotime($baseDate)));

        $updates = [
            'coverage_ends' => $newCoverageEnds,
            'grace_period_expires' => null
        ];

        // Activate member only if maturity period is complete
        $today = date('Y-m-d');
        if (!empty($member['maturity_ends']) && $member['maturity_ends'] <= $today) {
            if (in_array($member['status'], ['inactive', 'grace_period', 'defaulted', 'suspended'])) {
                $updates['status'] = 'active';
            }
        }

        $this->update($memberId, $updates);
    }
    
    public function updateGracePeriod($memberId, $gracePeriodExpires)
    {
        return $this->update($memberId, ['grace_period_expires' => $gracePeriodExpires]);
    }
    
    public function reactivateMember($memberId)
    {
        // Policy: To reactivate after default, member pays arrears + KES 100
        // and starts a fresh maturity period of 4 months before cover resumes.
        $reactivatedAt = date('Y-m-d');
        $newMaturityEnds = date('Y-m-d', strtotime('+4 months'));

        return $this->update($memberId, [
            'status' => 'inactive', // membership reactivated but cover waits for new maturity
            'grace_period_expires' => null,
            'reactivated_at' => $reactivatedAt,
            'maturity_ends' => $newMaturityEnds,
            'coverage_ends' => null
        ]);
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

    /**
     * Refresh a member's status based on coverage_ends and grace period rules.
     *
     * - If today is before or on coverage_ends, keep status as-is (active if maturity passed).
     * - If today is after coverage_ends but within GRACE_PERIOD_MONTHS, set status to grace_period.
     * - If today is after coverage_ends + GRACE_PERIOD_MONTHS, set status to defaulted.
     *
     * This is mainly used at claim-approval time to ensure default/grace logic
     * matches the policy even if a periodic job has not run.
     *
     * @param int $memberId
     * @return array|null Updated member row
     */
    public function refreshStatusFromCoverage($memberId)
    {
        $member = $this->find($memberId);
        if (!$member) {
            return null;
        }

        $today = new DateTimeImmutable('today');

        if (empty($member['coverage_ends'])) {
            // No coverage period yet; keep existing status
            return $member;
        }

        $coverageEnds = new DateTimeImmutable($member['coverage_ends']);

        // If coverage still valid, we don't downgrade status here
        if ($coverageEnds >= $today) {
            return $member;
        }

        // Compute end of grace period (2 months from coverage_ends by policy)
        $graceEnd = $coverageEnds->modify('+' . GRACE_PERIOD_MONTHS . ' months');

        $newStatus = $member['status'];
        $graceExpires = $member['grace_period_expires'];

        if ($today <= $graceEnd) {
            $newStatus = 'grace_period';
            $graceExpires = $graceEnd->format('Y-m-d H:i:s');
        } else {
            $newStatus = 'defaulted';
            $graceExpires = $graceEnd->format('Y-m-d H:i:s');
        }

        $this->update($memberId, [
            'status' => $newStatus,
            'grace_period_expires' => $graceExpires
        ]);

        // Return a fresh copy
        return $this->find($memberId);
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
    
    /**
     * Find members by multiple statuses
     */
    public function findByStatuses($statuses)
    {
        if (empty($statuses)) {
            return [];
        }
        
        $placeholders = implode(',', array_fill(0, count($statuses), '?'));
        $sql = "SELECT * FROM {$this->table} WHERE status IN ($placeholders)";
        
        return $this->db->fetchAll($sql, $statuses);
    }
    
    /**
     * Get all dependents for a member
     * 
     * @param int $memberId Member ID
     * @return array List of dependents
     */
    public function getDependents($memberId)
    {
        $dependentModel = new Dependent();
        return $dependentModel->getMemberDependents($memberId, true);
    }
    
    /**
     * Check if member's package allows dependents
     * 
     * @param int $memberId Member ID
     * @return bool True if package allows dependents
     */
    public function canHaveDependents($memberId)
    {
        $member = $this->find($memberId);
        if (!$member) {
            return false;
        }
        
        global $membership_packages;
        $packageKey = $member['package_key'];
        
        if (!isset($membership_packages[$packageKey])) {
            return false;
        }
        
        $package = $membership_packages[$packageKey];
        return $package['coverage_type'] !== 'principal_only';
    }
    
    /**
     * Validate if adding a dependent is allowed for this member's package
     * 
     * @param int $memberId Member ID
     * @param string $relationship Relationship type
     * @return array [allowed => bool, message => string]
     */
    public function canAddDependent($memberId, $relationship)
    {
        $member = $this->find($memberId);
        if (!$member) {
            return ['allowed' => false, 'message' => 'Member not found'];
        }
        
        global $membership_packages;
        $packageKey = $member['package_key'];
        
        if (!isset($membership_packages[$packageKey])) {
            return ['allowed' => false, 'message' => 'Invalid package'];
        }
        
        $package = $membership_packages[$packageKey];
        
        if ($package['coverage_type'] === 'principal_only') {
            return ['allowed' => false, 'message' => 'Your package does not cover dependents'];
        }
        
        $dependentModel = new Dependent();
        $counts = $dependentModel->countDependentsByRelationship($memberId);
        
        if ($relationship === 'spouse') {
            if (!in_array($package['coverage_type'], ['couple', 'couple_children', 'couple_children_parents', 'couple_children_parents_inlaws'])) {
                return ['allowed' => false, 'message' => 'Your package does not cover spouse'];
            }
            if ($counts['spouse'] >= 1) {
                return ['allowed' => false, 'message' => 'You already have a spouse registered'];
            }
        }
        
        if ($relationship === 'child') {
            if (!isset($package['max_children'])) {
                return ['allowed' => false, 'message' => 'Your package does not cover children'];
            }
            if ($counts['child'] >= $package['max_children']) {
                return ['allowed' => false, 'message' => "Maximum {$package['max_children']} children allowed for your package"];
            }
        }
        
        if ($relationship === 'parent') {
            if (!isset($package['max_parents'])) {
                return ['allowed' => false, 'message' => 'Your package does not cover parents'];
            }
            if ($counts['parent'] >= $package['max_parents']) {
                return ['allowed' => false, 'message' => "Maximum {$package['max_parents']} parents allowed for your package"];
            }
        }
        
        if (in_array($relationship, ['father_in_law', 'mother_in_law'])) {
            if (!isset($package['max_inlaws'])) {
                return ['allowed' => false, 'message' => 'Your package does not cover in-laws'];
            }
            $inlawCount = $counts['father_in_law'] + $counts['mother_in_law'];
            if ($inlawCount >= $package['max_inlaws']) {
                return ['allowed' => false, 'message' => "Maximum {$package['max_inlaws']} in-laws allowed for your package"];
            }
        }
        
        return ['allowed' => true, 'message' => 'Dependent can be added'];
    }
}