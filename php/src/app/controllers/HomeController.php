<?php

namespace app\controllers;

use app\controllers\BaseController;
use app\services\LowonganService;
use app\services\UserService;
use app\Request;

require_once __DIR__ . "/../config/config.php";

class HomeController extends BaseController
{
    protected $lowonganService;

    public function __construct()
    {
        parent::__construct(UserService::getInstance());
        $this->lowonganService = LowonganService::getInstance();
    }

    protected function get($urlParams)
    {
        $data = [];
        $uri = Request::getURL();
        if ($uri == "/home"){
            parent::redirect("/");
        } 

        if (isset($_SESSION['user_id'])) {
            if ($_SESSION["role"] == "company"){
                $page = $urlParams['page'] ?? 1;
                $limit = 9;
                $countData = $this->lowonganService->countLowonganRow();
                $data['lowongans'] = $this->lowonganService->getLowonganByCompanyIDandPage($_SESSION['user_id'], (int)$page, $limit);
                $data['page'] = (int)$page;
                $data['totalPage'] = (int)ceil($countData / $limit);
                parent::render($data, "home-company", "layouts/base");
            } else {
                $jobseeker = $this->service->getJobSeekerById($_SESSION['user_id']);
                if($jobseeker){
                    $data['email'] = $jobseeker->email;
                    $data['nama'] = $jobseeker->nama;
                }
                parent::render($data, "home-jobseeker", "layouts/base");
            }
        } else {
            parent::redirect("/login");
        }
    }
}
