<?php

namespace App\Database;

use Doctrine\DBAL\DriverManager;
use PDO;
use PDOException;
use Dotenv\Dotenv;
class Connection
{
    /**
     * @var DBALConnection|null
     */
    private static $connection = null;

    /**
     * Retorna a instância de conexão DBAL.
     * @return DBALConnection
     */
    public static function getInstance()
    {
       // Verifica se a conexão já foi criada
        if (self::$connection === null) {

            $dir = __DIR__ . '/../../';
            if (!file_exists($dir . '.env')) {
                http_response_code(500);
                echo json_encode([
                    'error' => 'Arquivo .env não encontrado.',
                    'path' => $dir . 'env'
                ]);
            }

            // Carrega as variáveis do .env se ainda não foram carregadas
            if(!isset($_ENV['DB_HOST'])) {
                $dotenv = Dotenv::createImmutable($dir);
                $dotenv->load();
            }

            // Monta o array de configurações do banco
            $connectionParams = [
                'dbname' => $_ENV['DB_NAME'],
                'user' => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASS'],
                'host' => $_ENV['DB_HOST'],
                'port' => $_ENV['DB_PORT'],
                'driver' => $_ENV['DB_DRIVER'],
            ];

            try {
                // Cria a conexão usando Doctrine DBAL
                self::$connection = DriverManager::getConnection($connectionParams);
            } catch (PDOException $e) {
                // Erro ao se conectar, retorna erro JSON com status 500
                http_response_code(500);
                echo json_encode([
                    'error' => 'Erro ao conectar ao banco de dados',
                    'message' => $e->getMessage()
                ]);
                exit;
            }
        }
        return self::$connection;
    }
}