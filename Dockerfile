FROM php:8.4-apache

# 1. Gerekli kütüphaneleri ve Node.js'i kuruyoruz
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-install pdo_pgsql bcmath zip

# 2. Modülleri Aktif Et
RUN a2enmod rewrite

# 3. Composer'ı indir
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Çalışma Dizini
WORKDIR /var/www/html

# 5. Dosyaları Kopyala
COPY . .

# 6. Bağımlılıkları Yükle
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# --- DÜZELTME BURADA BAŞLIYOR ---

# 7. Apache Ayar Dosyasını (VirtualHost) Sıfırdan Yazıyoruz
# Eski dosyayı değiştirmekle uğraşmıyoruz, direkt yenisini oluşturuyoruz.
# DocumentRoot'u /public olarak ayarlıyoruz ve izinleri veriyoruz.
RUN echo '<VirtualHost *:80>\n\
    ServerName localhost\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
    </VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# 8. Render Port Ayarı
# Apache varsayılan olarak 80 portunu dinler ama Render dinamik port verir ($PORT).
# Ayar dosyalarındaki "80" rakamlarını Render'ın vereceği port değişkeniyle değiştiriyoruz.
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# 9. Dosya Sahipliğini Ayarla
RUN chown -R www-data:www-data /var/www/html

CMD bash -c "php artisan migrate --force && apache2-foreground"