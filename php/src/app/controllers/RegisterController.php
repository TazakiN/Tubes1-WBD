<?php

namespace app\controllers;

use app\controllers\BaseController;
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
        if (isset($_SESSION["user_id"])) {
            Toast::error("You are already logged in");
            parent::redirect("/");
        } else {
            parent::render($urlParams, "register", "layouts/base");
        }
    }

    protected function post($urlParams)
    {
        try {
            if ($_POST['role'] == "jobseeker") {
                $this->service->registerJobSeeker(
                    $_POST['role'],
                    $_POST['nama'],
                    $_POST['email'],
                    $_POST['password'],
                    $_POST['confirm_password']
                );
            } else {
                $this->service->registerCompany(
                    $_POST['role'],
                    $_POST['nama'],
                    $_POST['email'],
                    $_POST['password'],
                    $_POST['confirm_password'],
                    $_POST['lokasi'],
                    $_POST['about']
                );
            }
            Toast::success("Register success, please login");
            parent::redirect("/login");
        } catch (Exception $e) {
            $msg = $e->getMessage();
            Toast::error($msg);
            parent::redirect("/register");
        }
    }
};
