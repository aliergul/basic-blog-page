# GÜNCELLEME: PHP 8.2 Yerine 8.4 kullanıyoruz
FROM php:8.4-apache

# 1. Gerekli kütüphaneleri ve Node.js'i kuruyoruz
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-install pdo_pgsql bcmath zip

# 2. Apache ayarları
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf
RUN sed -i "s/Listen 80/Listen \${PORT}/" /etc/apache2/ports.conf

# 3. Rewrite modülünü aç
RUN a2enmod rewrite

# 4. Composer'ı indir
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Çalışma dizini
WORKDIR /var/www/html

# 6. Dosyaları kopyala
COPY . .

# 7. Paketleri yükle (Production modu)
# --ignore-platform-reqs ekledik ki ufak sürüm farklarına takılmasın
RUN composer install --no-dev --optimize-autoloader

# 8. Frontend build al
RUN npm install && npm run build

# 9. İzinleri ayarla
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache