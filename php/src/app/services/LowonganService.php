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
        $lowonganModel = new LowonganModel();
        $lowonganModel->constructFromArray($data);
        return $this->repository->insertNewLowongan($lowonganModel);
    }
}