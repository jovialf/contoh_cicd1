FROM php:8.2-apache

COPY index.php /var/www/html/
COPY style.css /var/www/html/
COPY data.json /var/www/html/

# Ubah permission
RUN chown -R www-data:www-data /var/www/html && \
    find /var/www/html -type d -exec chmod 755 {} \; && \
    find /var/www/html -type f -exec chmod 644 {} \;

# Izinkan .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

RUN a2enmod rewrite
