<?php

namespace app\controllers;

use app\services\LamaranService;
use app\services\LowonganService;
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

    protected function post($urlParams)
    {
        $note = $_POST['noteInput'];
        $cv_file = $_FILES['cvInput'];
        $video_file = $_FILES['videoInput'];

        try {
            $this->service->createLamaran($note, $cv_file, $video_file);
        } catch (Exception $e) {
            $msg = $e->getMessage();
            parent::render(["errorMsg" => $msg], "lamaran", "layouts/base");
        }
    }
}
