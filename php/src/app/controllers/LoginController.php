<?php

namespace app\controllers;

use app\services\UserService;
use app\Request;
use Exception;

class LoginController extends BaseController
{
    public function __construct()
    {
        parent::__construct(UserService::getInstance());
    }

    protected function get($urlParams)
    {
        $uri = Request::getURL();
        if ($uri == "/login") {
            if (isset($_SESSION['user_id'])) {
                $urlParams['warning'] = "You are already logged in!";
                parent::redirect("/", $urlParams);
            } else {
                parent::render($urlParams, "login", "layouts/base");
            }
        } else if ($uri == "/logout") {
            $this->service->logout();
            parent::redirect("/login", ["success" => "Logout successful!"]);
        }
    }
    protected function post($urlParams)
    {
        $username_email = $_POST['username-email'];
        $password = $_POST['password'];
        try {
            $this->service->login($username_email, $password);
        } catch (Exception $e) {
            $msg = $e->getMessage();
            parent::render(["error" => $msg], "login", "layouts/base");
        }
        if (isset($_SESSION['user_id'])) {
            parent::redirect("/", ["success"=> "Login successful!"]);
        }
    }
}
