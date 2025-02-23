FROM php:8.1-fpm

USER root

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    zlib1g-dev \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libwebp-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    nano \
    freetype* \
    exif


# نصب GD Library با پشتیبانی از JPEG
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd


RUN docker-php-ext-install exif mbstring pdo_mysql mysqli bcmath pcntl zip xml

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/bin/composer

ENV APP_DIR="/var/www/html"
COPY .. $APP_DIR

RUN chown -R www-data:www-data $APP_DIR

WORKDIR $APP_DIR

EXPOSE 9000

CMD ["php-fpm"]
