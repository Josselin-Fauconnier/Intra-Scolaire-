RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 775 /var/www/html/var

RUN composer install
