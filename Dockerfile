FROM ambientum/php:7.2-nginx

USER root

RUN apk update && \
    apk add php7-dev@php && \
	apk add php7-pear@php && \
    apk add php7-gmp@php && \
    apk add php7-mcrypt@php && \
    apk add php7-libsodium@php && \
    apk add autoconf && \
    apk add openssl && \
    apk add --no-cache tzdata && \
    pecl channel-update pecl.php.net && \
    pear config-set php_ini /etc/php7/php.ini

COPY cron /etc/cron.d/cron

RUN chmod 0644 /etc/cron.d/cron
RUN crontab /etc/cron.d/cron

RUN touch /var/log/cron.log
RUN touch /var/log/queue.log

ENV TZ=America/Sao_Paulo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

USER ambientum

WORKDIR /var/www/app

COPY start.sh .

RUN sudo chmod +x start.sh

CMD ["/var/www/app/start.sh"]