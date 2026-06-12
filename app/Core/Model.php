<?php

namespace App\Core;

use PDO;
use PDOException;

class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = $this->getConnection();
    }

    protected function getConnection()
    {
        try {
            $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
            $dotenv->load();

            $host     = $_ENV['DB_HOST'] ?? 'localhost';
            $port     = $_ENV['DB_PORT'] ?? '3306';
            $dbname   = $_ENV['DB_DATABASE'] ?? 'phase_flow';
            $username = $_ENV['DB_USERNAME'] ?? 'root';
            $password = $_ENV['DB_PASSWORD'] ?? '';

            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";

            $pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);

            return $pdo;

        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        } catch (\Exception $e) {
            die("Configuration error: " . $e->getMessage());
        }
    }
}