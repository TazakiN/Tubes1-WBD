<?php

namespace app\repositories;

use app\repositories\BaseRepository;
use app\models\UserModel;
use PDO;

class UserRepository extends BaseRepository
{
    protected static $instance;
    protected $tableName = 'users';

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

    public function getByEmail($email)
    {
        return $this->findOne(['email' => [$email, PDO::PARAM_STR]]);
    }

    public function getByNama($nama)
    {
        return $this->findOne(['nama' => [$nama, PDO::PARAM_STR]]);
    }

    public function deleteByID($id)
    {
        $user = $this->getById($id);
        $userModel = new UserModel();
        $userModel->constructFromArray($user);
        return $this->delete($userModel);
    }
}
