FROM php:8.2-apache

# System dependencies
RUN apt-get update && apt-get install -y \
    unzip zip git curl libpng-dev libonig-dev libxml2-dev

# Apache rewrite (important for Laravel routes)
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Laravel required permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 775 storage bootstrap/cache

# IMPORTANT: ensure storage directories exist
RUN mkdir -p storage/app/public
RUN mkdir -p storage/framework
RUN mkdir -p storage/logs

# Apache config for Laravel public folder
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80

CMD ["apache2-foreground"]
