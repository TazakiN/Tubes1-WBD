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

    protected function get($urlParams): void
    {
        $uri = Request::getURL();

        if ($uri == "/lamaran/add"){
            if (isset($_SESSION['user_id'])) {
                $lowongan_id = $urlParams["lowongan_id"];
                $lowongan = $this->lowongan_service->getLowonganByID($lowongan_id);
                $data = [];
                $data['position'] = $lowongan->posisi;
                $company_id = $lowongan->company_id;
                $company = $this->user_service->getCompanyByID($company_id);
                $data['company_name'] = $company->nama;
                parent::render($data, "add-lamaran", "layouts/base");
            } else {
                parent::redirect("/login");
            }
        } else if ($uri == "/lamaran"){
            $data = [];
            $lamaran_id = $urlParams['lamaran_id'];
            $lamaran = $this->service->getLamaranByID($lamaran_id);
            $data['status'] = $lamaran->status;
            $data['date'] = $lamaran->created_at;
            $data['status_reason'] = $lamaran->status_reason;
            $data['note'] = $lamaran->note;
            $data['cv'] = $lamaran->cv_path;
            $data['video'] = $lamaran->video_path;
            $lowongan_id = $lamaran->lowongan_id;
            $lowongan = $this->lowongan_service->getLowonganByID($lowongan_id);
            $data['position'] = $lowongan->posisi;
            $company_id = $lowongan->company_id;
            $company = $this->user_service->getCompanyByID($company_id);
            $data['company_name'] = $company->nama;
            if ($_SESSION['role'] == "company"){

            } else if ($_SESSION['role'] == "jobseeker") {
                parent::render($data, "lamaran-jobseeker", "layouts/base");
            } else {
                parent::redirect("/login");
            }
        }
    }

    protected function post($urlParams): void
    {
        $uri = Request::getURL();

        $note = $_POST['noteInput'];
        $cv_file = $_FILES['cvInput'];
        $video_file = $_FILES['videoInput'];
        $lowongan_id = $urlParams["lowongan_id"];
        if ($uri == "/lamaran/add"){
            try {
                $this->service->createLamaran($note, $cv_file, $video_file, $lowongan_id);
                parent::render(["alert" => "Lamaran successfully created!", "lowongan_id" => $lowongan_id], "lowongan-detail-jobseeker", "layouts/base");

                // parent::redirect("/lowongan", ["lowongan_id" => $lowongan_id]);
                // parent::render(["alert" => "Lamaran successfully created!"], "/lowongan", "layouts/base");
            } catch (Exception $e) {
                $msg = $e->getMessage();
                parent::render(["errorMsg" => $msg], "add-lamaran", "layouts/base");
            }
        }
    }
}
