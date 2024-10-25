<?php

namespace app;

use app\Router;
use app\controllers\HomeController;
use app\controllers\LoginController;
use app\controllers\ProfileController;
use app\controllers\RegisterController;
use app\controllers\LamaranController;
use app\controllers\LowonganController;
use app\controllers\RiwayatController;

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
        $this->router->addRoute('/home', HomeController::class);
        $this->router->addRoute('/riwayat', RiwayatController::class);
        $this->router->addRoute('/login', LoginController::class);
        $this->router->addRoute('/logout', LoginController::class);
        $this->router->addRoute('/register', RegisterController::class);
        $this->router->addRoute('/profile', ProfileController::class);
        $this->router->addRoute('/edit-profile', ProfileController::class);
        $this->router->addRoute('/company-profile', ProfileController::class);
        $this->router->addRoute('/lamaran', LamaranController::class);
        $this->router->addRoute('/lamaran/add', LamaranController::class);
        $this->router->addRoute('/lamaran/delete', LamaranController::class);
        $this->router->addRoute('/lowongan', LowonganController::class);
        $this->router->addRoute('/lowongan/add', LowonganController::class);
        $this->router->addRoute('/lowongan/edit', LowonganController::class);
        $this->router->addRoute('/lowongan/edit-status', LowonganController::class);
        $this->router->addRoute('/lowongan/delete', LowonganController::class);
    }
}
