<?php

namespace App\Database;

use PDO;
use PDOException;
use Dotenv\Dotenv;
class Connection
{
    private static ?PDO $instace = null;

    public static function getInstance(): PDO
    {
        if (self::$instace === null) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
            $dotenv->load();

            $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
            $port = $_ENV['DB_PORT'] ?? '3306';
            $dbname = $_ENV['DB_NAME'] ?? '';
            $username = $_ENV['DB_USER'] ?? '';
            $password = $_ENV['DB_PASS'] ?? '';

            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

            try {
                $pdo = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode([
                    'error' => 'Erro ao se conectar ao banco de dados.',
                    'message' => $e->getMessage()
                ]);
                exit;
            }
        }
        return self::$instace;
    }
}