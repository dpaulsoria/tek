# 1) Base PHP + Composer
FROM php:8.2-fpm

# 2) Instala deps del sistema, extensiones de PHP y Composer
RUN apt-get update \
 && apt-get install -y \
      curl git unzip zip libpng-dev libonig-dev libxml2-dev \
 && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
 && curl -sS https://getcomposer.org/installer \
      | php -- --install-dir=/usr/local/bin --filename=composer \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*

# 3) Node.js para compilar assets
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
 && apt-get install -y nodejs \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*

# 4) Copiamos *todo* el código antes de instalar deps
WORKDIR /usr/src/app
COPY . .

# 5) Instalamos deps PHP (ahora sí existe bootstrap/app.php)
RUN composer install --no-dev --optimize-autoloader
RUN php artisan ziggy:generate resources/js/ziggy.js

# 6) Instalamos deps JS y compilamos assets
RUN npm ci \
 && npm run build

# 7) Exponemos el puerto de la app
EXPOSE 10000

# 8) Entrypoint: tu start.sh se encargará de certs, migraciones y levantar el servidor
COPY start.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/start.sh
ENTRYPOINT ["start.sh"]
