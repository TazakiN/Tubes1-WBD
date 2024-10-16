<?php

namespace app\models;

use app\models\BaseModel;

class UserModel extends BaseModel
{
    public $user_id;
    public $role;
    public $nama;
    public $email;
    public $password;

    public function __construct()
    {
        $this->_primary_key = 'user_id';
        return $this;
    }

    public function constructFromArray($array)
    {
        $this->user_id = $array['user_id'];
        $this->email = $array['email'];
        $this->nama = $array['nama'];
        $this->password = $array['password'];
        $this->role = $array['role'];
        return $this;
    }

    public function toResponse()
    {
        return array(
            'user_id' => $this->user_id,
            'email' => $this->email,
            'nama' => $this->nama,
            'role' => $this->role,
        );
    }
}
