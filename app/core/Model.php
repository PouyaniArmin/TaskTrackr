<?php

namespace App\Core;

use App\Database\DB;
use DateTime;
use PDO;

class Model extends DB
{

    protected function selectAll($table): array
    {
        $query = "SELECT * FROM $table";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    protected function selecById($table, $id): array
    {
        $query = "SELECT * FROM $table WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', intval($id));
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    protected function filterBy($table, $user_id, array $conditions)
    {
        $query = "SELECT * FROM $table WHERE user_id =:user_id";
        $placeholders = [];
        $boundValues = [];
        foreach ($conditions as $key => $value) {
            if ($value !== 0 && $value !== 'all' && !empty($value)) {
                $placeholders[] = " $key = :$key ";
                $boundValues[$key] =$value;
            }
        }
        if (!empty($placeholders)) {
            $query .= " AND "  . implode($placeholders);
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        foreach ($boundValues as $key => $value) {
            if ($key === 'priority_level_id' || $key === 'category_id') {
                $stmt->bindValue(":$key", intval($value));
            } else {
                $stmt->bindValue(":$key", $value);
            }
        }
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    protected function selecByGoogleId($table, $id): array
    {
        $query = "SELECT * FROM $table WHERE google_id =:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    protected function selecBy($table, $key, $value): array
    {
        $query = "SELECT * FROM $table WHERE $key =:$key";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':' . $key, $value);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    protected function create($table, $data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(', ', array_map(function ($key) {
            return ":" . $key;
        }, array_keys($data)));
        $query = "INSERT INTO {$table} ($columns) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($query);
        foreach ($data as $key => $value) {
            if ($key === 'password') {
                $stmt->bindValue(':' . $key, password_hash($value, PASSWORD_DEFAULT));
            } elseif ($key === 'due_date') {
                $formattedDate = (new DateTime($value))->format('Y-m-d');
                $stmt->bindValue(':' . $key, $formattedDate);
            } else {

                $stmt->bindValue(':' . $key, $value);
            }
        }
        $stmt->execute();
    }

    protected function updateById(string $table, $id, $data)
    {
        $placeholders = implode(', ', array_map(function ($key) {
            return "$key = :$key";
        }, array_keys($data)));
        $query = "UPDATE $table SET $placeholders WHERE id=:id;";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', intval($id), PDO::PARAM_INT);
        foreach ($data as $key => $value) {
            if ($key === 'due_date') {
                $formattedDate = (new DateTime($value))->format('Y-m-d');
                $stmt->bindValue(':' . $key, $formattedDate);
            } else {
                $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
            }
        }
        $stmt->execute();
        return;
    }

    protected function deleteById(string $table, $id)
    {
        $query = "DELETE FROM $table WHERE id = :id;";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', intval($id), PDO::PARAM_INT);
        $stmt->execute();
        return;
    }
}
