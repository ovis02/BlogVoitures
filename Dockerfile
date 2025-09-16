# Dockerfile
FROM php:8.2-cli

# Outils + extension MongoDB + zip
RUN apt-get update && apt-get install -y git unzip libzip-dev \
 && pecl install mongodb \
 && docker-php-ext-enable mongodb \
 && docker-php-ext-install zip \
 && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Dossier de travail
WORKDIR /app

