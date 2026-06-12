<?php

namespace App\Models;

use App\Core\Model;
use PDOException;

class Tenant extends Model
{
    protected $table = 'tenants';

    public function create(array $data): ?int
    {
        $sql = "INSERT INTO {$this->table} 
                (name, slug, email, created_at, updated_at) 
                VALUES 
                (:name, :slug, :email, NOW(), NOW())";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'name'  => $data['name'],
                'slug'  => $data['slug'],
                'email' => $data['email'] ?? null,
            ]);

            return (int)$this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Tenant creation failed: " . $e->getMessage());
            return null;
        }
    }
}