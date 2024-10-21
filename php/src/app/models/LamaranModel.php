<?php

namespace app\models;

use app\models\BaseModel;

class LamaranModel extends BaseModel
{
    public $user_id;
    public $lowongan_id;
    public $cv_path;
    public $video_path;
    public $status;
    public $status_reason;
    public $created_at; 

    public function __construct()
    {
        $this->_primary_key = 'user_id';
    }

    public function constructFromArray($array)
    {
        $this->user_id = $array['user_id'];
        $this->lowongan_id = $array['lowongan_id'];
        $this->cv_path = $array['cv_path'];
        $this->video_path = $array['video_path'];
        $this->status = $array['status'];
        $this->status_reason = $array['status_reason'];
        $this->created_at = $array['created_at'];
        return $this;
    }

    public function toResponse()
    {
        return array(
            'user_id' => $this->user_id,
            'lowongan_id' => $this->lowongan_id,
            'cv_path' => $this->cv_path,
            'video_path' => $this->video_path,
            'status' => $this->status,
            'status_reason' => $this->status_reason,
            'created_at' => $this->created_at,
        );
    }
}
