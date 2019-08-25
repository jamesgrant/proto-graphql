FROM php:7.3-cli

# Disable superuser warning in composer.
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install PHP extensions.
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        # Required for composer.
        git \
        zip \
        libzip-dev && \
    apt-get -y autoremove && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install composer
RUN curl --silent --show-error https://getcomposer.org/installer | php -- \
        --install-dir=/usr/local/bin \
        --filename=composer
