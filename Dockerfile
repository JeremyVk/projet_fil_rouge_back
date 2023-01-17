FROM php:8.1-apache

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions pdo_mysql intl

# RUN curl -sS https://getcomposer.org/installer | php -- --disable-tls && \
#     mv composer.phar /usr/local/bin/composer

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update \
        && apt-get install -y --no-install-recommends \
        git \
        unzip \
        automake \
        make
  
COPY . /var/www/

COPY ./.env /var/www

COPY ./docker/apache/apache.conf /etc/apache2/sites-available/000-default.conf

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN cd /var/www && \
    composer install

RUN cd /var/www && \
    php bin/console lexik:jwt:generate-keypair

WORKDIR /var/www/

# ENTRYPOINT ["./docker/docker.sh"]


EXPOSE 80