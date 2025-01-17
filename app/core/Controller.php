<?php

namespace App\Core;

class Controller
{

    public $session_manager;
    public function __construct()
    {
        $this->session_manager=new SessionManager;
        $this->session_manager->start();
    }

    public string $layout='main';
    public function view(string $view, $params = [])
    {
        return Application::$app->router->renderView($view, $params);
    }
    protected function setLayout($layout){
        $this->layout=$layout;
    }
    /**
     * 
     */
    public function redirectTo(string $url){
        return header("Location:/$url");
    }

}
