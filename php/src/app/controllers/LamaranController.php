<?php

namespace app\controllers;

use app\services\LamaranService;
use app\services\LowoService;
use app\Request;
use Exception;

class LamaranController extends BaseController
{
    public function __construct()
    {
        parent::__construct(LamaranService::getInstance());
    }

    protected function get($urlParams)
    {
        $uri = Request::getURL();

        $lowongan_id = $urlParams["lowongan_id"];

        // TODO

        $data = [];

        parent::render($data, "lamaran", "layouts/base");
        // if ($uri == "/profile"){
        //     // if (isset($_SESSION['user_id'])) {
        //     //     parent::render($urlParams, "lamaran", "layouts/base");
        //     // } else {
        //     //     parent::redirect("/login");
        //     // }
        // }
    }




    
    // protected function post($urlParams)
    // {
    //     $username_email = $_POST['username-email'];
    //     $password = $_POST['password'];
    //     try {
    //         $this->service->login($username_email, $password);
    //     } catch (Exception $e) {
    //         $msg = $e->getMessage();
    //         parent::render(["errorMsg" => $msg], "login", "layouts/base");
    //     }
    //     if (isset($_SESSION['user_id'])) {
    //         parent::redirect("/");
    //     }
    // }
}
