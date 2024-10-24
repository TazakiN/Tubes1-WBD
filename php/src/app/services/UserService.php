<?php

namespace app\services;

use app\services\BaseService;
use app\exceptions\BadRequestException;
use app\models\UserModel;
use app\models\JobSeekerModel;
use app\models\CompanyModel;
use app\repositories\UserRepository;
use app\repositories\CompanyDetailRepository;
use Exception;
use PDO;

class UserService extends BaseService
{
    protected static $instance;

    private function __construct($repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static(
                UserRepository::getInstance()
            );
        }
        return self::$instance;
    }

    public function registerJobSeeker($role, $nama, $email, $password, $confirm_password)
    {
        if ($password !== $confirm_password) {
            throw new BadRequestException("Password does not match");
        }

        if ($this->isEmailExist($nama)) {
            throw new BadRequestException("Email already exists!");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new BadRequestException("Email is not valid!");
        }

        $jobseeker = new JobSeekerModel();

        $jobseeker
            ->set('role', $role)
            ->set('email', $email)
            ->set('nama', $nama)
            ->set('password', password_hash($password, PASSWORD_DEFAULT));

        $id = $this->repository->insert($jobseeker, array(
            'role' => PDO::PARAM_STR,
            'email' => PDO::PARAM_STR,
            'nama' => PDO::PARAM_STR,
            'password' => PDO::PARAM_STR
        ));

        $response = $this->repository->getById($id);
        $jobseeker = new JobSeekerModel();
        $jobseeker->constructFromArray($response);

        $_SESSION["user_id"] = $jobseeker->get('user_id');
        $_SESSION["role"] = $jobseeker->get('role');
        $_SESSION["nama"] = $jobseeker->get('nama');

        return $jobseeker;
    }

    public function registerCompany($role, $nama, $email, $password, $confirm_password, $lokasi, $about)
    {
        if ($password !== $confirm_password) {
            throw new BadRequestException("Password does not match");
        }

        if ($this->isEmailExist($email)) {
            throw new BadRequestException("Email already exists!");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new BadRequestException("Email is not valid!");
        }

        $company = new CompanyModel();

        $company
            ->set('role', $role)
            ->set('email', $email)
            ->set('nama', $nama)
            ->set('password', password_hash($password, PASSWORD_DEFAULT))
            ->set('lokasi', $lokasi)
            ->set('about', $about);

        $id = $this->repository->insert($company, array(
            'role' => PDO::PARAM_STR,
            'email' => PDO::PARAM_STR,
            'nama' => PDO::PARAM_STR,
            'password' => PDO::PARAM_STR,
        ));

        $company->set('user_id', $id);

        $companyDetailRepository = CompanyDetailRepository::getInstance();

        $id = $companyDetailRepository->insert($company, array(
            'user_id' => PDO::PARAM_INT,
            'lokasi' => PDO::PARAM_STR,
            'about' => PDO::PARAM_STR
        ));

        $response = $this->repository->getById($id);
        $company = new CompanyModel();
        $company->constructFromArray($response);

        $_SESSION["user_id"] = $company->get('user_id');
        $_SESSION["role"] = $company->get('role');
        $_SESSION["nama"] = $company->get('nama');

        return $company;
    }

    public function login($email, $password)
    {
        $user = null;

        $user = $this->getByEmail($email);

        if ($user->get('user_id') == null) {
            throw new BadRequestException("Email is not found!");
        }

        if (!password_verify($password, $user->get('password'))) {
            throw new BadRequestException("Password is incorrect!");
        }

        $_SESSION["user_id"] = $user->get('user_id');
        $_SESSION["role"] = $user->get('role');
        $_SESSION["nama"] = $user->get('nama');

        return $user;
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['nama']);
        unset($_SESSION['role']);
    }

    public function getByEmail($email)
    {
        $user = new UserModel();
        $response = $this->repository->getByEmail($email);
        if ($response) {
            $user->constructFromArray($response);
        }

        return $user;
    }

    public function getJobSeekerByEmail($email)
    {
        $user = new JobSeekerModel();
        $response = $this->repository->getByEmail($email);
        if ($response) {
            $user->constructFromArray($response);
        }

        return $user;
    }

    public function getCompanyByEmail($email)
    {
        $user = new CompanyModel();
        $response = $this->repository->getByEmail($email);
        if (!$response) {
            $response = [];
        }
        $companyDetail = CompanyDetailRepository::getInstance()->getByID($response['user_id'] ?? 0);
        if (!$companyDetail) {
            $companyDetail = [];
        }
        $array = array_merge($response, $companyDetail);
        if (!empty($array)) {
            $user->constructFromArray($array);
        }
        return $user;
    }    

    public function getByNama($nama)
    {
        $user = new UserModel();
        $response = $this->repository->getByNama($nama);
        if ($response) {
            $user->constructFromArray($response);
        }
        return $user;
    }

    public function getJobSeekerByNama($nama)
    {
        $user = new JobSeekerModel();
        $response = $this->repository->getByNama($nama);
        if ($response) {
            $user->constructFromArray($response);
        }
        return $user;
    }

    public function getCompanyByNama($nama)
    {
        $user = new CompanyModel();
        $response = $this->repository->getByNama($nama);
        if (!$response) {
            $response = [];
        }
        $companyDetail = CompanyDetailRepository::getInstance()->getByID($response['user_id'] ?? 0);
        if (!$companyDetail) {
            $companyDetail = [];
        }
        $array = array_merge($response, $companyDetail);
        if (!empty($array)) {
            $user->constructFromArray($array);
        }
        return $user;
    }

    public function isnamaExist($nama)
    {
        $user = $this->getByNama($nama);
        return !is_null($user->get('user_id'));
    }

    public function isnamaJobSeekerExist($nama)
    {
        $user = $this->getJobSeekerByNama($nama);
        return !is_null($user->get('user_id'));
    }

    public function isnamaCompanyExist($nama)
    {
        $user = $this->getCompanyByNama($nama);
        return !is_null($user->get('user_id'));
    }

    public function isEmailExist($email)
    {
        $user = $this->getByEmail($email);
        return !is_null($user->get('user_id'));
    }

    public function isEmailJobSeekerExist($email)
    {
        $user = $this->getJobSeekerByEmail($email);
        return !is_null($user->get('user_id'));
    }

    public function isEmailCompanyExist($email)
    {
        $company = $this->getCompanyByEmail($email);
        return !is_null($company->get('user_id'));
    }

    public function getUserById($id)
    {
        $user = $this->repository->getById($id);

        if ($user) {
            $userModel = new UserModel();
            $userModel->constructFromArray($user);
            return $userModel;
        }

        return null;
    }

    public function getCompanyById($id)
        {
            $user = new CompanyModel();
            $response = $this->repository->getById($id);
            if (!$response) {
                $response = [];
            }
            $companyDetail = CompanyDetailRepository::getInstance()->getByID($response['user_id'] ?? 0);
            if (!$companyDetail) {
                $companyDetail = [];
            }
            $array = array_merge($response, $companyDetail);
            if (!empty($array)) {
                $user->constructFromArray($array);
            }
            return $user;
        }

    public function getJobSeekerById($id)
    {
        $user = $this->repository->getById($id);

        if ($user) {
            $userModel = new JobSeekerModel();
            $userModel->constructFromArray($user);
            return $userModel;
        }

        return null;
    }

    public function updateJobSeeker($user): mixed
    {
        $arrParams = [
            "user_id" => PDO::PARAM_INT,
            "role" => PDO::PARAM_STR,
            "nama" => PDO::PARAM_STR,
            "email" => PDO::PARAM_STR,
            "password" => PDO::PARAM_STR
        ];
        return $this->repository->update($user, $arrParams);
    }

    public function updateCompany($company): mixed
    {
        $arrParamsUsers = [
            "user_id" => PDO::PARAM_INT,
            "role" => PDO::PARAM_STR,
            "nama" => PDO::PARAM_STR,
            "email" => PDO::PARAM_STR,
            "password" => PDO::PARAM_STR
        ];
        $response = $this->repository->update($company, $arrParamsUsers);

        $arrParamsCompanyDetails = [
            "user_id" => PDO::PARAM_INT,
            "lokasi" => PDO::PARAM_STR,
            "about" => PDO::PARAM_STR
        ];

        $response = CompanyDetailRepository::getInstance()->update($company, $arrParamsCompanyDetails);
        
        return $response;
    }
    // public function deleteById($user_id)
    // {

    // }
}
