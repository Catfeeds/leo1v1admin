FROM php:7.1-fpm-alpine
RUN  sed -i 's/dl-cdn.alpinelinux.org/mirrors.ustc.edu.cn/g' /etc/apk/repositories  && \
    apk update &&\
    apk add   libpng-dev curl-dev  libmcrypt-dev  freetype-dev
    RUN    docker-php-ext-configure gd --with-freetype-dir=/usr/include/ && /usr/local/bin/docker-php-ext-install pdo pdo_mysql curl  gd   mcrypt session
CMD ["php-fpm"]
