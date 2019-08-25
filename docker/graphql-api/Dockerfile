FROM php:7.3-apache

# Set web root.
ENV APACHE_DOCUMENT_ROOT /var/www/public/

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Install PHP extensions.
RUN docker-php-ext-install -j$(nproc) pdo_mysql

# Update configuration.
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
# COPY config/craft.ini $PHP_INI_DIR/conf.d/

# Enable Apache rewrites.
# RUN a2enmod rewrite
