<?php

namespace app\controllers;

use app\controllers\BaseController;
use app\models\JobSeekerModel;
use app\services\UserService;
use app\Request;

require_once __DIR__ . "/../config/config.php";

class RiwayatController extends BaseController
{

    public function __construct()
    {
        parent::__construct(UserService::getInstance());
    }

    protected function get($urlParams)
    {
        $data = [];
        $uri = Request::getURL();
        if(isset($_SESSION['user_id'])){
            if ($_SESSION["role"] == "company"){
                parent::redirect("/");
            } else {
                $jobseeker = $this->service->getJobSeekerById($_SESSION['user_id']);
                if($jobseeker){
                    $data['email'] = $jobseeker->email;
                    $data['nama'] = $jobseeker->nama;
                }
                parent::render($urlParams, "riwayat", "layouts/base");
            }
        } else {
            parent::redirect("/login");
        }
    }
}
