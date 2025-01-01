<?php

namespace App\Controllres;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Users;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $users = new Users;

        $data = [
            'username' => 'armin',
            'email' => 'armin28@gmail.com',
            'passwprd' => '1236',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')];
        $users->insertToUsers($data);
        // var_dump($users->getAllUsers());
        return $this->view('home');
    }
    public function test($id)
    {
        return $id;
    }

    public function store()
    {
        return "hi armin";
    }
}
