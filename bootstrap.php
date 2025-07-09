<?php

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Dotenv\Dotenv;

// Carrega variáveis do .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Logger de buscas (search.log)
$searchLog = new Logger('search');
$searchLog->pushHandler(new RotatingFileHandler(__DIR__ . '/storage/logs/search.log', 7, Logger::INFO));

// Logger de erros (error.log)
$errorLog = new Logger('error');
$errorLog->pushHandler(new RotatingFileHandler(__DIR__ . '/storage/logs/error.log', 7, Logger::ERROR));

// Exporta ambos como array
return [
    'search' => $searchLog,
    'error' => $errorLog,
];