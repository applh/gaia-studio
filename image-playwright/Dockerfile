FROM node:latest AS node-playwright

RUN apt-get update && \
    apt-get install -y x11-apps xemacs21 feh

RUN npm ci && \
    npm i -g @playwright/test@latest

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
    chmod -R 777 /var/app && \
    touch /var/app/logs/debug.log

# Set root password
RUN echo 'root:mysecretpassword' | chpasswd

# CREATE user gaia
# CHOWN gaia:gaia /var/app
RUN useradd -ms /bin/bash gaia && \
    chown -R gaia:gaia /var/app

USER gaia

WORKDIR /var/app

# CMD tail -f /dev/null
CMD tail -f /var/app/logs/debug.log


FROM ubuntu:latest AS ubuntu-playwright

# not working ?
# https://playwright.dev/docs/docker
# FROM mcr.microsoft.com/playwright:latest
# https://github.com/microsoft/playwright/blob/main/utils/docker/Dockerfile.jammy

# install node and npm
RUN apt-get update && \
    # Install Node 18
    apt-get install -y curl wget gpg && \
    curl -sL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    # Feature-parity with node.js base images.
    apt-get install -y --no-install-recommends git openssh-client && \
    npm install -g yarn && \
    # clean apt cache
    rm -rf /var/lib/apt/lists/* && \
    # Create the pwuser
    adduser pwuser

# === BAKE BROWSERS INTO IMAGE ===

ENV PLAYWRIGHT_BROWSERS_PATH=/ms-playwright

# 1. Add tip-of-tree Playwright package to install its browsers.
#    The package should be built beforehand from tip-of-tree Playwright.
# COPY ./playwright-core.tar.gz /tmp/playwright-core.tar.gz

# 2. Bake in browsers & deps.
#    Browsers will be downloaded in `/ms-playwright`.
#    Note: make sure to set 777 to the registry so that any user can access
#    registry.
# RUN mkdir /ms-playwright && \
#     mkdir /ms-playwright-agent && \
#     cd /ms-playwright-agent && npm init -y && \
#     npm i /tmp/playwright-core.tar.gz && \
#     npm exec --no -- playwright-core mark-docker-image "${DOCKER_IMAGE_NAME_TEMPLATE}" && \
#     npm exec --no -- playwright-core install --with-deps && rm -rf /var/lib/apt/lists/* && \
#     rm /tmp/playwright-core.tar.gz && \
#     rm -rf /ms-playwright-agent && \
#     rm -rf ~/.npm/ && \
#     chmod -R 777 /ms-playwright

# https://playwright.dev/docs/intro#installing-playwright

CMD tail -f /dev/null