<?php

namespace app\repositories;

use app\repositories\BaseRepository;
use app\models\LamaranModel;
use PDO;

class LamaranRepository extends BaseRepository
{
    protected static $instance;
    protected $tableName = 'lamaran';

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

    public function getByLamaranID($id)
    {
        return $this->findOne(['lamaran_id' => [$id, PDO::PARAM_INT]]);
    }

    public function getByUserIDAndLowonganID($id)
    {
        return $this->findOne(['user_id' => [$id, PDO::PARAM_INT], 'lowongan_id' => [$id, PDO::PARAM_INT]]);
    }

    public function getAllByUserID($id, $order)
    {
        return $this->findAll(['user_id' => [$id, PDO::PARAM_INT]], $order);
    }

    public function getAllByLowonganID($id, $order)
    {
        return $this->findAll(['lowongan_id' => [$id, PDO::PARAM_INT]], $order);
    }

    public function getByJobseekerAndLowonganID($jobseeker_id, $lowongan_id)
    {
        return $this->findOne(['user_id' => [$jobseeker_id, PDO::PARAM_INT], 'lowongan_id' => [$lowongan_id, PDO::PARAM_INT]]);
    }

    public function deleteByLamaranID($id)
    {
        $user = $this->getByLamaranID($id);
        $lamaranModel = new LamaranModel();
        $lamaranModel->constructFromArray($user);
        return $this->delete($lamaranModel);
    }
}
