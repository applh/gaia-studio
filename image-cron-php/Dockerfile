FROM alpine:latest AS cron-php
# add about 250 Mo disk space

# cron, sendmail is already installed in alpine / busybox
# warning: 
    # about 150 Mo disk space
    # php82-pecl-imagick \
    # about 60 Mo disk space
    # ffmpeg \

RUN apk update && \
    apk add --no-cache \
    tzdata \
    curl \
    php82 \
    php82-sqlite3 php82-pdo_sqlite php82-pdo_mysql php82-mysqli \
    php82-gd php82-curl php82-zip php82-mbstring php82-bcmath php82-opcache \
    php82-gettext php82-intl php82-exif php82-sockets \ 
    php82-pecl-memcached php82-pecl-ssh2 \
    && ln -s /usr/bin/php82 /usr/local/bin/php \
    && rm -rf /var/cache/apk/* \
    && mkdir -p /app/log

RUN apk add --no-cache \
    php82-pecl-imagick \
    ffmpeg \
    busybox-extras \
    && touch /app/cmd.sh \
    && rm -rf /var/cache/apk/*

CMD /app/cmd.sh

# FIXME: Conflict with MacOS already using port 25
# mail port
# EXPOSE 25

# RUN docker-php-ext-configure mysqli && docker-php-ext-install mysqli



# add a new image to cron-php
FROM cron-php AS cron-php-chromium
# add about 450 Mo disk space
    # chromium \

RUN apk add --no-cache \
    chromium \
    && rm -rf /var/cache/apk/*


# add a new image to cron-php-chromium
FROM cron-php-chromium AS cron-php-chromium-jupyter
# add about 170 Mo disk space

RUN apk add --no-cache \
    jupyter-notebook \
    && rm -rf /var/cache/apk/*

ENV TZ=Europe/Paris

USER root

WORKDIR /app

# tip: keep at the end to avoid re-run all the time
# CURRENT DIR MUST CONTAIN FILES: 
# crontab-root, cmd.sh
COPY . /app

# root cron file is located at 
# /var/spool/cron/crontabs/root
RUN cp /app/crontab-root /var/spool/cron/crontabs/root \ 
    && chmod +x /app/*.sh

