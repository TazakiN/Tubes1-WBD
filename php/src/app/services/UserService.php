<?php

namespace app\services;

use app\services\BaseService;
use app\exceptions\BadRequestException;
use app\models\UserModel;
use app\models\JobSeekerModel;
use app\models\CompanyModel;
use app\repositories\UserRepository;
use app\repositories\CompanyDetailRepository;
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

        if (!$this->isnamaExist($nama) && !$this->isEmailExist($email)) {
            $user = new JobSeekerModel();

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new BadRequestException("Email is not valid!");
            }

            $user
                ->set('role', $role)
                ->set('email', $email)
                ->set('nama', $nama)
                ->set('password', password_hash($password, PASSWORD_DEFAULT));

            $id = $this->repository->insert($user, array(
                'role' => PDO::PARAM_STR,
                'email' => PDO::PARAM_STR,
                'nama' => PDO::PARAM_STR,
                'password' => PDO::PARAM_STR
            ));

            $response = $this->repository->getById($id);
            $user = new JobSeekerModel();

            return $user->constructFromArray($response);
        } else {
            throw new BadRequestException("Email already exists!");
        }
    }

    public function registerCompany($role, $nama, $email, $password, $confirm_password, $lokasi, $about)
    {
        if ($password !== $confirm_password) {
            throw new BadRequestException("Password does not match");
        }

        if (!$this->isnamaExist($nama) && !$this->isEmailExist($email)) {
            $user = new CompanyModel();

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new BadRequestException("Email is not valid!");
            }

            $user
                ->set('role', $role)
                ->set('email', $email)
                ->set('nama', $nama)
                ->set('password', password_hash($password, PASSWORD_DEFAULT))
                ->set('lokasi', $lokasi)
                ->set('about', $about);

            $id = $this->repository->insert($user, array(
                'role' => PDO::PARAM_STR,
                'email' => PDO::PARAM_STR,
                'nama' => PDO::PARAM_STR,
                'password' => PDO::PARAM_STR,
            ));

            $user->set('user_id', $id);

            $companyDetailRepository = CompanyDetailRepository::getInstance();

            $id = $companyDetailRepository->insert($user, array(
                'user_id' => PDO::PARAM_INT,
                'lokasi' => PDO::PARAM_STR,
                'about' => PDO::PARAM_STR
            ));

            $response = $this->repository->getById($id);
            $user = new CompanyModel();

            return $user->constructFromArray($response);
        } else {
            throw new BadRequestException("Email already exists!");
        }
    }

    public function login($email_or_nama, $password)
    {
        $user = null;

        $userEmail = $this->getByEmail($email_or_nama);
        if ($userEmail and !is_null($userEmail->get('user_id'))) {
            $user = $userEmail;
        }

        if (is_null($user)) {
            $usernama = $this->getByNama($email_or_nama);
            if ($usernama and !is_null($usernama->get('user_id'))) {
                $user = $usernama;
            }
        }

        if (is_null($user)) {
            throw new BadRequestException("Email or nama is not found!");
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
        if (isset($_SESSION['user_id']) and isset($_SESSION['role'])) {
            unset($_SESSION['user_id']);
            unset($_SESSION['nama']);
            unset($_SESSION['role']);
        }
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
        $array = $response;

        $response = CompanyDetailRepository::getInstance()->getByID($array['user_id']);
        $array = array_merge($array, $response);

        if ($response) {
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
        $array = $response;

        $response = CompanyDetailRepository::getInstance()->getByID($array['user_id']);
        $array = array_merge($array, $response);

        if ($array) {
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
        $user = $this->getCompanyByEmail($email);
        return !is_null($user->get('user_id'));
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
        $array = $response;

        $response = CompanyDetailRepository::getInstance()->getByID($array['user_id']);
        $array = array_merge($array, $response);

        if ($array) {
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

    // public function deleteById($user_id)
    // {

    // }
}
