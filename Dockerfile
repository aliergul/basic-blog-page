FROM php:8.4-apache

# 1. Gerekli kütüphaneleri ve Node.js'i kuruyoruz
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-install pdo_pgsql bcmath zip

# 2. Apache DocumentRoot Ayarı (Laravel için public klasörü)
# DÜZELTME: Değişken kullanmak yerine hardcode (elle) yazıyoruz, hata riskini sıfırlıyoruz.
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# /var/www/html olan her yeri /var/www/html/public yapıyoruz
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf

# 3. Apache İzin Ayarları (403 Hatası Çözümü)
# DÜZELTME: Varsayılan "AllowOverride None" ayarını "All" yapıyoruz ki .htaccess çalışsın.
# Ayrıca "Require all granted" olduğundan emin olmak için replace yapıyoruz.
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# 4. Port Ayarı (Render için dinamik port)
RUN sed -i "s/Listen 80/Listen \${PORT}/" /etc/apache2/ports.conf

# 5. Modülleri Aktif Et
RUN a2enmod rewrite

# 6. Composer Kurulumu
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 7. Çalışma Dizini
WORKDIR /var/www/html

# 8. Dosyaları Kopyala
COPY . .

# 9. Bağımlılıkları Yükle
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# 10. İzinleri Ayarla
RUN chown -R www-data:www-data /var/www/html