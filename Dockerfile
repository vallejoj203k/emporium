# Imagen base con PHP 8.2
FROM php:8.2-cli

# Dependencias del sistema y extensiones de PHP que usa Laravel
RUN apt-get update && apt-get install -y \
        git unzip zip curl libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring zip gd bcmath exif \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Node 20 para compilar los assets con Vite
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

# Copiamos el proyecto e instalamos dependencias
COPY . /app

RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && npm ci \
    && npm run build \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8080

# Espera a que la base de datos esté lista, migra, carga datos iniciales
# (admin + catálogos) y arranca el servidor.
CMD for i in 1 2 3 4 5 6 7 8 9 10; do \
        php artisan migrate --force --seed && break; \
        echo "Base de datos no lista, reintentando en 5s... (intento $i)"; \
        sleep 5; \
    done; \
    php artisan serve --host 0.0.0.0 --port ${PORT:-8080}
