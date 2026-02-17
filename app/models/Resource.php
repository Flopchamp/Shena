<?php
/**
 * Resource Model
 * Handles CRUD operations for agent resources/downloads
 */
class Resource extends BaseModel
{
    protected $table = 'resources';
    
    /**
     * Create a new resource
     * 
     * @param array $data Resource data
     * @return int|false Resource ID or false on failure
     */
    public function create($data)

    {
        $sql = "INSERT INTO resources (
            title, description, file_name, file_path, original_name,
            file_size, mime_type, category, uploaded_by, is_active
        ) VALUES (
            :title, :description, :file_name, :file_path, :original_name,
            :file_size, :mime_type, :category, :uploaded_by, :is_active
        )";
        
        $params = [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'file_name' => $data['file_name'],
            'file_path' => $data['file_path'],
            'original_name' => $data['original_name'] ?? $data['file_name'],
            'file_size' => $data['file_size'] ?? 0,
            'mime_type' => $data['mime_type'] ?? 'application/octet-stream',
            'category' => $data['category'] ?? 'other',
            'uploaded_by' => $data['uploaded_by'],
            'is_active' => isset($data['is_active']) ? $data['is_active'] : true
        ];
        
        $this->db->execute($sql, $params);
        return $this->db->getConnection()->lastInsertId();
    }
    
    /**
     * Get all resources with optional filtering
     * 
     * @param array $filters Optional filters (category, is_active)
     * @return array Array of resources
     */
    public function getAll(array $filters = [])
    {
        $sql = "SELECT r.*, 
                CONCAT(u.first_name, ' ', u.last_name) as uploaded_by_name
                FROM resources r
                LEFT JOIN users u ON r.uploaded_by = u.id
                WHERE 1=1";
        $params = [];
        
        if (isset($filters['category']) && $filters['category'] !== 'all') {
            $sql .= " AND r.category = :category";
            $params['category'] = $filters['category'];
        }
        
        if (isset($filters['is_active'])) {
            $sql .= " AND r.is_active = :is_active";
            $params['is_active'] = $filters['is_active'] ? 1 : 0;
        }
        
        $sql .= " ORDER BY r.created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get resources by category
     * 
     * @param string $category Resource category
     * @param bool $activeOnly Only get active resources
     * @return array Array of resources
     */
    public function getByCategory($category, $activeOnly = true)
    {
        $filters = ['category' => $category];
        if ($activeOnly) {
            $filters['is_active'] = true;
        }
        return $this->getAll($filters);
    }
    
    /**
     * Get a single resource by ID
     * 
     * @param int $id Resource ID
     * @return array|false Resource data or false
     */
    public function getById($id)
    {
        $sql = "SELECT r.*, 
                CONCAT(u.first_name, ' ', u.last_name) as uploaded_by_name
                FROM resources r
                LEFT JOIN users u ON r.uploaded_by = u.id
                WHERE r.id = :id
                LIMIT 1";
        
        return $this->db->fetch($sql, ['id' => $id]);
    }
    
    /**
     * Update a resource
     * 
     * @param int $id Resource ID
     * @param array $data Update data
     * @return bool Success status
     */
    public function update($id, $data)

    {
        $allowedFields = ['title', 'description', 'category', 'is_active'];
        $updates = [];
        $params = ['id' => $id];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updates[] = "$field = :$field";
                $params[$field] = $data[$field];
            }
        }
        
        if (empty($updates)) {
            return false;
        }
        
        $sql = "UPDATE resources SET " . implode(', ', $updates) . ", updated_at = NOW() WHERE id = :id";
        return $this->db->execute($sql, $params);
    }
    
    /**
     * Delete a resource
     * 
     * @param int $id Resource ID
     * @return bool Success status
     */
    public function delete($id)
    {
        // First get the file path to delete the physical file
        $resource = $this->getById($id);
        if ($resource && file_exists($resource['file_path'])) {
            unlink($resource['file_path']);
        }
        
        $sql = "DELETE FROM resources WHERE id = :id";
        return $this->db->execute($sql, ['id' => $id]);
    }
    
    /**
     * Increment download count
     * 
     * @param int $id Resource ID
     * @return bool Success status
     */
    public function incrementDownloadCount($id)
    {
        $sql = "UPDATE resources SET download_count = download_count + 1 WHERE id = :id";
        return $this->db->execute($sql, ['id' => $id]);
    }
    
    /**
     * Record a download
     * 
     * @param int $resourceId Resource ID
     * @param int $userId User ID who downloaded
     * @param string $ipAddress IP address
     * @param string $userAgent User agent string
     * @return bool Success status
     */
    public function recordDownload($resourceId, $userId, $ipAddress = null, $userAgent = null)
    {
        $sql = "INSERT INTO resource_downloads (
            resource_id, user_id, ip_address, user_agent
        ) VALUES (
            :resource_id, :user_id, :ip_address, :user_agent
        )";
        
        return $this->db->execute($sql, [
            'resource_id' => $resourceId,
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent
        ]);
    }
    
    /**
     * Get download history for a resource
     * 
     * @param int $resourceId Resource ID
     * @return array Download history
     */
    public function getDownloadHistory($resourceId)
    {
        $sql = "SELECT rd.*, 
                CONCAT(u.first_name, ' ', u.last_name) as user_name,
                u.email as user_email
                FROM resource_downloads rd
                LEFT JOIN users u ON rd.user_id = u.id
                WHERE rd.resource_id = :resource_id
                ORDER BY rd.downloaded_at DESC";
        
        return $this->db->fetchAll($sql, ['resource_id' => $resourceId]);
    }
    
    /**
     * Get download count for a resource
     * 
     * @param int $resourceId Resource ID
     * @return int Download count
     */
    public function getDownloadCount($resourceId)
    {
        $sql = "SELECT COUNT(*) as count FROM resource_downloads WHERE resource_id = :resource_id";
        $result = $this->db->fetch($sql, ['resource_id' => $resourceId]);
        return $result ? (int)$result['count'] : 0;
    }
    
    /**
     * Get resources grouped by category
     * 
     * @param bool $activeOnly Only get active resources
     * @return array Resources grouped by category
     */
    public function getGroupedByCategory($activeOnly = true)
    {
        $resources = $this->getAll($activeOnly ? ['is_active' => true] : []);
        
        $grouped = [
            'marketing_materials' => [],
            'training_documents' => [],
            'policy_documents' => [],
            'forms' => [],
            'other' => []
        ];
        
        foreach ($resources as $resource) {
            $category = $resource['category'] ?? 'other';
            if (!isset($grouped[$category])) {
                $category = 'other';
            }
            $grouped[$category][] = $resource;
        }
        
        return $grouped;
    }
    
    /**
     * Format file size for display
     * 
     * @param int $bytes File size in bytes
     * @return string Formatted file size
     */
    public static function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
    
    /**
     * Get category label
     * 
     * @param string $category Category key
     * @return string Category label
     */
    public static function getCategoryLabel($category)
    {
        $labels = [
            'marketing_materials' => 'Marketing Materials',
            'training_documents' => 'Training Documents',
            'policy_documents' => 'Policy Documents',
            'forms' => 'Forms',
            'other' => 'Other'
        ];
        
        return $labels[$category] ?? 'Other';
    }
    
    /**
     * Get category icon
     * 
     * @param string $category Category key
     * @return string Font Awesome icon class
     */
    public static function getCategoryIcon($category)
    {
        $icons = [
            'marketing_materials' => 'fa-bullhorn',
            'training_documents' => 'fa-graduation-cap',
            'policy_documents' => 'fa-file-contract',
            'forms' => 'fa-file-alt',
            'other' => 'fa-file'
        ];
        
        return $icons[$category] ?? 'fa-file';
    }
}
