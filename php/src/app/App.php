<?php

namespace app;

use app\Router;
use app\controllers\HomeController;

class App
{
    protected $router;

    function __construct()
    {
        $this->router = new Router();
        $this->init_router();
        $this->router->dispatch();
    }

    private function init_router()
    {
        $this->router->addRoute('/', HomeController::class);
    }
}
