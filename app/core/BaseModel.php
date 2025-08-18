<?php
/**
 * Base Model Class
 */
abstract class BaseModel 
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        return $this->db->fetch($sql, ['id' => $id]);
    }
    
    public function findAll($conditions = [], $orderBy = null, $limit = null)
    {
        $sql = "SELECT * FROM {$this->table}";
        
        if (!empty($conditions)) {
            $where = [];
            foreach (array_keys($conditions) as $field) {
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
    
    public function create($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table, $data);
    }
    
    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->update($this->table, $data, "{$this->primaryKey} = :id", ['id' => $id]);
    }
    
    public function delete($id)
    {
        return $this->db->delete($this->table, "{$this->primaryKey} = :id", ['id' => $id]);
    }
    
    public function count($conditions = [])
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        
        if (!empty($conditions)) {
            $where = [];
            foreach (array_keys($conditions) as $field) {
                $where[] = "{$field} = :{$field}";
            }
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        
        $result = $this->db->fetch($sql, $conditions);
        return $result['total'];
    }
    
    public function paginate($page = 1, $perPage = 20, $conditions = [], $orderBy = null)
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM {$this->table}";
        
        if (!empty($conditions)) {
            $where = [];
            foreach (array_keys($conditions) as $field) {
                $where[] = "{$field} = :{$field}";
            }
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";
        
        $data = $this->db->fetchAll($sql, $conditions);
        $total = $this->count($conditions);
        
        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage)
        ];
    }
}
