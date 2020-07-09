<?php
namespace App\Logic;


use PDO;
use PDOException;

class Connection
{
    protected static $conn;

    public function getInstance()
    {
        $AppConfig = require '../config/database.php';
        $type = $AppConfig['type'];
        $host = $AppConfig['host'];
        $database = $AppConfig['database'];
        $username = $AppConfig['username'];
        $password = $AppConfig['password'];
        $dsn = "{$type}:host={$host};dbname={$database}";
        if (empty(self::$conn)){
            try {
                self::$conn = new PDO($dsn, $username, $password, array(PDO::ATTR_PERSISTENT => true));
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$conn->exec('SET NAMES utf8');
                self::$conn->exec('SET CHARACTER SET utf8');
            } catch (PDOException $e) {
                echo "Error!: " . $e->getMessage();
                die();
            }
        }

        return self::$conn;
    }
}