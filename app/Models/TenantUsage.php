<?php

namespace App\Models;

use App\Core\Model;

class TenantUsage extends Model
{
    protected $table = 'tenant_usage';

    public function initialize(int $tenantId): bool
    {
        $sql = "INSERT INTO {$this->table} (tenant_id, current_clients, current_users) 
                VALUES (:tenant_id, 0, 1)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['tenant_id' => $tenantId]);
    }
}