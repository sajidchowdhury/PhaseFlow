<?php

namespace App\Models;

use App\Core\Model;
use PDOException;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'tenant_id', 'name', 'email', 'password', 'role', 'avatar_path',
        'is_active', 'email_verified_at', 'verification_token', 'verification_code',
        'last_login_at', 'created_by', 'updated_by'
    ];

    protected $softDelete = true;

    /**
     * Find user by email (with optional tenant filter)
     */
    public static function findByEmail(string $email, ?int $tenantId = null): ?User
    {
        $query = (new static())->where('email', $email);

        if ($tenantId !== null) {
            $query = $query->where('tenant_id', $tenantId);   // Note: Chaining needs improvement later
        }

        return $query->first();
    }

    /**
     * Create new user using Base Model
     */
    public static function create(array $data): ?User
    {
        // Ensure required fields
        $data['tenant_id'] = $data['tenant_id'] ?? 1;
        $data['role']      = $data['role'] ?? 'member';
        $data['name']      = $data['name'];
        $data['email']     = $data['email'];
        $data['password']  = $data['password'];
        $data['verification_code'] = $data['verification_code'] ?? null;

              

        return parent::create($data);
    }

    /**
     * Verify email code
     */
    public static function verifyCode(int $userId, string $code): ?User
    {
        $user = (new static())
            ->where('id', $userId)
            ->where('verification_code', $code)
            ->first();

        if ($user && empty($user->email_verified_at)) {
            $user->markAsVerified();
            return $user;
        }

        return null;
    }

    /**
     * Mark user as email verified
     */
    public function markAsVerified(): bool
    {
        $this->email_verified_at = date('Y-m-d H:i:s');
        $this->verification_code = null;
        return $this->update([
            'email_verified_at' => $this->email_verified_at,
            'verification_code' => null
        ]);
    }

    /**
     * Update last login time
     */
    public static function updateLastLogin(int $userId): bool
    {
        $user = (new static())->where('id', $userId)->first();
        if ($user) {
            return $user->update(['last_login_at' => date('Y-m-d H:i:s')]);
        }
        return false;
    }

    /**
     * Find user by ID
     */
    public static function findById(int $id): ?User
    {
        return (new static())->where('id', $id)->first();
    }

    /**
     * Update verification code (for resend)
     */
    public static function updateVerificationCode(int $userId, string $code): bool
    {
        $user = (new static())->where('id', $userId)->first();
        if ($user) {
            return $user->update([
                'verification_code' => $code,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
        return false;
    }

    /**
     * Convert to array (useful for views)
     */
    public function toArray(): array
    {
        return parent::toArray();
    }
}