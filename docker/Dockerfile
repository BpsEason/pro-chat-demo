# ==================== Stage 1: Build Extensions (Swoole & Redis) ====================
FROM php:8.4-cli-alpine AS swoole-builder

# 加入編譯工具與依賴庫
RUN apk add --no-cache \
    git \
    unzip \
    linux-headers \
    autoconf \
    build-base \
    openssl-dev \
    brotli-dev \
    zlib-dev

# 同時安裝 swoole 與 redis 擴展
# printf "\n" 是為了自動跳過 pecl install 的互動式詢問
RUN printf "\n" | pecl install swoole redis \
    && docker-php-ext-enable swoole redis

# ==================== Stage 2: Final Runtime Image ====================
FROM php:8.4-cli-alpine

# 1. 運行時依賴 (核心運作、Node.js、MySQL 工具、Swoole 所需的 C++ 庫與編碼庫)
RUN apk add --no-cache \
    git \
    unzip \
    libpng-dev \
    libzip-dev \
    zip \
    icu-dev \
    nodejs \
    npm \
    tzdata \
    libstdc++ \
    curl-dev \
    brotli \
    mysql-client

# 2. 安裝 Laravel 常用核心 PHP 擴展
RUN docker-php-ext-install pdo_mysql gd zip pcntl intl bcmath

# 3. 從 builder 複製已編譯好的 Swoole 與 Redis 擴展檔
# PHP 8.4 的 API 版本號為 20240924
COPY --from=swoole-builder /usr/local/lib/php/extensions/no-debug-non-zts-20240924/swoole.so /usr/local/lib/php/extensions/no-debug-non-zts-20240924/swoole.so
COPY --from=swoole-builder /usr/local/lib/php/extensions/no-debug-non-zts-20240924/redis.so /usr/local/lib/php/extensions/no-debug-non-zts-20240924/redis.so

# 4. 複製擴展的啟動配置文件
COPY --from=swoole-builder /usr/local/etc/php/conf.d/docker-php-ext-swoole.ini /usr/local/etc/php/conf.d/docker-php-ext-swoole.ini
COPY --from=swoole-builder /usr/local/etc/php/conf.d/docker-php-ext-redis.ini /usr/local/etc/php/conf.d/docker-php-ext-redis.ini

# 5. 複製 Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 6. 目錄預建與權限校準
# 確保在掛載 Volumes 之前，內部的目錄結構與權限已經正確
RUN mkdir -p \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# 7. 複製並設定啟動腳本
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# 暴露服務端口
EXPOSE 8000 8080

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]