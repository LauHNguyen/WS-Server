FROM php:8.2-fpm

# Cài Nginx và các gói cần thiết
RUN apt-get update && apt-get install -y nginx

# Cài Composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Copy code vào container
WORKDIR /var/www/html
COPY .env.development /var/www/html/.env.development
COPY . .

# Cài dependencies với Composer
RUN composer install --no-dev --optimize-autoloader

# Cấu hình Nginx
COPY nginx.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/

# Đảm bảo quyền cho thư mục
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Expose port
EXPOSE 80

# Chạy cả Nginx và PHP-FPM
CMD service nginx start && php-fpm