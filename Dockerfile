# Imagem ofifical do PHP com Apache
FROM php:8.2-apache

# Dependências necessárias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    && docker-php-ext-install pdo_pgsql

# Instala o Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Define diretório da aplicação dentro do container
WORKDIR /var/www/html

# Copia todos os arquivos do projeto para dentro do container
COPY . .

# Instala as dependências PHP dentro do container
RUN composer install --no-interaction --no-scripts --no-dev

# Ativa o mod_rewrite do Apache
RUN a2enmod rewrite

RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Expondo porta 80
EXPOSE 80