<?php

namespace app\controllers;

use app\controllers\BaseController;

require_once __DIR__ . "/../config/config.php";

class HomeController extends BaseController
{

    public function __construct()
    {
        parent::__construct(null);
    }

    protected function get($urlParams)
    {
        if (isset($_SESSION['user_id'])) {
            parent::render($urlParams, "home", "layouts/base");
        } else {
            parent::redirect("/login");
        }
    }
}
