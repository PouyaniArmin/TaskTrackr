<?php

namespace App\Core;

use App\Core\middleware\AdminMiddleware;

class Application
{
    public Router $router;
    public static Application $app;
    public Controller $controller;
    public function __construct(Router $router)
    {
        self::$app = $this;
        $this->router = $router;
    }
    public function getController(): Controller
    {
        return $this->controller;
    }
    public function setController(Controller $controller)
    {
        $this->controller = $controller;
    }

    public function run()
    {
        echo $this->router->resolve();
    }
}
