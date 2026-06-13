<?php

namespace App\Models;

use App\Core\Model;
use App\Models\TenantUsage;
use App\Models\Subscription;
use App\Models\Plan;

class Client extends Model

{
    protected $table = 'clients';
    protected $primaryKey = 'id';
    protected $fillable = [
        'tenant_id', 'name', 'organization', 'email', 'phone', 'address',
        'image_path', 'website', 'facebook_profile', 'instagram_profile',
        'linkedin_profile', 'location_lat', 'location_lng', 'notes',
        'tags', 'last_contact_date', 'next_followup_date', 'source', 'status',
        'created_by', 'updated_by'
    ];

    protected $softDelete = true;
    /**
     * Relationships
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function pipelineOpportunities()
    {
        return $this->hasMany(PipelineOpportunity::class, 'client_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'client_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'client_id');
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'client_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'client_id');
    }

    /**
     * Get full profile with related data
     */
    public function getFullProfile()
    {
        return [
            'basic' => $this->toArray(),
            'pipeline_count' => 0, // Later enhance
            'active_projects' => 0,
            'total_revenue' => 0,
        ];
    }

    /**
     * Scope for targeted clients (default)
     */
    public function scopeTargeted($query)
    {
        return $query->where('status', 'targeted');
    }

    /**
     * Check if tenant can add more clients (plan limit)
     */
    public static function canAddMoreClients($tenantId)
    {
        $usage = (new TenantUsage())->where('tenant_id', $tenantId)->first();
        $subscription = (new Subscription())->where('tenant_id', $tenantId)->first();
        
        if (!$subscription || !$usage) return true;

        $plan = (new Plan())->where('id', $subscription->plan_id)->first();
        $maxClients = $plan->max_clients ?? null;

        if ($maxClients === null) return true;

        return $usage->current_clients < $maxClients;
    }
}