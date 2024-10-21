<?php

namespace app\models;

use app\models\BaseModel;

class AttachmentLowonganModel extends BaseModel
{
    public $attachment_id;
    public $lowongan_id;
    public $file_path;

    public function __construct()
    {
        $this->_primary_key = 'attachment_id';
    }

    public function constructFromArray($array)
    {
        $this->attachment_id = $array['attachment_id'];
        $this->lowongan_id = $array['lowongan_id'];
        $this->file_path = $array['file_path'];
        return $this;
    }

    public function toResponse()
    {
        return array(
            'attachment_id' => $this->attachment_id,
            'lowongan_id' => $this->lowongan_id,
            'file_path' => $this->file_path,
        );
    }
}
