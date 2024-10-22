<?php

namespace app\controllers;

use app\controllers\BaseController;
use app\models\JobSeekerModel;
use app\services\UserService;
use app\services\LowonganService;
use app\services\LamaranService;
use app\Request;

require_once __DIR__ . "/../config/config.php";

class RiwayatController extends BaseController
{

    private $lowongan_service;
    private $lamaran_service;

    public function __construct()
    {
        parent::__construct(UserService::getInstance());
        $this->lowongan_service = LowonganService::getInstance();
        $this->lamaran_service = LamaranService::getInstance();
    }

    protected function get($urlParams)
    {
        $data = [];
        $single_data = [];
        if(isset($_SESSION['user_id'])){
            if ($_SESSION["role"] == "company"){
                parent::redirect("/");
            } else {
                $jobseeker = $this->service->getJobSeekerById($_SESSION['user_id']);
                if($jobseeker){
                    $all_lamaran_model = $this->lamaran_service->getLamaranByUser($_SESSION['user_id'], null);
                    foreach($all_lamaran_model as $lamaran_model) {
                        $single_data['status'] = $lamaran_model->status;
                        $single_data['created_at'] = $lamaran_model->created_at;
                        $lowongan_id = $lamaran_model->lowongan_id;
                        $lowongan = $this->lowongan_service->getLowonganByID($lowongan_id);
                        if (!$lowongan){
                            continue;
                        }
                        $single_data['position'] = $lowongan->posisi;
                        $company_id = $lowongan->company_id;
                        $company = $this->service->getCompanyById($company_id);
                        $single_data['company_name'] = $company->nama;

                        $data[] = $single_data;
                    }
                }
                parent::render($data, "riwayat", "layouts/base");
            }
        } else {
            parent::redirect("/login");
        }
    }
}
