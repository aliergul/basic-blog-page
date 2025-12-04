# Temel imaj olarak PHP 8.2 ve Apache kullanıyoruz
FROM php:8.2-apache

# 1. Gerekli kütüphaneleri ve Node.js'i kuruyoruz (Tailwind için şart)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-install pdo_pgsql bcmath zip

# 2. Apache ayarları: Render'ın verdiği PORT'u dinle ve public klasörünü aç
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf
# Render dinamik port verir, Apache'yi buna zorluyoruz:
RUN sed -i "s/Listen 80/Listen \${PORT}/" /etc/apache2/ports.conf

# 3. Laravel için gerekli olan Rewrite modülünü aç
RUN a2enmod rewrite

# 4. Composer'ı indir (PHP paket yöneticisi)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Çalışma dizinini ayarla
WORKDIR /var/www/html

# 6. Proje dosyalarını kopyala
COPY . .

# 7. Bağımlılıkları yükle ve Build al
# PHP paketlerini kur
RUN composer install --no-dev --optimize-autoloader
# Node paketlerini kur ve CSS'i derle (npm run build)
RUN npm install && npm run build

# 8. Dosya izinlerini ayarla (Laravel'in storage klasörüne yazabilmesi lazım)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache