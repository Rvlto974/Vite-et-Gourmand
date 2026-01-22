FROM php:8.2-fpm

# Installation
RUN apt-get update && apt-get install -y \
    nginx git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql mysqli mbstring exif pcntl bcmath gd zip
RUN pecl install mongodb && docker-php-ext-enable mongodb

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Config Nginx
RUN echo 'server { listen 80; root /var/www/html/public; index index.php; location / { try_files \ \/ /index.php?\; } location ~ \.php\$ { fastcgi_pass 127.0.0.1:9000; fastcgi_index index.php; include fastcgi_params; fastcgi_param SCRIPT_FILENAME \\; } }' > /etc/nginx/sites-available/default

COPY ./src /var/www/html
WORKDIR /var/www/html
RUN cd public && composer install --no-dev --optimize-autoloader || true
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD service php8.2-fpm start && nginx -g 'daemon off;'
