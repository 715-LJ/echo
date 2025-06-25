FROM php:8.2-fpm

# 更新APT包并安装基本工具
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    vim \
    nano \
    zip \
    git \
    unzip \
    curl \
    openssl \
    ca-certificates \
    supervisor

# 安装Redis扩展
RUN pecl install redis && docker-php-ext-enable redis

# 安装Memcached扩展
#RUN apt-get update && apt-get install -y libmemcached-dev libssl-dev zlib1g-dev \
#	&& pecl install memcached-3.2.0 \
#	&& docker-php-ext-enable memcached \

# 安装PHP扩展
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) bcmath gd zip pdo pdo_mysql

# 清理缓存
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 修复SSL证书问题
RUN update-ca-certificates

# 安装Composer（带重试机制和超时设置）
RUN curl -sS --retry 3 --retry-delay 1 --connect-timeout 30 https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=composer \
    && chmod +x /usr/local/bin/composer

# 设置工作目录
WORKDIR /var/www/html

# 验证Composer安装
RUN composer --version && composer clear-cache

# 分阶段复制文件
COPY ./api/composer.* ./

# 安装依赖
RUN php -d memory_limit=-1 /usr/local/bin/composer install \
    --no-dev \
    --no-scripts \
    --no-interaction \
    --optimize-autoloader

# 复制应用文件
COPY ./api .

# 复制Supervisor配置
COPY ./config/supervisor/ /etc/supervisor/

# 设置权限
RUN chown -R www-data:www-data /var/www/html

# 检查 Supervisor 配置
RUN test -f /etc/supervisor/supervisord.conf && echo "Supervisor config exists." || echo "Supervisor config missing!"

CMD ["sh", "-c", "php-fpm"]