<?php

namespace App\Models;

use App\Core\Model;

class Users extends Model
{
    public function getAllUsers()
    {
        return $this->selectAll('users');
    }
    public function getUsersById($id)
    {
        return $this->selecBytId('users',$id);
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
}
