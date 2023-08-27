FROM alpine:latest

# cron is already installed in alpine
RUN apk update && \
    apk add --no-cache \
    tzdata \
    curl \
    php82 \
    php82-sqlite3 php82-pdo_sqlite php82-pdo_mysql \
    php82-curl php82-gd php82-mbstring php82-opcache \
    php82-zip \
    && ln -s /usr/bin/php82 /usr/local/bin/php \
    && rm -rf /var/cache/apk/* \
    && mkdir -p /app/log

ENV TZ=Europe/Paris

USER root

WORKDIR /app

# CURRENT DIR MUST CONTAIN FILES: 
# crontab-root, cmd.sh
COPY . /app

# root cron file is located at 
# /var/spool/cron/crontabs/root
RUN cp /app/crontab-root /var/spool/cron/crontabs/root \ 
    && chmod +x /app/*.sh


CMD /app/cmd.sh
