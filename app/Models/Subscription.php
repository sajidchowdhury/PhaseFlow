<?php

namespace App\Models;

use PDO;

class Subscription
{
    private $db;

    public function __construct()
    {
        $this->db = \Database::getInstance()->getConnection();
    }

    /**
     * Create default subscription for new tenant (Normal plan)
     */
    public function createDefault($tenantId)
    {
        // Get default plan (Normal)
        $planStmt = $this->db->prepare("SELECT id FROM plans WHERE slug = 'normal' LIMIT 1");
        $planStmt->execute();
        $plan = $planStmt->fetch();

        if (!$plan) {
            return false; // No default plan found
        }

        $sql = "INSERT INTO subscriptions (tenant_id, plan_id, status, starts_at, created_at, updated_at) 
                VALUES (:tenant_id, :plan_id, 'active', CURDATE(), NOW(), NOW())";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'tenant_id' => $tenantId,
            'plan_id'   => $plan['id']
        ]);
    }

    /**
     * Find subscription by tenant
     */
    public function findByTenant($tenantId)
    {
        $stmt = $this->db->prepare("
            SELECT s.*, p.name as plan_name, p.max_users, p.max_clients 
            FROM subscriptions s
            JOIN plans p ON s.plan_id = p.id
            WHERE s.tenant_id = :tenant_id 
            ORDER BY s.created_at DESC 
            LIMIT 1
        ");
        $stmt->execute(['tenant_id' => $tenantId]);
        return $stmt->fetch();
    }
}