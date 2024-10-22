<?php

namespace app\services;

use app\models\AttachmentLowonganModel;
use app\services\BaseService;
use app\models\LowonganModel;
use app\repositories\LowonganRepository;
use app\repositories\AttachmentLowonganRepository;
use Exception;

class LowonganService extends BaseService
{
    protected static $instance;
    protected $attachmentLowonganRepository;

    private function __construct($repository)
    {
        parent::__construct();
        $this->repository = $repository;
        $this->attachmentLowonganRepository = AttachmentLowonganRepository::getInstance();
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

        $inserted_lowongan =  $this->repository->insertNewLowongan($lowongan);
        $lowongan_id = $inserted_lowongan->get("lowongan_id");

        $uploadDirectory = __DIR__ . "\\..\\..\\uploads\\";

        // var_dump($data["files"]);
        foreach ($data["files"]["name"] as $index => $name) {
            $tmp_name = $data["files"]["tmp_name"][$index];
            $error = $data["files"]["error"][$index];
    
            if ($error === UPLOAD_ERR_OK) {
                $uniqueFileName = uniqid() . "_" . basename($name);
                $uploadPath = $uploadDirectory . $uniqueFileName;
    
                if (move_uploaded_file($tmp_name, $uploadPath)) {
                    $attachment_lowongan = new AttachmentLowonganModel();
                    $attachment_lowongan
                        ->set("lowongan_id", $lowongan_id)
                        ->set("file_path", $uniqueFileName);
    
                    $this->attachmentLowonganRepository->insertNewAttachmentLowongan($attachment_lowongan);
                } else {
                    throw new Exception("Gagal mengupload file: " . $name);
                }
            } else {
                throw new Exception("Terjadi error saat mengupload file: " . $name);
            }
        }
    

        return $lowongan_id;
    }
}