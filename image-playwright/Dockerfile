FROM node:latest AS node-playwright

RUN apt-get update && \
    apt-get install -y x11-apps xemacs21 feh

#RUN npm ci && \
RUN npm i -g @playwright/test@latest

## install dependencies
RUN npx playwright install && \
    npx playwright install-deps

RUN npm i -g @slidev/cli

# note: 
# browsers folders: .cache/ms-playwright/...
# with X11: chrome is KO, firefox is OK

# CREATE dir /var/app
# CHMOD 777 /var/app
RUN mkdir -p /var/app/logs && \
    mkdir -p /var/app_docker && \
    chmod -R 777 /var/app && \
    touch /var/app/logs/debug.log

# Set root password
RUN echo 'root:mysecretpassword' | chpasswd

# CREATE user gaia
# CHOWN gaia:gaia /var/app
RUN useradd -ms /bin/bash gaia && \
    chown -R gaia:gaia /var/app

RUN  apt-get install -y vim cron htop tree

# not working
# RUN service cron start

# USER gaia

WORKDIR /var/app

# COPY LOCAL FILE TO CONTAINER
COPY ./app_cron /etc/cron.d/app_cron

COPY ./app_cron.sh /var/app_docker/app_cron.sh
COPY ./docker_cmd.sh /var/app_docker/docker_cmd.sh
RUN chmod +x /var/app_docker/*.sh

CMD ["/var/app_docker/docker_cmd.sh"]

# CMD tail -f /dev/null
# CMD tail -f /var/app/logs/debug.log


FROM node-playwright AS node-playwright-jupyter

RUN apt-get install -y pipx python3-pip \
        && pipx ensurepath

RUN apt install -y python3-venv

RUN mkdir -p /var/www/venv && \
    python3 -m venv /var/www/venv

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

