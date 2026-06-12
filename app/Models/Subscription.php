<?php

namespace App\Models;

use App\Core\Model;

class Subscription extends Model
{
    protected $table = 'subscriptions';

    public function createDefault(int $tenantId): bool
    {
        $sql = "INSERT INTO {$this->table} 
                (tenant_id, plan_id, status, starts_at, created_at) 
                VALUES 
                (:tenant_id, 1, 'active', CURDATE(), NOW())";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['tenant_id' => $tenantId]);
    }
}