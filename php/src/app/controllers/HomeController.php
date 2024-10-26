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
        $data = [];
        $data = $this->getToastContent($urlParams, $data);
        $uri = Request::getURL();
        if (!isset($_SESSION['role'])) {
            $_SESSION['role'] = 'guest';
        }
        if ($uri == "/job-listing"){
            $data = $this->getJobListingData($urlParams, $data);
            return parent::render($data, "home-joblisting", "layouts/base");
        } else if ($uri == "/") {
            if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'jobseeker') {
                $jobseeker = $this->service->getJobSeekerById($_SESSION['user_id']);
                if($jobseeker){
                    $data['email'] = $jobseeker->email;
                    $data['nama'] = $jobseeker->nama;
                } else {
                    parent::redirect("/job-listing");
                }
            } else if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'company') {
                parent::redirect("/job-listing");
            } else {
                $data['email'] = '';
                $data['nama'] = 'Guest';
            }
            return parent::render($data, "home-jobseeker", "layouts/base");
        } else {
            return parent::redirect("/login");
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
        : ['On-site', 'Hybrid', 'Remote'];

        return $filters;
    }

    private function getJobListingData($urlParams, $data)
    {
        $limit = 12;
        $filters = $this->makeFilters($urlParams);
        $page = $urlParams['page'] ?? 1;
        $countData = $this->lowonganService->countLowonganRow($filters);
        $sort = $urlParams['sort'] ?? 'asc';
        if ($_SESSION["role"] == "company") {
            $filters['company_id'] = $_SESSION['user_id'];
        }
        $data['lowongans'] = $this->lowonganService->getLowonganByFilters($filters,  (int)$page, $limit, $sort) ?? [];
        $data['page'] = (int)$page;
        $data['totalPage'] = (int)ceil($countData / $limit);
        return $data;
    }
}