<?php

namespace app\repositories;

use app\repositories\BaseRepository;
use app\models\LowonganModel;
use PDO;

class LowonganRepository extends BaseRepository
{
    protected static $instance;
    protected $tableName = 'lowongan';

    private function __construct()
    {
        parent::__construct();
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function getByID($id)
    {
        return $this->findOne(['lowongan_id' => [$id, PDO::PARAM_INT]]);
    }

    public function insertNewLowongan($lowonganModel) {
        // var_dump($lowonganModel);
        $id = $this->insert($lowonganModel, array(
            'company_id'=> PDO::PARAM_INT,
            'posisi'=> PDO::PARAM_STR,
            'deskripsi'=> PDO::PARAM_STR,
            'jenis_pekerjaan'=> PDO::PARAM_STR,
            'jenis_lokasi'=> PDO::PARAM_STR,
            'is_open'=> PDO::PARAM_BOOL
        ));

        $response = $this->getByID($id);
        $lowongan = new LowonganModel();

        return $lowongan->constructFromArray($response);
    }

    public function deleteByID($id)
    {
        $user = $this->getById($id);
        $companyModel = new LowonganModel();
        $companyModel->constructFromArray($user);
        return $this->delete($companyModel);
    }
}
