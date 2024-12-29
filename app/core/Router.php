<?php

namespace App\Core;

use ReflectionMethod;

class Router
{
    // Array to store routes and their corresponding callbacks for each HTTP method
    protected array $router = [];
    // Request object to handle request data
    protected Request $request;
    // Constructor to initialize the Router with a Request object
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    // Method to add a GET route to the router
    public function get($path, $callback, array $middlewares = [])
    {
        // Add the GET route to the router array with its callback and optional middlewares
        $this->router['get'][$path] = ['callback' => $callback, 'middlewares' => $middlewares];
    }
    // Method to add a POST route to the router
    public function post($path, $callback, array $middlewares = [])
    {
        // Add the POST route to the router array with its callback and optional middlewares
        $this->router['post'][$path] = ['callback' => $callback, 'middlewares' => $middlewares];
    }
     // Method to add a POST route to the router
     public function put($path, $callback, array $middlewares = [])
     {
         // Add the POST route to the router array with its callback and optional middlewares
         $this->router['put'][$path] = ['callback' => $callback, 'middlewares' => $middlewares];
     }
      // Method to add a POST route to the router
    public function patch($path, $callback, array $middlewares = [])
    {
        // Add the POST route to the router array with its callback and optional middlewares
        $this->router['patch'][$path] = ['callback' => $callback, 'middlewares' => $middlewares];
    }
     // Method to add a POST route to the router
     public function delete($path, $callback, array $middlewares = [])
     {
         // Add the POST route to the router array with its callback and optional middlewares
         $this->router['delete'][$path] = ['callback' => $callback, 'middlewares' => $middlewares];
     }
    // Method to execute the callback for the matched route with parameters
    public function resolve()
    {
        $path = $this->request->path();
        $method = $this->request->method();
        // Loop through the registered routes for the current method
        foreach ($this->router[$method] as $routerUri => $target) {
            $pattern = preg_replace('#\{([a-zA-Z0-9_-]+)\}#', '([^/]+)', $routerUri);
            if ($routerUri === $path) {
                if (is_string($target)) {
                    return $target;
                }
                if (is_callable($target)) {
                    return call_user_func($target);
                }
            }
            // Check if the route pattern matches the request path using regex
            if (preg_match("#^$pattern$#", $path, $matches)) {

                foreach ($target['middlewares'] as $middleware) {
                    $middlewareInstance = new $middleware;
                    if (method_exists($middlewareInstance, 'handle')) {
                        $middlewareResult = $middlewareInstance->handle($this->request);
                        if ($middlewareResult === false) {
                            return;
                        }
                    }
                }
                // Retrieve the callback and process the route parameters
                $callback = $target['callback'];
                array_shift($matches);
                if (is_array($callback)) {
                    return $this->executeCallback($callback, $matches);
                }

                return call_user_func($callback, $matches[0]);
            }
        }
        return $this->renderView('notFound');
    }
    // Method to execute the callback for the matched route with parameters
    private function executeCallback($callback, array $matches)
    {
        Application::$app->controller = new $callback[0];
        $class = Application::$app->controller;
        $classMethod = $callback[1];
        $reflectionMethod = new ReflectionMethod($class, $classMethod);
        $parameters = $reflectionMethod->getParameters();
        foreach ($parameters as $parameter) {
            if (
                $parameter->hasType() &&
                $parameter->getType()->getName() === Request::class
            ) {
                return call_user_func([$class, $classMethod], $this->request);
            }
        }
        return call_user_func_array([$class, $classMethod], $matches);
    }
    // Method to render the view with the provided parameters
    public function renderView(string $view, $params = [])
    {
        $contetnLayout = $this->renderLayout();
        $contentView = $this->renderOnlyView($view, $params);
        return str_replace("{{content}}", $contentView, $contetnLayout);
    }
    // Method to render the layout view
    private function renderLayout()
    {
        $main = Application::$app->controller->layout ?? '';
        ob_start();
        require_once __DIR__ . '/../views/layouts/main.php';
        return ob_get_clean();
    }
    // Method to render only the content view
    private function renderOnlyView(string $view, $params)
    {
        extract($params);
        ob_start();
        require_once __DIR__ . '/../views/' . $view . '.php';
        return ob_get_clean();
    }
}
