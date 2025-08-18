<?php
/**
 * User Model - Handles user authentication and basic user data
 */
class User extends BaseModel 
{
    protected $table = 'users';
    
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        return $this->db->fetch($sql, ['email' => $email]);
    }
    
    public function findByPhone($phone)
    {
        $sql = "SELECT * FROM {$this->table} WHERE phone = :phone";
        return $this->db->fetch($sql, ['phone' => $phone]);
    }
    
    public function createUser($data)
    {
        // Hash password before storing
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        $data['status'] = 'pending'; // New users start as pending
        $data['role'] = 'member'; // Default role
        
        return $this->create($data);
    }
    
    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
    
    public function updatePassword($userId, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($userId, ['password' => $hashedPassword]);
    }
    
    public function activateUser($userId)
    {
        return $this->update($userId, ['status' => 'active']);
    }
    
    public function deactivateUser($userId)
    {
        return $this->update($userId, ['status' => 'inactive']);
    }
    
    public function getUsersByRole($role)
    {
        return $this->findAll(['role' => $role]);
    }
    
    public function getPendingUsers()
    {
        return $this->findAll(['status' => 'pending'], 'created_at DESC');
    }
}
