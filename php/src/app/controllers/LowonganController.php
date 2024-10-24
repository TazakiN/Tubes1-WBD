<?php

namespace app\controllers;

use app\controllers\BaseController;
use app\services\LowonganService;
use app\Request;
use app\services\UserService;
use app\exceptions\ForbiddenAccessException;
use app\helpers\Toast;
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
                    Toast::error($msg);
                    parent::render($urlParams, "home-company", "layouts/base");
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
            Toast::warning("You are not allowed to access this page. Please login first.");
            return parent::render(null, "/", "layouts/base");
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

        $data = array_merge($dataCompany, $dataLowongan, ['attachments' => $dataAttachments]);

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
            Toast::success("Vacancy added successfully.");
            echo json_encode(['id' => $id]);
        } catch (Exception $e) {
            $msg = $e->getMessage();
            Toast::error($msg);
            parent::render($urlParams, "add-lowongan-company", "layouts/base");
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

            Toast::success("Vacancy edited successfully.");
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'status' => 'success',
                'id' => $lowongan_id]);
        } catch (Exception $e) {
            $msg = $e->getMessage();
            Toast::error($msg);
            parent::render($urlParams, "edit-lowongan-company", "layouts/base");
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
                "message" => "Status updated successfully.",    
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
                $this->service->deleteLowongan($lowongan_id);
                Toast::success("Vacancy deleted successfully.");
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                ]);
            } else {
                throw new ForbiddenAccessException("You are not allowed to access this page.");
            }
        } catch (Exception $e) {
            Toast::error($e->getMessage());
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);

        }
    }
}