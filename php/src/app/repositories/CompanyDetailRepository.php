<?php

namespace app\repositories;

use app\repositories\BaseRepository;
use app\models\CompanyModel;
use PDO;

class CompanyDetailRepository extends BaseRepository
{
    protected static $instance;
    protected $tableName = 'company_detail';

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
        return $this->findOne(['user_id' => [$id, PDO::PARAM_INT]]);
    }

    public function deleteByID($id)
    {
        $user = $this->getById($id);
        $companyModel = new CompanyModel();
        $companyModel->constructFromArray($user);
        return $this->delete($companyModel);
    }
}
