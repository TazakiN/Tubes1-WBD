<?php

namespace app\controllers;

use app\controllers\BaseController;
use app\Request;

require_once __DIR__ . "/../config/config.php";

class HomeController extends BaseController
{

    public function __construct()
    {
        parent::__construct(null);
    }

    protected function get($urlParams)
    {
        $uri = Request::getURL();
        if ($uri == "/home"){
            parent::redirect("/");
        } 

        if (isset($_SESSION['user_id'])) {
            parent::render($urlParams, "home-jobseeker", "layouts/base");
        } else {
            parent::redirect("/login");
        }
    }
}
