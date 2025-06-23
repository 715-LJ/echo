FROM --platform=$BUILDPLATFORM php:8.2-fpm

# 1. 安装系统依赖（包含SSL相关依赖）
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    git \
    unzip \
    curl \
    openssl \
    ca-certificates \
    supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) bcmath gd zip pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. 修复SSL证书问题
RUN update-ca-certificates

# 3. 安装Composer（带重试机制和超时设置）
RUN curl -sS --retry 3 --retry-delay 1 --connect-timeout 30 https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=composer \
    && chmod +x /usr/local/bin/composer

#RUN mkdir -p /var/log/supervisor
#COPY ./config/supervisor/supervisord.conf /etc/supervisor/supervisord.conf
#RUN chmod 644 /etc/supervisor/supervisord.conf

# 4. 设置工作目录
WORKDIR /var/www/html

# 5. 验证Composer安装
RUN composer --version

# 6. 分阶段复制文件
COPY ./api/composer.* ./

# 7. 安装依赖（带内存限制解除）
RUN php -d memory_limit=-1 /usr/local/bin/composer install \
    --no-dev \
    --no-scripts \
    --no-interaction \
    --optimize-autoloader

# 8. 复制应用文件
COPY ./api .

# 9. 复制Supervisor配置
COPY ./config/supervisor/supervisord.conf /etc/supervisord.conf

CMD ["sh", "-c", "php-fpm & supervisord -c /etc/supervisord.conf"]