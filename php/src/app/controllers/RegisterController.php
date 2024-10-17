<?php

namespace app\controllers;

use app\controllers\BaseController;
use app\repositories\CompanyDetailRepository;
use app\services\UserService;
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
            parent::redirect("/login");
        } catch (Exception $e) {
            $msg = $e->getMessage();
            echo "<script>console.error('Error: " . addslashes($msg) . "');</script>";
            parent::redirect("/register");
            parent::render($urlParams, "register", "layouts/base");
        }
    }
};
