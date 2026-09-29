<?php

namespace App;

use PDO;

class Database
{
    private PDO $connection;

    public function __construct()
    {
        $host = $_ENV['DB_HOST'] ?? 'db';
        $port = $_ENV['DB_PORT'] ?? '3306';
        $database = $_ENV['DB_NAME'] ?? 'app';
        $username = $_ENV['DB_USER'] ?? 'app';
        $password = $_ENV['DB_PASSWORD'] ?? 'app';

        $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";

        $this->connection = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}