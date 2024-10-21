<?php

namespace app\services;

use app\services\BaseService;
use app\models\LowonganModel;
use app\repositories\LowonganRepository;

class LowonganService extends BaseService
{
    protected static $instance;

    private function __construct($repository)
    {
        parent::__construct();
        $this->repository = $repository;
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

    public function getLowonganByID($id) {
        $lowongan = $this->repository->getByID($id);

        if ($lowongan) {
            $lowonganModel = new LowonganModel();
            $lowonganModel->constructFromArray($lowongan);
            return $lowonganModel;
        }

        return null;
    }

    public function postNewLowongan($data) {
        $lowongan = new LowonganModel();
        $lowongan
            ->set("company_id", $data["company_id"])
            ->set("posisi", $data["posisi"])
            ->set("deskripsi", $data["deskripsi"])
            ->set("jenis_pekerjaan", $data["jenis_pekerjaan"])
            ->set("jenis_lokasi", $data["jenis_lokasi"])
            ->set("is_open", $data["is_open"]);

        // echo var_dump($lowongan);
        return $this->repository->insertNewLowongan($lowongan);
    }
}