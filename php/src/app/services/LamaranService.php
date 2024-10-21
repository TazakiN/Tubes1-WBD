<?php

namespace app\services;

use app\services\BaseService;
use app\exceptions\BadRequestException;
use app\models\LamaranModel;
use app\repositories\LamaranRepository;
use PDO;

class LamaranService extends BaseService
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
                LamaranRepository::getInstance()
            );
        }
        return self::$instance;
    }
}
