<?php

use App\Controllres\HomeController;
use App\Core\Application;
use App\Core\Middleware\AdminMiddleware;
use App\Core\Request;
use App\Core\Router;


$request = new Request;
$app = new Application(dirname(__DIR__),new Router($request));
$app->router->get('/', [HomeController::class, 'index']);

$app->router->get('/homes/{id}', [HomeController::class,'test']);
$app->router->get('/home/{id}',function($id){
    return "id $id";
});


$app->router->get('/test/post', [HomeController::class,'store'],[AdminMiddleware::class]);

$app->router->get('/test',function(){
    return "test";
});
$app->run();
