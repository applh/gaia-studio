FROM php:fpm as php-fpm-jupyter

RUN apt-get update --fix-missing

# SETUP system libs and other apps
RUN apt-get install -y curl cron build-essential \
libssl-dev zlib1g-dev libpng-dev libjpeg-dev libfreetype6-dev libzip-dev libssh2-1-dev \
ffmpeg libssh2-1 ghostscript \
imagemagick libmagickwand-dev \
memcached libmemcached-dev \
python3-pip

# SETUP php extensions
RUN docker-php-ext-configure gd && docker-php-ext-install gd

RUN docker-php-ext-configure pdo_mysql && docker-php-ext-install pdo_mysql

RUN docker-php-ext-configure mysqli && docker-php-ext-install mysqli

RUN pecl install imagick

RUN docker-php-ext-enable imagick

RUN docker-php-ext-configure exif && docker-php-ext-install exif

RUN docker-php-ext-configure zip && docker-php-ext-install zip

RUN docker-php-ext-configure bcmath && docker-php-ext-install bcmath

RUN pecl install ssh2

RUN docker-php-ext-enable ssh2

RUN docker-php-ext-configure sockets && docker-php-ext-install sockets

RUN pecl install memcached

RUN docker-php-ext-enable memcached

RUN docker-php-ext-configure intl && docker-php-ext-install intl

RUN docker-php-ext-enable intl

# SETUP jupyter notebook

# OLD
# RUN pip3 install jupyter Pillow requests numpy opencv-python matplotlib ipython db-sqlite3 playwright
# RUN playwright install
# RUN playwright install-deps
# RUN playwright install chromium

RUN apt-get install -y pipx \
        && pipx ensurepath

# OK
# RUN pipx install --include-deps jupyter 
# RUN pipx install --include-deps requests
# RUN pipx install --include-deps numpy
# RUN pipx install --include-deps opencv-python
# RUN pipx install --include-deps matplotlib
# RUN pipx install --include-deps ipython
# KO
# RUN pipx install --include-deps Pillow
# RUN pipx install --include-deps db-sqlite3
# RUN pipx install --include-deps playwright

RUN apt install -y python3-venv
RUN python3 -m venv /var/www/venv
RUN /var/www/venv/bin/pip install --upgrade pip
RUN /var/www/venv/bin/pip install --upgrade setuptools
RUN /var/www/venv/bin/pip install jupyter Pillow requests numpy opencv-python matplotlib ipython db-sqlite3 playwright


# playwright and ipython setup
RUN /var/www/venv/bin/playwright install

# AT THE END TO AVOID REBUILDING PREVIOUS STEPS
RUN apt install -y chromium

#RUN /var/www/venv/bin/playwright install-deps
RUN /var/www/venv/bin/playwright install chromium

# ipython will need $HOME env variable
# and playwright must be installed in $HOME/.cache
# move /root/.cache to /var/www/home/.cache
# change owner (recursive)


RUN mkdir /var/www/home \
        && mv /root/.cache /var/www/home/.cache \
        && chown -R www-data:www-data /var/www/home \
        && mkdir /var/code \
        && chown -R www-data:www-data /var/code

# PHP: add /var/code as volume for faster file access
# TODO: copy PHP source code in /var/code

RUN apt-get clean && apt-get autoclean

# FIXME: INSTALL CRON FOR USER root
# MUST BE ACTIAVTED IN ENTRYPOINT
# service cron start
# COPY crontab-root-gaia /root/crontab-root-gaia
# RUN service cron start
# RUN cat /root/crontab-root-gaia | crontab -
