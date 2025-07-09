<<<<<<< HEAD
# Barcode API

API REST para consulta de produtos por código de barras, construída em PHP com Doctrine DBAL e Monolog, rodando em container Docker com Apache.

---

## 🛠 Tecnologias Utilizadas

- PHP 8.2
- Doctrine DBAL
- PostgreSQL
- Docker + Docker Compose
- Apache HTTP Server
- Composer
- vlucas/phpdotenv (para variáveis de ambiente)
- Monolog

---

## 🚀 Como executar a aplicação

### Pré-requisitos

- Docker instalado (https://docs.docker.com/get-docker/)
- Docker Compose instalado (https://docs.docker.com/compose/install/)

### Passos para rodar

1. Clone este repositório:
   ```bash
   git clone https://github.com/seu-usuario/barcode-api.git
   cd barcode-api

2. Build e suba o container Docker:
   ```bash
   docker-compose up --build -d

3. Acesse a API no navegador ou via cliente HTTP:
   http://localhost:8000/api/produtos?barcode=SEU_CODIGO_DE_BARRAS
=======
>>>>>>> 77a4ba57f5c4c9e04942d122a599f4f2f9545501
