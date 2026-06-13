<?php

namespace App\Models;

use App\Core\Model;

class TenantUsage extends Model
{
    protected $table = 'tenant_usage';
    protected $primaryKey = 'id';
    protected $fillable = [
        'tenant_id', 'current_clients', 'current_users'
    ];

    protected $softDelete = false;

    /**
     * Initialize tenant usage record for new tenant
     */
    public static function initialize(int $tenantId): ?TenantUsage
    {
        $data = [
            'tenant_id'      => $tenantId,
            'current_clients' => 0,
            'current_users'   => 1
        ];

        return parent::create($data);
    }

    /**
     * Find usage by tenant
     */
    public static function findByTenant(int $tenantId): ?TenantUsage
    {
        return (new static())->where('tenant_id', $tenantId)->first();
    }

    /**
     * Find by ID
     */
    public static function findById(int $id): ?TenantUsage
    {
        return (new static())->where('id', $id)->first();
    }
}