<?php

use App\Controllres\DashboardController;
use App\Controllres\HomeController;
use App\Core\Application;
use App\Core\Middleware\AdminMiddleware;
use App\Core\Request;
use App\Core\Router;


$request = new Request;
$app = new Application(dirname(__DIR__), new Router($request));
$app->router->get('/', [HomeController::class, 'index']);
$app->router->get('/sigin', [HomeController::class, 'signin']);
$app->router->post('/sigin', [HomeController::class, 'siginForm']);
$app->router->get('/sigin-google', [HomeController::class, 'siginWithGoogle']);

$app->router->get('/sigup', [HomeController::class, 'sigUp']);
$app->router->post('/sigup', [HomeController::class, 'sigUpForm']);

$app->router->get('/dashboard', [DashboardController::class, 'index'], [AdminMiddleware::class]);
$app->router->get('/dashboard/new-task', [DashboardController::class, 'createTask'], [AdminMiddleware::class]);
$app->router->post('/dashboard/new-task', [DashboardController::class, 'createTask'], [AdminMiddleware::class]);

// 
$app->router->post('/dashboard/filter', [DashboardController::class, 'filter'], [AdminMiddleware::class]);

$app->router->get('/dashboard/edit/{id}', [DashboardController::class, 'edit'], [AdminMiddleware::class]);
$app->router->post('/dashboard/updateTask', [DashboardController::class, 'updateTask'], [AdminMiddleware::class]);

$app->router->get('/dashboard/delete/{id}', [DashboardController::class, 'delete'], [AdminMiddleware::class]);

$app->router->get('/logout', [DashboardController::class, 'logout']);


$app->router->get('/homes/{id}', [HomeController::class, 'test']);
$app->router->get('/home/{id}', function ($id) {
    return "id $id";
});


$app->router->get('/test/post', [HomeController::class, 'store'], [AdminMiddleware::class]);

$app->router->get('/test', function () {
    return "test";
});
$app->run();
