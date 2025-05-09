# Base image is from Microsoft
FROM mcr.microsoft.com/vscode/devcontainers/base:ubuntu-22.04

# Avoid interactive prompts (e.g. tzdata) during installation
ENV DEBIAN_FRONTEND=noninteractive

RUN mkdir -p /var/www/html

# Install 8.3 from Ondrej’s PPA
RUN apt-get update && apt-get install -y \
    software-properties-common \
    && add-apt-repository ppa:ondrej/php -y \
    && apt-get update && apt-get install -y sqlite3 \
    && apt-get install -y php8.3-cli php8.3-dev \
       php8.3-pgsql php8.3-sqlite3 php8.3-gd \
       php8.3-curl \
       php8.3-imap php8.3-mysql php8.3-mbstring \
       php8.3-xml php8.3-zip php8.3-bcmath php8.3-soap \
       php8.3-intl php8.3-readline \
       php8.3-ldap \
       php8.3-msgpack php8.3-igbinary php8.3-redis php8.3-swoole \
       php8.3-memcached php8.3-pcov php8.3-imagick php8.3-xdebug \
    && apt-get clean && rm -rf /var/lib/apt/lists/*
