FROM php:8.2-fpm-alpine

ARG UID
ARG GID
ARG NETWORK_NAME

ENV UID=${UID}
ENV GID=${GID}

RUN mkdir -p /var/www/html

WORKDIR /var/www/html

RUN addgroup -g ${GID} --system ${NETWORK_NAME}
RUN adduser -G ${NETWORK_NAME} --system -D -s /bin/sh -u ${UID} ${NETWORK_NAME}

RUN sed -i "s/user = www-data/user = ${NETWORK_NAME}/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = ${NETWORK_NAME}/g" /usr/local/etc/php-fpm.d/www.conf
RUN echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf

RUN apk update && apk add curl nodejs npm && \
  curl -sS https://getcomposer.org/installer | php \
  && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer   

RUN mkdir -p /usr/src/php/ext/redis \
    && curl -L https://github.com/phpredis/phpredis/archive/master.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
    && echo 'redis' >> /usr/src/php-available-exts \
    && docker-php-ext-install redis 

USER ${NETWORK_NAME}

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]