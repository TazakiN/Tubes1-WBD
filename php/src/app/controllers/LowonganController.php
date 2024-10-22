<?php

namespace app\controllers;

use app\controllers\BaseController;
use app\services\LowonganService;
use app\models\LowonganModel;
use app\Request;
use Exception;

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
                return parent::render($urlParams, "add-lowongan-company", "layouts/base");
            } else if ($uri == "/lowongan") {
                $lowonganData = $this->service->getLowonganByID((int)$urlParams['lowongan_id']);
                $data = $lowonganData->toResponse();
                return parent::render($data, "lowongan-detail-company", "layouts/base");
            }
        } else if ($_SESSION["role"] == "jobseeker") {
            if ($uri == "/lowongan") {
                $lowonganData = $this->service->getLowonganByID($urlParams['lowongan_id']);
                return parent::render($lowonganData, "lowongan-detail-jobseeker", "layouts/base");
            }
        } else {
            return parent::render(null, "login", "layouts/base");
        }
    }

    protected function post($urlParams) {
        $posisi = $_POST['vacancy-name'];
        $jenis_pekerjaan = $_POST['type'];
        $is_open = $_POST['status'] == "open" ? true : false;
        $jenis_lokasi = $_POST['lokasi'];
        $deskripsi = $_POST['deskripsi'];
        $files = $_FILES['files'];
        try {
            $id = $this->service->postNewLowongan([
                'company_id' => $_SESSION['user_id'],
                'posisi' => $posisi,
                'deskripsi' => $deskripsi,
                'jenis_pekerjaan' => $jenis_pekerjaan,
                'jenis_lokasi' => $jenis_lokasi,
                'is_open' => $is_open,
                'files' => $files,
            ]);

            echo json_encode(['id' => $id]);
        } catch (Exception $e) {
            $msg = $e->getMessage();
            parent::render(["errorMsg" => $msg], "login", "layouts/base");
        }
    }
}