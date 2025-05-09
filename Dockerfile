# Usa una imagen oficial de PHP con Apache
FROM php:8.1-apache

# Instala las dependencias necesarias para Laravel
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev libzip-dev unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip

# Habilita el mod_rewrite de Apache (necesario para Laravel)
RUN a2enmod rewrite

# Copia los archivos del proyecto al contenedor
COPY . /var/www/html/

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Instala Composer (gestor de dependencias PHP)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instala las dependencias de Laravel
RUN composer install

# Exponer el puerto 80 (por defecto de Apache)
EXPOSE 80

# Inicia Apache en primer plano
CMD ["apache2-foreground"]
