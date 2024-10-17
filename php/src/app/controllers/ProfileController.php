<?php

namespace app\controllers;

use app\models\CompanyModel;
use app\models\JobSeekerModel;
use app\Request;
use app\services\UserService;
use Exception;

class ProfileController extends BaseController {

    public function __construct() {
        parent::__construct(UserService::getInstance());
    }
    
    protected function get($urlParams) : void {
        $data = [];
        $uri = Request::getURL();
        if  ($uri == "/lamaran") {
            if ($_SESSION["role"] == "company") {
                $company = $this->service->getCompanyById($_SESSION['user_id']);
                if ($company) {
                    $data['email'] = $company->email;
                    $data['nama'] = $company->nama;
                    $data['lokasi'] = $company->lokasi;
                    $data['about'] = $company->about;
                }
                parent::render($data, "profile-company", "layouts/base");
            } else {
                $jobseeker = $this->service->getJobSeekerById($_SESSION['user_id']);
                if ($jobseeker) {
                    $data['email'] = $jobseeker->email;
                    $data['nama'] = $jobseeker->nama;
                }
                parent::render($data, "profile-jobseeker", "layouts/base");
            }
        }
    }

    protected function patch($urlParams) : void {
        $data = [];
        $uri = Request::getURL();
        
    }
}