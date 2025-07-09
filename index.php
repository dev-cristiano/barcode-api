<?php

require_once './vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
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

// Definir cabeçalhos
header('Content-Type: application/json');

// Obter URI e método
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Rota de busca por código de barras
if ($method === 'GET' && $uri === '/api/produtos') {

    $barcode = $_GET['barcode'] ?? null;

    if (!$barcode) {
        http_response_code(400);
        echo json_encode(['error' => 'Parâmetro barcode é obrigatório']);
        exit;
    }

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
    exit;
}

// Rota não encontrada
http_response_code(404);
echo json_encode(['error' => 'Rota não encontrada']);
