<?php
declare(strict_types=1);

include_once('config.php');

class DBConnector
{

    private static $_pdo = null;
    private static $instance = null;


    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new DBConnector();
        }
        return self::$instance;
    }

    private function __construct()
    {
        self::$_pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";", DB_USER, DB_PASSWORD);
    }

    public static function getConnection(): PDO
    {
        return self::$_pdo;
    }
}

?>