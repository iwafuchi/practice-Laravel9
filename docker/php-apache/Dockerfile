FROM php:8.1.1-apache

# プロジェクトのパスを設定する
ENV APACHE_DOCUMENT_ROOT /var/www/html/marche/public

# apacheのドキュメントルートを変更する
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# composerのinstall
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN apt-get update && \
    apt-get -y install --no-install-recommends git unzip libzip-dev libicu-dev libonig-dev && \
    docker-php-ext-install intl pdo_mysql zip bcmath nodejs && \
    cp /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/ && \
    cp /etc/apache2/mods-available/headers.load /etc/apache2/mods-enabled/ && \
    curl -sL https://deb.nodesource.com/setup_16.x | bash - && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*