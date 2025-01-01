<?php

namespace App\Core;

use App\Database\DB;
use PDO;

class Model extends DB
{

    public function selectAll($table): array
    {
        $query = "SELECT * FROM $table";
        $stmt = $this->conn->prepare($query);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function create($table, $data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(', ', array_map(function ($key) {
            return ":" . $key;
        }, array_keys($data)));
        $query = "INSERT INTO {$table} ($columns) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($query);
        foreach ($data as $key => $value) {
            $stmt->bindParam(':' . $key, $value);
        }
    }
}
