<?php

namespace app\controllers;

use app\helpers\Toast;
use app\models\CompanyModel;
use app\models\JobSeekerModel;
use app\Request;
use app\services\UserService;
use app\exceptions\BadRequestException;
use Exception;

class ProfileController extends BaseController {

    public function __construct() {
        parent::__construct(UserService::getInstance());
    }
    
    protected function get($urlParams) : void {
        $data = [];
        $data = $this->getToastContent($urlParams, $data);
        $uri = Request::getURL();
        if  ($uri == "/profile") {
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
        } else if ($uri == "/edit-profile") {
            if ($_SESSION["role"] == "company") {
                $company = $this->service->getCompanyById($_SESSION['user_id']);
                if ($company) {
                    $data['email'] = $company->email;
                    $data['nama'] = $company->nama;
                    $data['lokasi'] = $company->lokasi;
                    $data['about'] = $company->about;
                }
                parent::render($data, "edit-profile-company", "layouts/base");
            } else if ($_SESSION["role"] == "jobseeker") {
                $jobseeker = $this->service->getJobSeekerById($_SESSION['user_id']);
                if ($jobseeker) {
                    $data['email'] = $jobseeker->email;
                    $data['nama'] = $jobseeker->nama;
                }
                parent::render($data, "edit-profile-jobseeker", "layouts/base");
            } else {
                Toast::error("You are not authorized to access this page. Please Login");
                parent::redirect("/login");
            }
        } else if ($uri == "/company-profile") {
            $company_id_url = $urlParams["company_id"];
            if ($_SESSION["user_id"] == $company_id_url) {
                parent::redirect("/profile");
            } else {
                $company = $this->service->getCompanyById($urlParams["company_id"]);
                if ($company) {
                    $data = $company->toResponse();
                }
                parent::render($data, "profile-company-for-jobseeker", "layouts/base");
            }
        } 
    }

    protected function post($urlParams): void {
        if ($_SESSION["role"] == "company") {
            $this->updateCompanyProfileDriver($urlParams);
        } else {
            $this->updateJobSeekerProfileDriver($urlParams);
        }
    }

    private function updateJobSeekerProfileDriver($urlParams): void {
        try {
            $user = $this->service->getJobSeekerById($_SESSION["user_id"]);
            $pass_lama = $user->password;

            $urlParams['email'] = $user->email;
            $urlParams['nama'] = $user->nama;

            $nama = $_POST['nama'];
            $email = $_POST['email'];
            $password = $_POST['password'] ? $_POST['password'] : $pass_lama;
            $confirmPassword = $_POST['confirm-password'] ? $_POST['confirm-password'] : $pass_lama;

            if ($this->service->isEmailJobSeekerExist($email) and $user->email != $email) {
                throw new BadRequestException("Email Already Exists!");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new BadRequestException("Email is not valid!");
            }

            if ($this->service->isnamaJobSeekerExist($nama) and $user->nama != $nama) {
                throw new BadRequestException("Nama Already Exists!");
            }

            if ($password !== $confirmPassword) {
                throw new BadRequestException("Password does not match!");
            }

            $user
                ->set('nama', $nama)
                ->set('email', $email)
                ->set('password', password_hash($password, PASSWORD_DEFAULT));

            $response = $this->service->updateJobSeeker($user);
            $msg = '';

            if ($response) {
                
                $_SESSION['nama'] = $nama;
                $_SESSION['email'] = $email;
                Toast::success("Profile updated successfully.");
                parent::redirect("/profile",);
            } else {
                throw new Exception('Failed to update profile.');
            }
        } catch (Exception $e) {
            $msg = $e->getMessage();
            Toast::error($msg);
            parent::render($urlParams, "edit-profile-jobseeker", "layouts/base");
        }
    }

    private function updateCompanyProfileDriver($urlParams): void {
        try {
            $company = $this->service->getCompanyById($_SESSION["user_id"]);
            $passLama = $company->password;
            
            $nama = $_POST['nama'];
            $email = $_POST['email'];
            $lokasi = $_POST['lokasi'];
            $about = $_POST['about'];
            $password = $_POST['password'] ? $_POST['password'] : $passLama;
            $confirmPassword = $_POST['confirm-password'] ? $_POST['confirm-password'] : $passLama;

            if ($this->service->isEmailCompanyExist($email) and $company->email != $email) {
                throw new BadRequestException("Email Already Exists!");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new BadRequestException("Email is not valid!");
            }

            if ($this->service->isnamaCompanyExist($nama) and $company->nama != $nama) {
                throw new BadRequestException("Nama Already Exists!");
            }

            if ($password !== $confirmPassword) {
                throw new BadRequestException("Password does not match!");
            }

            $company
                ->set('nama', $nama)
                ->set('email', $email)
                ->set('lokasi', $lokasi)
                ->set('about', $about)
                ->set('password', password_hash($password, PASSWORD_DEFAULT));

            $response = $this->service->updateCompany($company);
            $msg = '';

            if ($response) {
                $updatedCompany = $this->service->getCompanyById($company->user_id);
                $_SESSION['nama'] = $updatedCompany->nama;
                $_SESSION['email'] = $updatedCompany->email;
                Toast::success("Profile updated successfully.");
                parent::redirect("/profile");
            } else {
                throw new Exception('Failed to update profile.');
            }
        } catch (Exception $e) {
            $msg = $e->getMessage();
            Toast::error($msg);
            parent::render($urlParams, "edit-profile-company", "layouts/base");
        }
    }
}