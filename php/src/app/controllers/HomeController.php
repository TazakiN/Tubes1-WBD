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
            if(!isset($_SESSION["role"]) || $_SESSION["role"] != "company"){
                $filters = $this->makeFilters($urlParams);
                $page = $urlParams['page'] ?? 1;
                $countData = $this->lowonganService->countLowonganRow($filters);
                $sort = $urlParams['sort'] ?? 'asc';
                $data['lowongans'] = $this->lowonganService->getLowonganByFilters($filters,  (int)$page, $limit, $sort) ?? [];
                $data['page'] = (int)$page;
                $data['totalPage'] = (int)ceil($countData / $limit);
                return parent::render($data, "home-lowongan-jobseeker", "layouts/base");
            } else {
                return parent::redirect("/");
            }
        } else {
            if (isset($_SESSION['user_id'])) {
                if (isset($_SESSION["role"]) && $_SESSION["role"] == "company"){
                    $filters = $this->makeFilters($urlParams);
                    $filters['company_id'] = $_SESSION['user_id'];

                    $page = $urlParams['page'] ?? 1;
                    $sort_type = $urlParams['sort_type'] ?? 'asc';
                    $countData = $this->lowonganService->countLowonganRow($filters);
                    $data['lowongans'] = $this->lowonganService->getLowonganByFilters($filters,  (int)$page, $limit, $sort_type) ?? [];

                    $data['page'] = (int)$page;
                    $data['totalPage'] = (int)ceil($countData / $limit);

                    return parent::render($data, "home-company", "layouts/base");

                } else {
                    $jobseeker = $this->service->getJobSeekerById($_SESSION['user_id']);
                    if($jobseeker){
                        $data['email'] = $jobseeker->email;
                        $data['nama'] = $jobseeker->nama;
                    }
                    return parent::render($data, "home-jobseeker", "layouts/base");
                }
            } else {
                $data['email'] = '';
                $data['nama'] = 'Guest';
                return parent::redirect("/home");
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
        : ['On-site', 'Hybrid', 'Remote'];

        return $filters;
    }
}