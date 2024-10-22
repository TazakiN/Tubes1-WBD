<?php

namespace app\controllers;

use app\services\LamaranService;
use app\services\LowonganService;
use app\services\UserService;
use app\Request;
use Exception;

class LamaranController extends BaseController
{
    private $lowongan_service;
    private $user_service;

    public function __construct()
    {
        parent::__construct(LamaranService::getInstance());
        $this->lowongan_service = LowonganService::getInstance();
        $this->user_service = UserService::getInstance();
    }

    protected function get($urlParams)
    {
        $uri = Request::getURL();
        if ($uri == "/lamaran"){
            if (isset($_SESSION['user_id'])) {
                $lowongan_id = $urlParams["lowongan_id"];
                $lowongan = $this->lowongan_service->getLowonganByID($lowongan_id);
                $data = [];
                $data['position'] = $lowongan->posisi;
                $company_id = $lowongan->company_id;
                $company = $this->user_service->getCompanyByID($company_id);
                $data['company_name'] = $company->nama;
                parent::render($data, "lamaran", "layouts/base");
            } else {
                parent::redirect("/login");
            }
        }
    }

    protected function post($urlParams)
    {
        $note = $_POST['noteInput'];
        $cv_file = $_FILES['cvInput'];
        $video_file = $_FILES['videoInput'];
        $lowongan_id = $urlParams["lowongan_id"];
        try {
            $this->service->createLamaran($note, $cv_file, $video_file, $lowongan_id);
            parent::render(["alert" => "Lamaran successfully created!"], "home-jobseeker", "layouts/base");
            parent::redirect("/");
        } catch (Exception $e) {
            $msg = $e->getMessage();
            parent::render(["errorMsg" => $msg], "lamaran", "layouts/base");
        }
    }
}
