<?php

namespace app\controllers;

use app\controllers\BaseController;
use app\Request;
use app\services\UserService;
use app\helpers\Toast;
use Exception;

class RegisterController extends BaseController
{
    public function __construct()
    {
        parent::__construct(UserService::getInstance());
    }

    public function get($urlParams)
    {
        $uri = Request::getURL();
        if($uri == "/register"){
            if (isset($_SESSION["user_id"])) {
                Toast::error("You are already logged in");
                parent::redirect("/");
            } else {
                parent::render($urlParams, "register", "layouts/base");
            }
        }
    }

    protected function post($urlParams)
    {
        try {
            $userModel = null;
            if ($_POST['role'] == "jobseeker") {
                $userModel = $this->service->registerJobSeeker(
                    $_POST['role'],
                    $_POST['nama'],
                    $_POST['email'],
                    $_POST['password'],
                    $_POST['confirm_password']
                );
            } else {
                $userModel = $this->service->registerCompany(
                    $_POST['role'],
                    $_POST['nama'],
                    $_POST['email'],
                    $_POST['password'],
                    $_POST['confirm_password'],
                    $_POST['lokasi'],
                    $_POST['about']
                );
            }

            $_SESSION['user_id'] = $userModel->id;
            $_SESSION['role'] = $userModel->role;
            $_SESSION['email'] = $userModel->email;

            Toast::success("Register success");
            parent::redirect("/");
        } catch (Exception $e) {
            $msg = $e->getMessage();
            Toast::error($msg);
            parent::redirect("/register");
        }
    }
};
