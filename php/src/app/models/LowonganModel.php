<?php

namespace app\models;

use app\models\BaseModel;

class LowonganModel extends BaseModel
{
    public $lowongan_id;
    public $company_id;
    public $posisi;
    public $deskripsi;
    public $jenis_pekerjaan;
    public $jenis_lokasi;
    public $is_open;
    public $created_at;
    public $updated_at;

    public function __construct()
    {
        $this->_primary_key = 'lowongan_id';
    }

    public function constructFromArray($array)
    {
        $this->lowongan_id = $array['lowongan_id'];
        $this->company_id = $array['company_id'];
        $this->posisi = $array['posisi'];
        $this->deskripsi = $array['deskripsi'];
        $this->jenis_pekerjaan = $array['jenis_pekerjaan'];
        $this->jenis_lokasi = $array['jenis_lokasi'];
        $this->is_open = $array['is_open'];
        $this->created_at = $array['created_at'];
        $this->updated_at = $array['updated_at'];
        return $this;
    }

    public function toResponse()
    {
        return array(
            'lowongan_id' => $this->lowongan_id,
            'company_id'=> $this->company_id,
            'posisi'=> $this->posisi,
            'deskripsi'=> $this->deskripsi,
            'jenis_pekerjaan'=> $this->jenis_pekerjaan,
            'jenis_lokasi'=> $this->jenis_lokasi,
            'is_open'=> $this->is_open,
            'created_at'=> $this->created_at,
            'updated_at'=> $this->updated_at,
        );
    }
}
