FROM php:8.1-apache

# Sao chép mã nguồn vào thư mục /var/www/html
COPY . /var/www/html

# Đặt quyền cho thư mục
RUN chown -R www-data:www-data /var/www/html

# Mở cổng 80
EXPOSE 80
