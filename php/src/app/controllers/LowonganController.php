<?php

namespace app\controllers;

use app\controllers\BaseController;
use app\services\LowonganService;
use app\models\LowonganModel;
use app\Request;


class LowonganController extends BaseController
{
    public function __construct()
    {
        parent::__construct(LowonganService::getInstance());
    }

    protected function get($urlParams)
    {
        $uri = Request::getURL();
        if ($_SESSION["role"] == "company") {
            if ($uri == "/lowongan/add") {
                return parent::render($urlParams, "addLowongan", "layouts/base");
            }
        } else {
            return parent::render(null, "login", "layouts/base");
        }
    }
}