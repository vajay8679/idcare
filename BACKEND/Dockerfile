FROM php:7.4-apache

# Install required libs before extensions
RUN apt-get update && apt-get install -y \
    libbz2-dev \
    libc-client-dev libkrb5-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmemcached-dev zlib1g-dev \
    libmcrypt-dev \
    libpq-dev \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    sendmail \
    vim \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install imap \
    && rm -r /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install bcmath bz2 calendar dba exif gettext mysqli opcache pcntl pdo_mysql pdo_pgsql pgsql shmop soap sockets sysvmsg sysvsem sysvshm zip

# Install pecl extensions
# RUN pecl install memcached redis xdebug && docker-php-ext-enable memcached redis xdebug

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Enable apache rewrite module
RUN a2enmod rewrite