FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql mysqli mbstring exif pcntl bcmath gd zip
RUN pecl install mongodb && docker-php-ext-enable mongodb

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Désactiver tous les MPM puis activer uniquement prefork
RUN a2dismod mpm_event mpm_worker 2>/dev/null || true
RUN a2enmod mpm_prefork
RUN a2enmod rewrite

RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

COPY ./src /var/www/html
WORKDIR /var/www/html
RUN cd public && composer install --no-dev --optimize-autoloader || true
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

RUN mkdir -p /var/lib/php/sessions && chown -R www-data:www-data /var/lib/php/sessions

RUN echo 'display_errors = On' >> /usr/local/etc/php/conf.d/custom.ini && \
    echo 'error_reporting = E_ALL' >> /usr/local/etc/php/conf.d/custom.ini && \
    echo 'session.save_path = "/var/lib/php/sessions"' >> /usr/local/etc/php/conf.d/custom.ini

EXPOSE 80

CMD ["apache2-foreground"]
