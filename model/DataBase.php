<?php

class DataBase
{
    private $connection = null;

    function __construct()
    {
        $host = 'localhost';
        $port = '3306';
        $dataBase = 'user_api';
        $user = 'root';
        $password = 'root';

        try {
            $this->connection = new PDO(
                "mysql:host=$host;port=$port;charset=utf8mb4;dbname=$dataBase",
                $user,
                $password
            );
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
