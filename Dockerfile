# Usa una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Copia todos tus archivos al directorio web
COPY . /var/www/html/

# Da permisos necesarios
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expone el puerto por defecto
EXPOSE 80
