<?php

namespace App\Models;

use App\Core\Model;

class Subscription extends Model
{
    protected $table = 'subscriptions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'tenant_id', 'plan_id', 'status', 'starts_at', 'ends_at'
    ];

    protected $softDelete = false;

    /**
     * Create default subscription for new tenant
     */
    public static function createDefault(int $tenantId): ?Subscription
    {
        $data = [
            'tenant_id' => $tenantId,
            'plan_id'   => 1,                    // Normal plan by default
            'status'    => 'active',
            'starts_at' => date('Y-m-d')
        ];

        return parent::create($data);
    }

    /**
     * Find subscription by tenant
     */
    public static function findByTenant(int $tenantId): ?Subscription
    {
        return (new static())->where('tenant_id', $tenantId)->first();
    }

    /**
     * Find by ID
     */
    public static function findById(int $id): ?Subscription
    {
        return (new static())->where('id', $id)->first();
    }
}