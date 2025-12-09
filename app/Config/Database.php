<?php

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $conn;
    private $error;

    private $host;
    private $db_name;
    private $username;
    private $password;

    private function __construct()
    {
        $this->host = env('DB_HOST', 'localhost');
        $this->db_name = env('DB_NAME', 'hospital_db');
        $this->username = env('DB_USER', 'root');
        $this->password = env('DB_PASS', '');

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            $this->error = "Connection error: " . $exception->getMessage();
            $this->conn = null;
        }
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function getError()
    {
        return $this->error;
    }
}
