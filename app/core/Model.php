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
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
    }

    public function selecBytId($table,$id): array
    {
        $query = "SELECT * FROM $table WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id',intval($id),PDO::PARAM_STR);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
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
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->execute();
    }

    public function updateById(string $table, $id, $data)
    {
        $placeholders = implode(', ', array_map(function ($key) {
            return "$key = :$key";
        }, array_keys($data)));
        $query = "UPDATE $table SET $placeholders WHERE id=:id;";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', intval($id), PDO::PARAM_INT);
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }
        $stmt->execute();
        return;
    }

    public function deleteById(string $table, $id)
    {
        $query = "DELETE FROM $table WHERE id = :id;";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', intval($id), PDO::PARAM_INT);
        $stmt->execute();
        return;
    }
}
