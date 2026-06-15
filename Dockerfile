FROM php:8.2-apache

# 1. Instal dependensi sistem dan ekstensi PHP yang dibutuhkan Laravel & Filament
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo_mysql gd intl zip

# 2. Aktifkan mod_rewrite Apache agar routing web.php Laravel berfungsi
RUN a2enmod rewrite

# 3. Atur DocumentRoot Apache langsung mengarah ke folder public Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Atur folder kerja di dalam kontainer Docker
WORKDIR /var/www/html

# 5. Salin seluruh file proyek BENGKEL_AMTECH ke dalam kontainer
COPY . .

# 6. Ambil Composer resmi untuk menginstal paket PHP Laravel
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 7. Berikan izin akses (permission) folder storage dan cache agar Laravel tidak error 500
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Buka port 80 untuk akses web
EXPOSE 80