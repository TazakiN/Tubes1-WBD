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

    public function getLowonganByCompanyID($companyID, $pageNo, $limit)
    {
        return $this->findAll(['company_id' => [$companyID, PDO::PARAM_INT]], null, $pageNo, $limit);
    }

    public function getLowonganByFilters($filters, $pageNo, $limit, $sort)
    {
        $whereConditions = [];

        if (!empty($filters['company_id'])) {
            $whereConditions['company_id'] = [$filters['company_id'], PDO::PARAM_INT];
        }

        if (!empty($filters['searchParams'])) {
            $whereConditions['posisi'] = [$filters['searchParams'], PDO::PARAM_STR, 'LIKE'];
        }

        if (!empty($filters['jenis_pekerjaan']) && is_array($filters['jenis_pekerjaan'])) {
            $whereConditions['jenis_pekerjaan'] = [
                $filters['jenis_pekerjaan'][0],
                PDO::PARAM_STR,
                'IN',
                $filters['jenis_pekerjaan']
            ];
        }
        
        if (!empty($filters['jenis_lokasi']) && is_array($filters['jenis_lokasi'])) {
            $whereConditions['jenis_lokasi'] = [
                $filters['jenis_lokasi'][0],
                PDO::PARAM_STR,
                'IN',
                $filters['jenis_lokasi'] 
            ];
        }
        
        return $this->findAll($whereConditions, 'created_at', $pageNo, $limit, $sort);
    }

    public function countRowByFilters($filters) {
        $whereConditions = [];

        if (!empty($filters['company_id'])) {
            $whereConditions['company_id'] = [$filters['company_id'], PDO::PARAM_INT];
        }

        if (!empty($filters['searchParams'])) {
            $whereConditions['posisi'] = [$filters['searchParams'], PDO::PARAM_STR, 'LIKE'];
        }

        if (!empty($filters['jenis_pekerjaan']) && is_array($filters['jenis_pekerjaan'])) {
            $whereConditions['jenis_pekerjaan'] = [
                $filters['jenis_pekerjaan'][0],
                PDO::PARAM_STR,
                'IN',
                $filters['jenis_pekerjaan']
            ];
        }
        
        if (!empty($filters['jenis_lokasi']) && is_array($filters['jenis_lokasi'])) {
            $whereConditions['jenis_lokasi'] = [
                $filters['jenis_lokasi'][0],
                PDO::PARAM_STR,
                'IN',
                $filters['jenis_lokasi'] 
            ];
        }

        return $this->countRow($whereConditions);
    }

    public function getAllLowonganRep($pageNo, $limit)
    {
        return $this->findAll([], null, $pageNo, $limit);
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

    public function updateLowongan($lowonganModel) {

        $response = $this->update($lowonganModel, array(
            'lowongan_id'=> PDO::PARAM_INT,
            'company_id'=> PDO::PARAM_INT,
            'posisi'=> PDO::PARAM_STR,
            'deskripsi'=> PDO::PARAM_STR,
            'jenis_pekerjaan'=> PDO::PARAM_STR,
            'jenis_lokasi'=> PDO::PARAM_STR,
            'is_open'=> PDO::PARAM_BOOL
        ));

        $lowonganData = $this->getByID($lowonganModel->get('lowongan_id'));
        $lowongan = new LowonganModel();

        return $lowongan->constructFromArray($lowonganData);
    }

    public function deleteByID($id)
    {
        $user = $this->getById($id);
        $companyModel = new LowonganModel();
        $companyModel->constructFromArray($user);
        return $this->delete($companyModel);
    }
}
