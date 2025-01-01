<?php

namespace App\Models;

use App\Core\Model;

class Users extends Model
{
    public function getAllUsers()
    {
        return $this->selectAll('users');
    }
    public function insertToUsers($data){
        $this->create('users',$data);
    }
}
