<?php

namespace App\Core;

use PDO;
use PDOException;
use Exception;

class Model
{
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $softDelete = false;

    protected static $db;

    // Query building properties
    protected $whereClause;
    protected $orderByClause = [];

    public function __construct()
    {
        if (!self::$db) {
            self::$db = $this->getConnection();
        }
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
        } catch (Exception $e) {
            die("Configuration error: " . $e->getMessage());
        }
    }

    // =============== Query Builder ===============

    public static function where($column, $operator = '=', $value = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $instance = new static();
        $instance->whereClause = [$column, $operator, $value];
        return $instance;
    }

    public function orderBy($column, $direction = 'ASC')
    {
        $this->orderByClause[] = "`{$column}` " . strtoupper($direction);
        return $this;
    }

    public function get()
    {
        $sql = "SELECT * FROM `{$this->table}`";
        $params = [];

        if (isset($this->whereClause)) {
            [$col, $op, $val] = $this->whereClause;
            $sql .= " WHERE `{$col}` {$op} ?";
            $params[] = $val;
        }

        if ($this->softDelete) {
            $sql .= (strpos($sql, 'WHERE') !== false ? ' AND' : ' WHERE') . " `deleted_at` IS NULL";
        }

        if (!empty($this->orderByClause)) {
            $sql .= " ORDER BY " . implode(', ', $this->orderByClause);
        }

        $stmt = self::$db->prepare($sql);
        $stmt->execute($params);

        $results = $stmt->fetchAll(PDO::FETCH_CLASS, get_class($this));
        return $results;
    }

    public function first()
    {
        $results = $this->get();
        return !empty($results) ? $results[0] : null;
    }

        public static function create(array $data)
    {
        $instance = new static();
        $filtered = array_intersect_key($data, array_flip($instance->fillable));

        if (empty($filtered)) {
            return false;
        }

        // Convert any objects to string or remove them (safety)
        foreach ($filtered as $key => $value) {
            if (is_object($value)) {
                $filtered[$key] = (string)$value;   // or unset($filtered[$key]);
            }
        }

        $columns = implode('`, `', array_keys($filtered));
        $placeholders = implode(', ', array_fill(0, count($filtered), '?'));

        $sql = "INSERT INTO `{$instance->table}` (`{$columns}`) VALUES ({$placeholders})";

        $stmt = self::$db->prepare($sql);
        if ($stmt->execute(array_values($filtered))) {
            $id = self::$db->lastInsertId();
            $filtered[$instance->primaryKey] = $id;

            $obj = new static();
            foreach ($filtered as $key => $value) {
                $obj->{$key} = $value;
            }
            return $obj;
        }
        return false;
    }

    public function update(array $data)
    {
        $filtered = array_intersect_key($data, array_flip($this->fillable));
        if (empty($filtered)) return false;

        $sets = [];
        $params = [];
        foreach ($filtered as $col => $val) {
            $sets[] = "`{$col}` = ?";
            $params[] = $val;
        }
        $params[] = $this->{$this->primaryKey};

        $sql = "UPDATE `{$this->table}` SET " . implode(', ', $sets) . " WHERE `{$this->primaryKey}` = ?";

        $stmt = self::$db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete()
    {
        if ($this->softDelete) {
            $sql = "UPDATE `{$this->table}` SET `deleted_at` = NOW() WHERE `{$this->primaryKey}` = ?";
        } else {
            $sql = "DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?";
        }

        $stmt = self::$db->prepare($sql);
        return $stmt->execute([$this->{$this->primaryKey}]);
    }

    public function toArray()
    {
        $array = [];
        foreach (get_object_vars($this) as $key => $value) {
            if ($key[0] !== '_') $array[$key] = $value;
        }
        return $array;
    }
}