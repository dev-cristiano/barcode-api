<?php

namespace App\Controllers;

use App\Database\Connection;
use Doctrine\DBAL\Exception;
use Monolog\Logger;

class ProductController
{
    public function getProductByCode($barcode): void
    {

        header('Content-Type: application/json');

        // Carrega os loggers
        $logger = require __DIR__ . "/../../bootstrap.php";
        $searchLogger = $logger["search"];
        $errorLogger = $logger["error"];

        // Validação do parâmetro
        if(!$barcode) {
            http_response_code(400);

            $errorLogger->warning('Consulta sem código de barras', [
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);

            echo json_encode(['error' => 'Parâmetro barcode é obrigatório']);
            return;
        }

        // Obtém a conexão com o banco
        $conn = Connection::getInstance();

        try {

            // Monta o QueryBuilder
            $qb = $conn->createQueryBuilder();

            $qb->select('*')
                ->from('produtos_geral')
                ->where('cod_barras = :barcode')
                ->setParameter('barcode', $barcode);

            // Executa a query
            $result = $qb->executeQuery();

            // Busca o primeiro registro como array associativo
            $product = $result->fetchAssociative();

            // Verifica se encontrou o produto
            if ($product) {
                http_response_code(200);
                echo json_encode($product);

                $searchLogger->info('Produto encontrado', [
                    'barcode' => $barcode,
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);

            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Produto não encontrado']);

                $searchLogger->info('Produto não encontrado', [
                    'barcode' => $barcode,
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'error' => 'Erro ao consultar o produto no banco de dados',
                'message' => $e->getMessage()
            ]);

            $errorLogger->error('Erro ao buscar produto', [
                'barcode' => $barcode,
                'message' => $e->getMessage(),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
        }
    }
}