FROM php:8.0-fpm

# Install PHP extensions
RUN apt update
RUN apt install -y libzip-dev zip \
  && docker-php-ext-install zip

# Install dependencies
RUN apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libpq-dev
## NPM
RUN apt-get install -y nodejs npm
RUN npm install n -g
RUN n stable

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install DB extensions
RUN docker-php-ext-install pdo pdo_pgsql

# Install & setup xDebug
RUN pecl install xdebug-3.0.3
RUN docker-php-ext-enable xdebug
RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy files
COPY ./application /var/www

# Copy existing application directory permissions
COPY --chown=www:www ./application /var/www

WORKDIR /var/www

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
