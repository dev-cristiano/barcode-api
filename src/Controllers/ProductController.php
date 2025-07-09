<?php

namespace App\Controllers;

use App\Database\Connection;
use Doctrine\DBAL\Exception;

class ProductController
{
    public function getProductByCode($barcode): void
    {

        header('Content-Type: application/json');

        // Validação do parâmetro
        if(!$barcode) {
            http_response_code(400);
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
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Produto não encontrado']);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'error' => 'Erro ao consultar o produto no banco de dados',
                'message' => $e->getMessage()
            ]);
        }
    }
}