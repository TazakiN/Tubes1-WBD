<?php

namespace app\repositories;

use app\models\AttachmentLowonganModel;
use app\repositories\BaseRepository;
use app\models\CompanyModel;
use PDO;

class AttachmentLowonganRepository extends BaseRepository
{
    protected static $instance;
    protected $tableName = 'attachment_lowongan';

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
        return $this->findOne(['attachment_id' => [$id, PDO::PARAM_INT]]);
    }

    public function insertNewAttachmentLowongan($attachmentLowonganData) {
        $id = $this->insert($attachmentLowonganData, array(
            'lowongan_id'=> PDO::PARAM_INT,
            'file_path'=> PDO::PARAM_STR
        ));

        $response = $this->getByID($id);
        $attachmentLowongan = new AttachmentLowonganModel();

        return $attachmentLowongan->constructFromArray($response);
    }

    public function deleteByID($id)
    {
        $user = $this->getById($id);
        $companyModel = new CompanyModel();
        $companyModel->constructFromArray($user);
        return $this->delete($companyModel);
    }
}
