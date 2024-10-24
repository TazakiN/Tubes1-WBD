<?php

namespace app\controllers;

use app\controllers\BaseController;
use app\services\LowonganService;
use app\services\UserService;
use app\helpers\Toast;
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
        $limit = 12;
        $data = [];
        $data = $this->getToastContent($urlParams, $data);
        $uri = Request::getURL();
        if ($uri == "/home"){
            $filters = $this->makeFilters($urlParams);
            $page = $urlParams['page'] ?? 1;
            $countData = $this->lowonganService->countLowonganRow($filters);
            $data['lowongans'] = $this->lowonganService->getLowonganByFilters($filters,  (int)$page, $limit) ?? [];
            $data['page'] = (int)$page;
            $data['totalPage'] = (int)ceil($countData / $limit);
            parent::render($data, "home-lowongan-jobseeker", "layouts/base");
        } else {
            if (isset($_SESSION['user_id'])) {
                if ($_SESSION["role"] == "company"){

                    $filters = $this->makeFilters($urlParams);
                    $filters['company_id'] = $_SESSION['user_id'];

                    $page = $urlParams['page'] ?? 1;
                    $countData = $this->lowonganService->countLowonganRow($filters);
                    $data['lowongans'] = $this->lowonganService->getLowonganByFilters($filters,  (int)$page, $limit) ?? [];

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
                Toast::error("Silahkan login terlebih dahulu");
                $data['email'] = '';
                $data['nama'] = 'Guest';
                parent::render($data, 'home-jobseeker', 'layouts/base');
            }
        }
    }

    private function makeFilters($urlParams)
    {
        $filters = [];
        $filters['searchParams'] = $urlParams['searchParams'] ?? '';

        $filters['jenis_pekerjaan'] = !empty($urlParams['jenis_pekerjaan']) 
        ? explode(',', $urlParams['jenis_pekerjaan']) 
        : ['Internship', 'Part-time', 'Full-time'];

        $filters['jenis_lokasi'] = !empty($urlParams['jenis_lokasi']) 
        ? explode(',', $urlParams['jenis_lokasi']) 
        : ['on-site', 'hybrid', 'remote'];
        
        return $filters;
    }
}
