<?php

namespace app\database;

use PDO;
use PDOException;

require_once __DIR__ . '/../config/config.php';

class DatabaseConnection
{
    private static $instance;
    private $pdo;

    private function __construct()
    {
        $DB_HOST = $_ENV['DB_HOST'];
        $DB_PORT = $_ENV['DB_PORT'];
        $DB_USERNAME = $_ENV['DB_USERNAME'];
        $DB_PASSWORD = $_ENV['DB_PASSWORD'];
        $DB_NAME = $_ENV['DB_NAME'];

        try {
            $this->pdo = new PDO("pgsql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_NAME", $DB_USERNAME, $DB_PASSWORD);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("ERROR: Could not connect. " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new DatabaseConnection();
        }
        return self::$instance;
    }

    public function __destruct()
    {
        $this->pdo = null;
    }

    public function getPDO()
    {
        return $this->pdo;
    }
}
