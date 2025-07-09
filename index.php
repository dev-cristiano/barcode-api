<?php

require_once './vendor/autoload.php';

use App\Controllers\ProductController;
use App\Database\Connection;

// Extrai informações da requisição HTTP
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Remove barras extras e normaliza a URI
$uri = rtrim($uri, '/');
if (empty($uri)) {
    $uri = '/';
}

// Verifica se a requisição corresponde á rota esperada
if ($method === 'GET' && $uri === '/api/produtos') {
    $barcode = $_GET['barcode'] ?? null;

    $controller = new ProductController();
    $controller->getProductByCode($barcode);
    $connection = new Connection();
    exit;
}

// Caso a rota não exista, retorna erro 404
http_response_code(404);
header('Content-Type: application/json');
echo json_encode(['error' => 'Rota não encontrada']);