<?php

namespace app\services;

use app\models\AttachmentLowonganModel;
use app\services\BaseService;
use app\services\LamaranService;
use app\services\UserService;
use app\models\LowonganModel;
use app\repositories\LowonganRepository;
use app\repositories\UserRepository;
use app\repositories\AttachmentLowonganRepository;
use Exception;

class LowonganService extends BaseService
{
    protected static $instance;
    protected $attachmentLowonganRepository;
    protected $lamaranService;
    protected $userService;
    

    private function __construct($repository)
    {
        parent::__construct();
        $this->repository = $repository;
        $this->attachmentLowonganRepository = AttachmentLowonganRepository::getInstance();
        $this->lamaranService = LamaranService::getInstance();
        $this->userService = UserService::getInstance();
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static(
                LowonganRepository::getInstance()
            );
        }
        return self::$instance;
    }

    public function getLowonganByID($lowongan_id) {
        $lowongan = $this->repository->getByID($lowongan_id);

        if ($lowongan) {
            $lowonganModel = new LowonganModel();
            $lowonganModel->constructFromArray($lowongan);
            return $lowonganModel;
        }

        return null;
    }

    public function getAllLowongan($pageNo, $limit) {
        $lowongans = $this->repository->getAllLowonganRep($pageNo, $limit);
        if ($lowongans) {
            $lowonganModels = [];
            foreach ($lowongans as $lowongan) {
                $lowonganModel = new LowonganModel();
                $lowonganModel->constructFromArray($lowongan);
                $companyDetail = UserRepository::getInstance()->getByID($lowonganModel->company_id ?? 0);
                $lowonganModel->company_name = $companyDetail['nama'];
                $lowonganModels[] = $lowonganModel;
            }
            return $lowonganModels;
        }
    }

    public function getLowonganByFilters($filters= [], $pageNo = 1, $limit = 6, $sort) {
        $lowongans = $this->repository->getLowonganByFilters($filters, $pageNo, $limit, $sort);
        if ($lowongans) {
            $lowonganModels = [];
            foreach ($lowongans as $lowongan) {
                $lowonganModel = new LowonganModel();
                $lowonganModel->constructFromArray($lowongan);
                $lowonganModels[] = $lowonganModel;
            }
            return $lowonganModels;
        }
    }

    public function countLowonganRow($whereParams = []) {
        return $this->repository->countRowByFilters($whereParams);
    }

    public function getAttachmentLowonganByLowonganID($lowongan_id) {
        $attachments = $this->attachmentLowonganRepository->getByLowonganID($lowongan_id);

        $attachmentLowonganModels = [];
        foreach ($attachments as $attachment) {
            $attachmentLowonganModel = new AttachmentLowonganModel();
            $attachmentLowonganModel->constructFromArray($attachment);
            $attachmentLowonganModels[] = $attachmentLowonganModel;
        }

        return $attachmentLowonganModels;
    }

    private function getUploadDirectory() {
        return dirname(__DIR__, 2) . '/uploads/';
    }

    public function postNewLowongan($data) {
        $lowongan = new LowonganModel();
        $attachment_lowongan = new AttachmentLowonganModel();

        $lowongan
            ->set("company_id", $data["company_id"])
            ->set("posisi", $data["posisi"])
            ->set("deskripsi", $data["deskripsi"])
            ->set("jenis_pekerjaan", $data["jenis_pekerjaan"])
            ->set("jenis_lokasi", $data["jenis_lokasi"])
            ->set("is_open", $data["is_open"]);

        $inserted_lowongan = $this->repository->insertNewLowongan($lowongan);
        $lowongan_id = $inserted_lowongan->get("lowongan_id");

        $uploadDirectory = $this->getUploadDirectory();
        
        // Ensure upload directory exists and is writable
        if (!file_exists($uploadDirectory)) {
            if (!mkdir($uploadDirectory, 0777, true)) {
                throw new Exception("Failed to create upload directory");
            }
        }

        if (!is_writable($uploadDirectory)) {
            throw new Exception("Upload directory is not writable");
        }

        if (!isset($data["files"]) || !isset($data["files"]["name"]) || empty($data["files"]["name"][0])) {
            return $lowongan_id;
        }

        foreach ($data["files"]["name"] as $index => $name) {
            $tmp_name = $data["files"]["tmp_name"][$index];
            $error = $data["files"]["error"][$index];
    
            if ($error === UPLOAD_ERR_OK) {
                $uniqueFileName = uniqid() . "_" . basename($name);
                $uploadPath = $uploadDirectory . $uniqueFileName;
                
                if (!move_uploaded_file($tmp_name, $uploadPath)) {
                    error_log("Failed to move uploaded file. Error: " . error_get_last()['message']);
                    throw new Exception("Failed to upload file: " . $name);
                }

                $attachment_lowongan = new AttachmentLowonganModel();
                $attachment_lowongan
                    ->set("lowongan_id", $lowongan_id)
                    ->set("file_path", $uniqueFileName);

                $this->attachmentLowonganRepository->insertNewAttachmentLowongan($attachment_lowongan);
            } else {
                $errorMessage = $this->getUploadErrorMessage($error);
                throw new Exception("Error uploading file {$name}: {$errorMessage}");
            }
        }

        return $lowongan_id;
    }

    public function getApplicantsDataCSV($lowongan_id) {
        $lowongan = $this->getLowonganByID($lowongan_id);
        $lamarans = $this->lamaranService->getLamaranByLowonganID($lowongan_id);
        $csvData = [];
        $csvData[] = ["Nama", "Email", "Posisi", "CV", "Video", "Status Lamaran", "Tanggal Lamaran"];

    foreach ($lamarans as $lamaran) {
        $user_id = $lamaran->get("user_id");
        $jobseeker = $this->userService->getJobSeekerById($user_id);
        
        if ($jobseeker && $lowongan) {
            $csvData[] = [
                $jobseeker->get("nama"),
                $jobseeker->get("email"),
                $lowongan->get("posisi"),
                $lamaran->get("cv_path"),
                $lamaran->get("video_path"),
                $lamaran->get("status"),
                date("Y-m-d H:i:s", strtotime($lamaran->get("created_at")))
            ];
        }
    }
    return $csvData;
    }

    private function getUploadErrorMessage($error_code) {
        switch ($error_code) {
            case UPLOAD_ERR_INI_SIZE:
                return "The uploaded file exceeds the upload_max_filesize directive in php.ini";
            case UPLOAD_ERR_FORM_SIZE:
                return "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
            case UPLOAD_ERR_PARTIAL:
                return "The uploaded file was only partially uploaded";
            case UPLOAD_ERR_NO_FILE:
                return "No file was uploaded";
            case UPLOAD_ERR_NO_TMP_DIR:
                return "Missing a temporary folder";
            case UPLOAD_ERR_CANT_WRITE:
                return "Failed to write file to disk";
            case UPLOAD_ERR_EXTENSION:
                return "File upload stopped by extension";
            default:
                return "Unknown upload error";
        }
    }

    public function postEditLowongan($data) {
    
        $lowongan = $this->getLowonganByID($data["lowongan_id"]);
        $attachment_lowongan = new AttachmentLowonganModel();
        $uploadDirectory = __DIR__ . "/../../uploads/";
    
        $lowongan
            ->set("posisi", $data["posisi"])
            ->set("deskripsi", $data["deskripsi"])
            ->set("jenis_pekerjaan", $data["jenis_pekerjaan"])
            ->set("jenis_lokasi", $data["jenis_lokasi"])
            ->set("is_open", $data["is_open"]);
    
        $this->repository->updateLowongan($lowongan);

        if($data['deleted_attachments'] == [""]) {
            $data['deleted_attachments'] = [];
        } 

        foreach ($data['deleted_attachments'] as $attachmentId) {
            $attachment_model = new AttachmentLowonganModel();
            $attachment = $this->attachmentLowonganRepository->getAttachmentByID($attachmentId);
            $attachment_model->constructFromArray($attachment);
            error_log("Attachment: " . json_encode($attachment));
            if ($attachment) {
                $filePath = $uploadDirectory . $attachment_model->get("file_path");
                
                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                $response = $this->attachmentLowonganRepository->deleteByID($attachmentId);
            }
        }
        
        if ($data["files"]["name"][0] == "") {
            return;
        }
        
        foreach ($data["files"]["name"] as $index => $name) {
            $tmp_name = $data["files"]["tmp_name"][$index];
            $error = $data["files"]["error"][$index];
    
            if ($error === UPLOAD_ERR_OK) {
                $uniqueFileName = uniqid() . "_" . basename($name);
                $uploadPath = $uploadDirectory . $uniqueFileName;
    
                if (move_uploaded_file($tmp_name, $uploadPath)) {
                    $attachment_lowongan = new AttachmentLowonganModel();
                    $attachment_lowongan
                        ->set("lowongan_id", $data["lowongan_id"])
                        ->set("file_path", $uniqueFileName);
    
                    $this->attachmentLowonganRepository->insertNewAttachmentLowongan($attachment_lowongan);
                } else {
                    throw new Exception("Gagal mengupload file: " . $name);
                }
            } else {
                throw new Exception("Terjadi error saat mengupload file: " . $name);
            }
        }
    }

    public function editLowonganStatus($id, $status) {
        $lowongan = $this->getLowonganByID($id);
        $lowongan->set("is_open", $status);
        $this->repository->updateLowongan($lowongan);
    }

    public function isBelongsToCompany($lowongan_id, $company_id) {
        $lowongan = $this->getLowonganByID($lowongan_id);
        return $lowongan->get("company_id") == $company_id;
    }

    public function deleteLowongan($lowongan_id) {
        $lowongan = $this->getLowonganByID($lowongan_id);
        $attachments = $this->getAttachmentLowonganByLowonganID($lowongan_id);
        $uploadDirectory = __DIR__ . "/uploads/";

        foreach ($attachments as $attachment) {
            $attachment_id = $attachment->get("attachment_id");
            $filePath = $uploadDirectory . $attachment->get("file_path");
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $this->attachmentLowonganRepository->deleteByID($attachment_id);
        }

        $this->repository->deleteByID($lowongan_id);
    }
}