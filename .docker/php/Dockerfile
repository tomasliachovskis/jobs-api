FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libcurl4-gnutls-dev \
    libzip-dev \
    zlib1g-dev \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install zip curl xml pdo pdo_mysql mbstring pcntl bcmath gd

# Install Redis PHP extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents to the working directory
COPY . .

# Set appropriate permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 9000 for PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
