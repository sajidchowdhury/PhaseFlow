<?php

namespace App\Models;

use App\Core\Model;

class Tenant extends Model
{
    protected $table = 'tenants';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name', 'slug', 'email', 'phone', 'address', 'logo_path', 
        'plan_id', 'subscription_status', 'current_period_end', 'is_active'
    ];

    protected $softDelete = true;

    /**
     * Create new tenant - Returns Tenant object
     */
    public static function create(array $data): ?Tenant
    {
        $data['slug'] = $data['slug'] ?? strtolower(preg_replace('/[^a-z0-9]+/', '-', $data['name'] ?? 'tenant')) . '-' . time();
        $data['subscription_status'] = $data['subscription_status'] ?? 'active';
        $data['is_active'] = $data['is_active'] ?? 1;

        return parent::create($data);
    }

    /**
     * Get ID from Tenant object or int
     */
    public static function getId($tenant): ?int
    {
        if (is_object($tenant) && isset($tenant->id)) {
            return (int)$tenant->id;
        }
        return (int)$tenant;
    }
}