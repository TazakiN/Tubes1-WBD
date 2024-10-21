<?php

namespace app;

use app\Router;
use app\controllers\HomeController;
use app\controllers\LoginController;
use app\controllers\ProfileController;
use app\controllers\RegisterController;
use app\controllers\LamaranController;
use app\controllers\LowonganController;

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
        $this->router->addRoute('/login', LoginController::class);
        $this->router->addRoute('/logout', LoginController::class);
        $this->router->addRoute('/register', RegisterController::class);
        $this->router->addRoute('/profile', ProfileController::class);
        $this->router->addRoute('/edit-profile', ProfileController::class);
        $this->router->addRoute('/lamaran', LamaranController::class);
        $this->router->addRoute('/lowongan/add', LowonganController::class);
    }
}
