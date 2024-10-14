<?php

namespace app\controllers;

use app\controllers\BaseController;

class HomeController extends BaseController
{

    public function __construct()
    {
        parent::__construct(null);
    }

    protected function get($urlParams)
    {
        parent::render($urlParams, "home", "layouts/base");
    }
}
