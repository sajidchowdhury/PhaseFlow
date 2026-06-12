<?php

namespace App\Models;

use App\Core\Model;

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

    // Soft delete support
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
            'pipeline_count' => $this->pipelineOpportunities()->count(),
            'active_projects' => $this->projects()->where('status', 'in_progress')->count(),
            'total_revenue' => $this->invoices()->where('status', 'paid')->sum('total_amount') ?? 0,
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
        $usage = TenantUsage::where('tenant_id', $tenantId)->first();
        $subscription = Subscription::where('tenant_id', $tenantId)->first();
        
        if (!$subscription || !$usage) {
            return true; // Safety fallback
        }

        $plan = Plan::find($subscription->plan_id);
        $maxClients = $plan->max_clients;

        if ($maxClients === null) {
            return true; // Unlimited
        }

        return $usage->current_clients < $maxClients;
    }
}