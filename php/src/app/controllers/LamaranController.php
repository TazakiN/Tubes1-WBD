<?php

namespace app\controllers;

use app\services\LamaranService;
use app\services\LowonganService;
use app\services\UserService;
use app\Request;
use app\helpers\Toast;
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
            $data['lamaran_id'] = $lamaran_id;
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
                parent::render($data, "lamaran-company", "layouts/base");
            } else if ($_SESSION['role'] == "jobseeker") {
                parent::render($data, "lamaran-jobseeker", "layouts/base");
            } else {
                parent::redirect("/login");
            }
        }
    }

    protected function post($urlParams)
    {
        $uri = Request::getURL();

        if ($uri == "/lamaran/add"){
            $lowongan_id = $urlParams["lowongan_id"];
            $note = $_POST['noteInput'];
            $cv_file = $_FILES['cvInput'];
            $video_file = $_FILES['videoInput'];
            try {
                $this->service->createLamaran($note, $cv_file, $video_file, $lowongan_id);
                Toast::success("Lamaran successfully created!");
                $data = $this->getLowonganDetailJobseeker($urlParams['lowongan_id'], $_SESSION['user_id']);
                $data["is_melamar"] = false;
                // return parent::render(["lowongan_id" => $lowongan_id], "lowongan-detail-jobseeker", "layouts/base");
                return parent::render($data, "lowongan-detail-jobseeker", "layouts/base");
            } catch (Exception $e) {
                $msg = $e->getMessage();
                Toast::error($msg);
                return parent::render($urlParams, "add-lamaran", "layouts/base");
            }
        } else if ($uri == "/lamaran/update") {
            try {
                header('Content-Type: application/json');
                $jsonData = file_get_contents('php://input');
                $data = json_decode($jsonData, true); 
                $reason = $data['reason'];
                $lamaran_id = $urlParams['lamaran_id'];
                $new_status = $urlParams['new_status'];
                $this->service->patchLamaranDecision($lamaran_id, $new_status, $reason);
                Toast::success('Lamaran decision successfully saved');
                echo json_encode([
                    'status' => 'success',
                ]);
            } catch (Exception $e) {
                header('Content-Type: application/json');
                Toast::error($e->getMessage());
                http_response_code(500);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Unexpected Error: ' . $e->getMessage()
                ]);
            }
        }
    }

    protected function delete($urlParams): void
    {
        $uri = Request::getURL();
        if ($uri == "/lamaran/delete"){
            try {
                $lamaran_id = $urlParams['lamaran_id'];
                $this->service->deleteLamaran($lamaran_id);
                Toast::success('Lamaran successfully deleted!');
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                ]);
            } catch (Exception $e) {
                Toast::error($e->getMessage());
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Unexpected Error: ' . $e->getMessage()
                ]);
            }
        }
    }





    // Illegal Function wkwkwk
    private function getLowonganDetailJobseeker($lowongan_id, $jobseeker_id) {
        $lowongan = $this->lowongan_service->getLowonganByID($lowongan_id);
        $lowongan->set('created_at', date("Y-m-d", strtotime($lowongan->get('created_at'))));
        $lowongan->set('updated_at', date("Y-m-d", strtotime($lowongan->get('updated_at'))));
        $dataLowongan = $lowongan->toResponse();
        
        $company = $this->user_service->getCompanyById($lowongan->get('company_id'));
        $dataCompany = $company->toResponse();

        $dataAttachments = $this->lowongan_service->getAttachmentLowonganByLowonganID($lowongan_id);

        $dataLamaran = $this->service->getByJobseekerAndLowonganID($jobseeker_id, $lowongan_id);
        $dataLamaran = $dataLamaran ? $dataLamaran->toResponse() : null;
        if (is_null($dataLamaran)) {
            $dataLamaran = [];
        }

        $data = array_merge($dataCompany, $dataLowongan, ['attachments' => $dataAttachments], ['lamaran' => $dataLamaran]);
        return $data;
    }
}
