<?php

namespace App\Models;

use PDO;
use Exception;

class User
{
    private $db;

    public function __construct()
    {
        $this->db = \Database::getInstance()->getConnection();
    }

    /**
     * Find user by ID (with soft delete check)
     */
    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM users 
            WHERE id = :id AND deleted_at IS NULL
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Find user by email within a tenant
     */
    public function findByEmail($email, $tenantId = null)
    {
        $sql = "SELECT * FROM users WHERE email = :email AND deleted_at IS NULL";
        $params = ['email' => $email];

        if ($tenantId) {
            $sql .= " AND tenant_id = :tenant_id";
            $params['tenant_id'] = $tenantId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    /**
     * Get all users (with optional tenant filter)
     */
    public function all($tenantId = null, $limit = 50, $offset = 0)
    {
        $sql = "SELECT * FROM users WHERE deleted_at IS NULL";
        $params = [];

        if ($tenantId) {
            $sql .= " AND tenant_id = :tenant_id";
            $params['tenant_id'] = $tenantId;
        }

        $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $params['limit'] = $limit;
        $params['offset'] = $offset;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Create new user
     */
    public function create(array $data)
    {
        $sql = "INSERT INTO users 
                (tenant_id, name, email, password, role, created_at, updated_at) 
                VALUES 
                (:tenant_id, :name, :email, :password, :role, NOW(), NOW())";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            'tenant_id' => $data['tenant_id'] ?? 1,
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => password_hash($data['password'], PASSWORD_DEFAULT),
            'role'      => $data['role'] ?? 'developer'
        ]);

        return $result ? $this->db->lastInsertId() : false;
    }

    /**
     * Update user data
     */
    public function update($id, array $data)
    {
        $allowedFields = ['name', 'email', 'role', 'is_active', 'avatar_path'];
        $fields = [];
        $params = ['id' => $id];

        foreach ($data as $key => $value) {
            if (in_array($key, $allowedFields)) {
                $fields[] = "$key = :$key";
                $params[$key] = $value;
            }
        }

        if (empty($fields)) return false;

        $sql = "UPDATE users SET " . implode(', ', $fields) . ", updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Update user password
     */
    public function updatePassword($id, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $stmt = $this->db->prepare("
            UPDATE users 
            SET password = :password, updated_at = NOW() 
            WHERE id = :id
        ");
        
        return $stmt->execute([
            'password' => $hashedPassword,
            'id' => $id
        ]);
    }

    /**
     * Update last login time
     */
    public function updateLastLogin($id)
    {
        $stmt = $this->db->prepare("
            UPDATE users SET last_login_at = NOW() WHERE id = :id
        ");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Soft delete user
     */
    public function softDelete($id)
    {
        $stmt = $this->db->prepare("
            UPDATE users SET deleted_at = NOW(), updated_at = NOW() WHERE id = :id
        ");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Check if email exists (within tenant)
     */
    public function emailExists($email, $tenantId = null, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) FROM users WHERE email = :email AND deleted_at IS NULL";
        $params = ['email' => $email];

        if ($tenantId) {
            $sql .= " AND tenant_id = :tenant_id";
            $params['tenant_id'] = $tenantId;
        }

        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Count total users
     */
    public function count($tenantId = null)
    {
        $sql = "SELECT COUNT(*) FROM users WHERE deleted_at IS NULL";
        $params = [];

        if ($tenantId) {
            $sql .= " AND tenant_id = :tenant_id";
            $params['tenant_id'] = $tenantId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    /**
     * Get users by role
     */
    public function getByRole($role, $tenantId = null)
    {
        $sql = "SELECT * FROM users WHERE role = :role AND deleted_at IS NULL";
        $params = ['role' => $role];

        if ($tenantId) {
            $sql .= " AND tenant_id = :tenant_id";
            $params['tenant_id'] = $tenantId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }


    /**
 * Generate and save verification token
 */
public function generateVerificationToken($userId)
{
    $token = bin2hex(random_bytes(32)); // Secure random token
    
    $stmt = $this->db->prepare("
        UPDATE users 
        SET verification_token = :token 
        WHERE id = :id
    ");
    
    $stmt->execute([
        'token' => $token,
        'id' => $userId
    ]);
    
    return $token;
}

/**
 * Verify user's email using token
 */
public function verifyEmail($token)
{
    $stmt = $this->db->prepare("
        SELECT id FROM users 
        WHERE verification_token = :token 
        AND email_verified_at IS NULL 
        AND deleted_at IS NULL
    ");
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch();

    if ($user) {
        // Mark email as verified
        $update = $this->db->prepare("
            UPDATE users 
            SET email_verified_at = NOW(), 
                verification_token = NULL,
                updated_at = NOW()
            WHERE id = :id
        ");
        return $update->execute(['id' => $user['id']]);
    }

    return false;
}
}