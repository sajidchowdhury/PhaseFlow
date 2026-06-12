<?php

namespace App\Models;

use PDO;

class TenantUsage
{
    private $db;

    public function __construct()
    {
        $this->db = \Database::getInstance()->getConnection();
    }

    /**
     * Initialize usage record for new tenant
     */
    public function initialize($tenantId)
    {
        $sql = "INSERT INTO tenant_usage (tenant_id, current_clients, current_users, updated_at) 
                VALUES (:tenant_id, 0, 1, NOW())";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['tenant_id' => $tenantId]);
    }

    /**
     * Get current usage of a tenant
     */
    public function getUsage($tenantId)
    {
        $stmt = $this->db->prepare("SELECT * FROM tenant_usage WHERE tenant_id = :tenant_id");
        $stmt->execute(['tenant_id' => $tenantId]);
        return $stmt->fetch();
    }

    /**
     * Increment client count
     */
    public function incrementClients($tenantId)
    {
        $sql = "UPDATE tenant_usage 
                SET current_clients = current_clients + 1, updated_at = NOW() 
                WHERE tenant_id = :tenant_id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['tenant_id' => $tenantId]);
    }

    /**
     * Increment user count
     */
    public function incrementUsers($tenantId)
    {
        $sql = "UPDATE tenant_usage 
                SET current_users = current_users + 1, updated_at = NOW() 
                WHERE tenant_id = :tenant_id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['tenant_id' => $tenantId]);
    }
}