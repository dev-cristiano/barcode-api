<?php

namespace App\Controllers;

use App\Database\Connection;
use PDOException;

class ProductController
{
    public function getProductByCode(?string $barcode)
    {
        // Definir cabeçalhos
        header('Content-Type: application/json');

        if (!$barcode) {
            http_response_code(400);
            echo json_encode(['error' => 'Parâmetro barcode é obrigatório']);
            exit;
        }

        $pdo = Connection::getInstance();

        try {
            $stmt = $pdo->prepare("SELECT * FROM produtos_geral WHERE cod_barras = :barcode");
            $stmt->execute(['barcode' => $barcode]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                echo json_encode($product);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Produto não encontrado']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'error' => 'Erro ao consultar o banco de dados' . $e->getMessage()]);
        }
    }
}