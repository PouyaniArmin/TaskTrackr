<?php

namespace App\Controllres;

use App\Core\Controller;
use App\Core\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
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
