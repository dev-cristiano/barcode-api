<?php

namespace App\Controllers;

use App\Database\Connection;
use PDOException;

class ProductController
{
    public function getProductByCode(?string $barcode)
    {
        $conn = Connection::getInstance();
    }
}