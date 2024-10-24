<?php

namespace app\controllers;

use app\controllers\BaseController;
use app\services\UserService;
use app\services\LowonganService;
use app\services\LamaranService;
use app\Request;
use app\exceptions\ForbiddenAccessException;
use Exception;

class LowonganController extends BaseController
{
    protected $userService;
    protected $lamaranService;
    public function __construct()
    {
        parent::__construct(LowonganService::getInstance());
        $this->userService = UserService::getInstance();
        $this->lamaranService = LamaranService::getInstance();
    }

    protected function get($urlParams)
    {
        $data = [];
        $uri = Request::getURL();
        $data = $this->getToastContent($urlParams, $data);
        if ($_SESSION["role"] == "company") {
            if ($uri == "/lowongan/add") {
                return parent::render($urlParams, "add-lowongan-company", "layouts/base");
            } else if ($uri == "/lowongan/edit") {
                try {
                    if ($this->service->isBelongsToCompany($urlParams['lowongan_id'], $_SESSION['user_id'])) {
                        $data = $this->getLowonganDetail($urlParams['lowongan_id']);
                        return parent::render($data, "edit-lowongan-company", "layouts/base");
                    } else {
                        throw new ForbiddenAccessException("You are not allowed to access this page.");
                    }
                } catch (Exception $e) {
                    $msg = $e->getMessage();
                    parent::render(["error" => $msg], "home-company", "layouts/base");
                }
            } else if ($uri == "/lowongan") {
                if ($this->service->isBelongsToCompany($urlParams['lowongan_id'], $_SESSION['user_id'])) {
                    $data = $this->getLowonganDetail($urlParams['lowongan_id']);
                    return parent::render($data, "lowongan-detail-company", "layouts/base");
                } else {
                    $data = $this->getLowonganDetail($urlParams['lowongan_id']);
                    return parent::render($data, "lowongan-detail-jobseeker", "layouts/base");
                }
            }
        } else if ($_SESSION["role"] == "jobseeker") {
            if ($uri == "/lowongan") {
                $data = $this->getLowonganDetail($urlParams['lowongan_id']);
                return parent::render($data, "lowongan-detail-jobseeker", "layouts/base");
            }
        } else {
            return parent::render($data, "/", "layouts/base");
        }
    }

    protected function post($urlParams) {
        $uri = Request::getURL();

        if ($uri == "/lowongan/add") {
            $this->postNewLowongan(urlParams: $urlParams);
        } else if ($uri == "/lowongan/edit") {
            $this->postEditLowongan($urlParams);
        } else if ($uri == "/lowongan/edit-status") {
            $this->postEditStatusLowongan($urlParams);
        }
    }

    protected function delete($urlParams) {
        $uri = Request::getURL();
        if ($uri == "/lowongan/delete") {
            $this->deleteLowongan($urlParams);
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

        $lamarans = $this->lamaranService->getLamaranByLowonganID($lowongan_id);

        foreach ($lamarans as $lamaran) {
            $user_id = $lamaran->user_id;
            $jobSeekerModel = $this->userService->getJobSeekerById($user_id); 
            $lamaran->set('nama', $jobSeekerModel->nama);
        }
        $data = array_merge($dataCompany, $dataLowongan, ['attachments' => $dataAttachments], ['lamarans' => $lamarans]);
        return $data;
    }

    private function postNewLowongan($urlParams) {
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

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "message" => "Vacancy added successfully.",
                "id" => $id
            ]);
        } catch (Exception $e) {
            $msg = $e->getMessage();
            parent::render(["error" => $msg], "add-lowongan-company", "layouts/base");
        }
    }

    private function postEditLowongan($urlParams) {
        $posisi = $_POST['vacancy-name'];
        $jenis_pekerjaan = $_POST['type'];
        $is_open = $_POST['status'] === "open";
        $jenis_lokasi = $_POST['lokasi'];
        $deskripsi = $_POST['deskripsi'];
        $lowongan_id = (int)$urlParams['lowongan_id'];
        $deletedAttachments = explode(",", $_POST['deleted_attachments']);
        $files = $_FILES['files'];
    
        try {
            $this->service->postEditLowongan([
                'lowongan_id' => $lowongan_id,
                'posisi' => $posisi,
                'deskripsi' => $deskripsi,
                'jenis_pekerjaan' => $jenis_pekerjaan,
                'jenis_lokasi' => $jenis_lokasi,    
                'is_open' => $is_open,
                'files' => $files,
                'deleted_attachments' => $deletedAttachments,
            ]);
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "message"=> "Vacancy edited successfully.",
                'id' => $lowongan_id]);
        } catch (Exception $e) {
            $msg = $e->getMessage();
            parent::render(["error" => $msg], "edit-lowongan-company", "layouts/base");
        }
    }

    public function postEditStatusLowongan($urlParams) {
        if ($this->service->isBelongsToCompany($urlParams["lowongan_id"], $_SESSION["user_id"])) {
            
            $input = json_decode(file_get_contents('php://input'), true);
            $is_open = $input["is_open"];
    
            $this->service->editLowonganStatus($urlParams["lowongan_id"], $is_open);
            header("Content-Type: application/json");
            echo json_encode([
                "status" => "success",
                "message" => "Lowongan status has been updated successfully.",
                "is_open" => $is_open
            ]);
            return;
        } else {
            throw new ForbiddenAccessException("You are not allowed to access this page.");
        }
    }

    public function deleteLowongan($urlParams) {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $lowongan_id = $input["lowongan_id"];
            if ($this->service->isBelongsToCompany($lowongan_id, $_SESSION['user_id'])) {
    
                error_log("lowongan_id: ". $lowongan_id);
    
                $this->service->deleteLowongan($lowongan_id);
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Lowongan berhasil dihapus'
                ]);
            } else {
                header('Content-Type: application/json');
                http_response_code(403);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki akses untuk menghapus lowongan ini'
                ]);
            }
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}