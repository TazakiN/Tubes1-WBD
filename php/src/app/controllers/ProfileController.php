<?php

namespace app\controllers;

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
        }
    }

    protected function patch($urlParams): void {
        try {
            $inputData = json_decode(file_get_contents("php://input"), true);

            if (!isset($inputData['nama']) || !isset($inputData['email'])) {
                http_response_code(400);
                echo json_encode(['message' => 'Name and email are required.']);
                return;
            }

            $userId = $_SESSION['user_id'];
            $user = $this->service->getUserById($userId);

            $nama = $inputData['nama'];
            $email = $inputData['email'];

            if ($this->service->isEmailJobSeekerExist($email) and $user->email != $email) {
                throw new BadRequestException("Email Already Exists!");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new BadRequestException("Email is not valid!");
            }

            if ($this->service->isnamaJobSeekerExist($nama) and $user->nama != $nama) {
                throw new BadRequestException("Nama Already Exists!");
            }

            $user->set('nama', $nama)->set('email', $email);

            $response = $this->service->updateJobSeeker($user);
            $msg = '';

            if ($response) {
                $msg = 'Profile updated successfully.';
                $updatedUser = $this->service->getUserById($userId);
                $_SESSION['nama'] = $updatedUser->nama;
                $_SESSION['email'] = $updatedUser->email;
                http_response_code(200);
                echo json_encode(['message' => $msg, 'nama' => $_SESSION['nama'], 'email' => $_SESSION['email']]);
            } else {
                throw new Exception('Failed to update profile.');
            }
        } catch (Exception $e) {
            $msg = $e->getMessage();
            $urlParams['errorMsg'] = $msg;
            parent::render($urlParams, "profile-jobseeker", "layouts/base");
        }
    }
}