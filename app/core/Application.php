<?php

namespace App\Core;

use App\Core\middleware\AdminMiddleware;
use App\Database\DB;

class Application
{
    public Router $router;
    public static Application $app;
    public Controller $controller;
    public static string $ROOTPATH;

    public function __construct(string $rootPath, Router $router)
    {
        self::$app = $this;
        self::$ROOTPATH = $rootPath;
        $this->router = $router;
        new DB;
        session_start();
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
