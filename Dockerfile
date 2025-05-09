# Usa una imagen oficial de PHP como base
FROM php:8.1-fpm

# Crear un nuevo usuario (sin privilegios de root)
RUN useradd -ms /bin/bash laraveluser
USER laraveluser

# Instalar las dependencias necesarias
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip git && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql

# Configurar el directorio de trabajo dentro del contenedor
WORKDIR /var/www

# Copiar el archivo composer.json y composer.lock para instalar las dependencias de Laravel
COPY composer.json composer.lock /var/www/

# Instalar Composer (el gestor de dependencias PHP)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar las dependencias de Laravel
RUN composer install

# Copiar todo el código del proyecto dentro del contenedor
COPY . /var/www

# Configurar permisos para los archivos de Laravel (como almacenamiento y caché)
RUN chown -R laraveluser:laraveluser /var/www/storage /var/www/bootstrap/cache

# Exponer el puerto que usará Laravel (8080 en este caso)
EXPOSE 8080

# Comando para iniciar el servidor de Laravel dentro del contenedor
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
