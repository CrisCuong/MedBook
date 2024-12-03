# Sử dụng PHP với Apache
FROM php:8.2-apache

# Sao chép toàn bộ mã nguồn của ứng dụng vào container
COPY . /var/www/html/

# Mở cổng 80 để cho phép truy cập vào ứng dụng
EXPOSE 80
