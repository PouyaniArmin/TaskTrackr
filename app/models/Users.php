<?php

namespace App\Models;

use App\Core\Model;
use App\Core\SessionManager;

class Users extends Model
{
    public function getAllUsers()
    {
        return $this->selectAll('users');
    }
    public function getUsersById($id)
    {
        return $this->selecById('users', $id);
    }

    public function getUsersByEmail($email)
    {
        return $this->selecBy('users', 'email', $email);
    }
    public function insertToUsers($data)
    {
        $this->create('users', $data);
    }
    public function updateUserById($id, $data)
    {
        $this->updateById('users', $id, $data);
    }
    public function deleteUserById($id)
    {
        $this->deleteById('users', $id);
    }
    public function login($email, $password)
    {
        $data = $this->selecBy('users', 'email', $email);
        if ($data) {
            if (password_verify($password, $data[0]['password'])) {
                return $data[0];
            }
        }
        return null;
    }
}
