FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip zip git curl libpng-dev libonig-dev libxml2-dev

# Enable Apache rewrite (important for Laravel)
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port
EXPOSE 80

CMD ["apache2-foreground"]