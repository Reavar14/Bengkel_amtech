FROM php:8.2-cli

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

# 2. Atur folder kerja
WORKDIR /var/www/html

# 3. Salin seluruh file proyek (Termasuk folder public/build yang sudah matang dari lokal)
COPY . .

# 4. Ambil Composer resmi dan instal paket PHP
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 5. Berikan izin akses folder storage dan cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 6. Buka port 80
EXPOSE 80

# 7. Jalankan server internal Laravel secara langsung
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]