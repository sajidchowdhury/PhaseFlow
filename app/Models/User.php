<?php

namespace App\Models;

use App\Core\Model;
use PDO;
use PDOException;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    /**
     * Find user by email
     */
    public function findByEmail(string $email, ?int $tenantId = null): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        $params = ['email' => $email];

        if ($tenantId !== null) {
            $sql .= " AND tenant_id = :tenant_id";
            $params['tenant_id'] = $tenantId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    /**
     * Create new user
     */
    public function create(array $data): ?int
    {
        $sql = "INSERT INTO {$this->table} 
                (tenant_id, name, email, password, role, verification_code, created_at, updated_at) 
                VALUES 
                (:tenant_id, :name, :email, :password, :role, :verification_code, NOW(), NOW())";

        try {
            $stmt = $this->db->prepare($sql);
            
            $stmt->execute([
                'tenant_id'         => $data['tenant_id'] ?? 1,
                'name'              => $data['name'],
                'email'             => $data['email'],
                'password'          => $data['password'],
                'role'              => $data['role'] ?? 'member',
                'verification_code' => $data['verification_code'] ?? null,
            ]);

            return (int)$this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("User creation failed: " . $e->getMessage());
            return null;
        }
    }

    public function verifyCode(int $userId, string $code): ?array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE id = :id AND verification_code = :code 
                AND email_verified_at IS NULL";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $userId, 'code' => $code]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $this->markAsVerified($userId);
            return $user;
        }

        return null;
    }

    public function markAsVerified(int $userId): bool
    {
        $sql = "UPDATE {$this->table} 
                SET email_verified_at = NOW(), verification_code = NULL, updated_at = NOW() 
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $userId]);
    }

    public function updateLastLogin(int $userId): bool
    {
        $sql = "UPDATE {$this->table} 
                SET last_login_at = NOW(), updated_at = NOW() 
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $userId]);
    }

    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Update the verification code for a user (used for resends)
     */
    public function updateVerificationCode(int $userId, string $code): bool
    {
        $sql = "UPDATE {$this->table} 
                SET verification_code = :code, updated_at = NOW() 
                WHERE id = :id AND email_verified_at IS NULL";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['code' => $code, 'id' => $userId]);
    }
}