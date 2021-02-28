FROM ambientum/php:7.4-nginx

USER root

RUN apk update && \
    apk add php7-gmp@php && \
    apk add autoconf && \
    apk add openssl && \
    apk add --no-cache tzdata

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