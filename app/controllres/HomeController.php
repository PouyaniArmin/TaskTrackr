<?php

namespace App\Controllres;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Validator;
use App\Models\Users;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            'username' => 'armin',
            'email' => 'armin@gmail.com',
            'zipcode'=>'12345',
            'password' => '12345',
            'confrimPassword' => '12345'
        ];
        $fields = [
            'username' => 'required |alphanumeric',
            'email' => 'required | email ',
            'zipcode'=>'required | between: 3,255',
            'password' => 'required |secure',
            'confrimPassword' => 'required |same:password'
        ];
        $validator = new Validator;
        $errors = $validator->validation($data, $fields);
        var_dump($errors);
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
