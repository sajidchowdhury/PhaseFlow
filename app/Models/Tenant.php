<?php

namespace App\Models;

use PDO;

class Tenant
{
    private $db;

    public function __construct()
    {
        $this->db = \Database::getInstance()->getConnection();
    }
/**
 * Create new tenant (company)
 */
public function create(array $data)
{
    $sql = "INSERT INTO tenants 
            (name, slug, email, plan_id, subscription_status, is_active, created_at, updated_at) 
            VALUES 
            (:name, :slug, :email, :plan_id, 'active', 1, NOW(), NOW())";

    $stmt = $this->db->prepare($sql);
    
    return $stmt->execute([
        'name'    => $data['name'],
        'slug'    => $data['slug'],
        'email'   => $data['email'] ?? null,
        'plan_id' => $data['plan_id'] ?? 1
    ]);
}

    /**
     * Find tenant by ID
     */
    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tenants WHERE id = :id AND deleted_at IS NULL");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Find tenant by slug
     */
    public function findBySlug($slug)
    {
        $stmt = $this->db->prepare("SELECT * FROM tenants WHERE slug = :slug AND deleted_at IS NULL");
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch();
    }

    /**
     * Update tenant
     */
    public function update($id, array $data)
    {
        $fields = [];
        $params = ['id' => $id];

        foreach ($data as $key => $value) {
            if (in_array($key, ['name', 'email', 'phone', 'address', 'logo_path'])) {
                $fields[] = "$key = :$key";
                $params[$key] = $value;
            }
        }

        if (empty($fields)) return false;

        $sql = "UPDATE tenants SET " . implode(', ', $fields) . ", updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}