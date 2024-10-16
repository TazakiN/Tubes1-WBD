<?php

namespace app\models;

use app\models\BaseModel;

class JobSeekerModel extends BaseModel
{
    public $user_id;
    public $role;
    public $email;
    public $nama;
    public $password;

    public function __construct()
    {
        $this->_primary_key = 'user_id';
        return $this;
    }

    public function constructFromArray($array)
    {
        $this->user_id = $array['user_id'];
        $this->role = $array['role'];
        $this->email = $array['email'];
        $this->nama = $array['nama'];
        $this->password = $array['password'];
        return $this;
    }

    public function toResponse()
    {
        return array(
            'user_id' => $this->user_id,
            'role' => $this->role,
            'email' => $this->email,
            'nama' => $this->nama,
        );
    }
}
