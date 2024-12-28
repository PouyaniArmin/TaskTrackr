<?php

namespace App\Core;

class Controller
{

    public string $layout='main';
    public function view(string $view, $params = [])
    {
        return Application::$app->router->renderView($view, $params);
    }
    protected function setLayout($layout){
        $this->layout=$layout;
    }

}
