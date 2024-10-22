<?php

namespace app\controllers;

use app\controllers\BaseController;
use app\services\LowonganService;
use app\Request;
use app\services\UserService;
use Exception;

class LowonganController extends BaseController
{
    protected $userService;
    public function __construct()
    {
        parent::__construct(LowonganService::getInstance());
        $this->userService = UserService::getInstance();
    }

    protected function get($urlParams)
    {
        $uri = Request::getURL();
        if ($_SESSION["role"] == "company") {
            if ($uri == "/lowongan/add") {
                return parent::render($urlParams, "add-lowongan-company", "layouts/base");
            } else if ($uri == "/lowongan") {
                # TODO : Validate apakah company pemilik dari lowongan tersebut
                $data = $this->getLowonganDetail($urlParams['lowongan_id']);
                return parent::render($data, "lowongan-detail-company", "layouts/base");
            }
        } else if ($_SESSION["role"] == "jobseeker") {
            if ($uri == "/lowongan") {
                $data = $this->getLowonganDetail($urlParams['lowongan_id']);
                return parent::render($data, "lowongan-detail-jobseeker", "layouts/base");
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

    private function getLowonganDetail($lowongan_id) {
        $lowongan = $this->service->getLowonganByID($lowongan_id);
        $lowongan->set('created_at', date("Y-m-d", strtotime($lowongan->get('created_at'))));
        $lowongan->set('updated_at', date("Y-m-d", strtotime($lowongan->get('updated_at'))));
        $dataLowongan = $lowongan->toResponse();
        
        $company = $this->userService->getCompanyById($lowongan->get('company_id'));
        $dataCompany = $company->toResponse();

        $dataAttachments = $this->service->getAttachmentLowonganByLowonganID($lowongan_id);

        $data = array_merge($dataCompany, $dataLowongan, ['attachments' => $dataAttachments]);

        return $data;
    }
}