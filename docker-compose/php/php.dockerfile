FROM php:8.2-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install dependencies
RUN apt-get update && apt-get install -qy git curl zip libmcrypt-dev unzip libfreetype6-dev libjpeg62-turbo-dev libpng-dev libzip-dev zlib1g-dev libxrender1 libfontconfig1
# Configure and install extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-configure pcntl --enable-pcntl
RUN docker-php-ext-install -j$(nproc) pdo_mysql gd pcntl exif ctype bcmath zip
RUN docker-php-ext-enable zip

# Activate shell
RUN rm /bin/sh && ln -s /bin/bash /bin/sh

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www

