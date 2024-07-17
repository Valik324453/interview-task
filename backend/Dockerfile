# backend/Dockerfile
FROM php:7.4-apache

# Активируем mod_rewrite
RUN a2enmod rewrite

# Активируем mod_headers
RUN a2enmod headers

# Устанавливаем расширение mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Копируем ваш код в контейнер
COPY . /var/www/html/

# Копируем конфигурацию Apache
COPY apache2.conf /etc/apache2/sites-available/000-default.conf

# Добавляем инициализацию базы данных MySQL
# Копируем SQL-скрипт инициализации в контейнер
COPY init.sql /docker-entrypoint-initdb.d/

# Перезапускаем Apache
CMD ["apache2-foreground"]