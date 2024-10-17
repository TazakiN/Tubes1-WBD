<?php

namespace app;

use app\controllers;
use app\Request as AppRequest;
use Exception;

class Router
{
    protected $routes = [];

    function addRoute(string $route, $controller)
    {
        $this->routes[$route] = $controller;
    }

    public function dispatch()
    {
        $uri = AppRequest::getURL();
        $method = AppRequest::getMethod();
        $params = AppRequest::getParams();

        if (isset($this->routes[$uri])) {
            $controllerClass = $this->routes[$uri];
            $class = new $controllerClass();
            try {
                return $class->handle($method, $params);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }
}
