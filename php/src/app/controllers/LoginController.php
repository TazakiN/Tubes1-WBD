<?php

namespace app\controllers;

use app\services\UserService;
use app\helpers\Toast;
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
                Toast::error("You are already logged in");
                parent::redirect("/");
            } else {
                parent::render($urlParams, "login", "layouts/base");
            }
        } else if ($uri == "/logout") {
            $this->service->logout();
            Toast::success("Logout successful!");
            parent::redirect("/login");
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
            Toast::error($msg);
            parent::render($urlParams, "login", "layouts/base");
        }
        if (isset($_SESSION['user_id'])) {
            Toast::success("Login success");
            parent::redirect("/");
        }
    }
}
