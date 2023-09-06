# Gaia Studio

This Docker container is build to provide very useful features
* Heartbeat (with cron)
    * every minute (cron)
* Web APIs
    * curl requests
* Web Browser 
    * pdf generator (chromium)
    * screenshot generator (chromium)
* Databases
    * SQLite (local files)
    * SQL (remote SQL server by PHP PDO connections)


## ARCHITECTURE

Gaia Studio is built on 2 main technologies
* Docker Containers
* PHP

### Docker Containers

Docker containers are used to provide a very stable and portable environment.
* A docker container can work identically on Mac, Windows, Linux
* A docker container can start automatically at machine boot (daemon mode)
A docker container can be deployes on the cloud and work as saas (Software As A Service)

* Docker containers are built on top of Linux
* Docker containers are very light
* Docker containers are very fast to start
* Docker containers are very fast to stop
* Docker containers are very fast to restart
* Docker containers are very fast to update
* Docker containers are very fast to deploy
* Docker containers are very fast to scale
* Docker containers are very fast to backup
* Docker containers are very fast to restore
* Docker containers are very fast to migrate
* Docker containers are very fast to monitor
* Docker containers are very fast to debug
* Docker containers are very fast to secure
* Docker containers are very fast to share
* Docker containers are very fast to ...

### PHP

Inside the Docker container, the programming language is PHP.
In the context of building an AI, the choice of the programming language is important.
An AI is a program that can learn and adapt its code to the environment.
If you choose a language that needs a compilation step, then your AI can produce a new version of the code. 
But it will need a step to recompile the code to run it.
If you wan to remove this compilation step, then you need to choose a language that can be interpreted.
PHP is an interpreted language.
PHP is a very good choice for building an AI.
PHP is originally a template engine, so it can generate PHP code easilly.
And PHP can dynamically load PHP code, so it can adapt immediatly its code to the environment.

#### PHP on the web

Another good advantage of PHP is that it is used by +75% of the web sites.
So the PHP code produced by Gaia can be used by +75% of the web sites.
And WordPress is a CMS (Content Management System) that is built on top of PHP.
So Gaia code can also ne integrated into WP as a plugin.
WordPress is used by +40% of the web sites.

## CRON

* With Alpine linux, the cron daemon is available as part of busybox.

https://mixu.wtf/cron-in-docker-alpine-image/


### Activity by every minute

* Heartbeat
* The basic period for a cron task is 1 minute.
* A cron task can last more than 1 minute
 
In the context of an AI working as a human developer,
A human is expected to produce 1 line of code every minute.
It seems so easy for an AI to produce 100 lines of code every minute.
So a coding AI could be easily x100 more productive than a human developer ?!

## SQL

SQL databases are very popular and efficient to store data.
Docker allows to add a SQL MariaDB container very easily.
SQLite a also a very light SQL database that can be used as a local file.
SQLite has a web assembly version that can be run in the browser.
 
## NGINX + PHP-FPM

* AI must be able to instrospect itself
* As the docker container is a web server, 
  * then an incoming request should be able to re-launch some other recursive requests
  * problem: if using the PHP built-in web-server, 
  * each request is blocking the others until finished
  * => impossible to have recursive requests


## Chromium Headless

* https://developer.chrome.com/articles/new-headless/

* FIXME:

* Solved
  * chromium headless doesn't load ttf fonts ?!
    * add Alpine several font packages (ttf, emoji, ...)  

```
chromium --headless --print-to-pdf https://applh.com

chrome --headless=new --print-to-pdf --no-pdf-header-footer https://developer.chrome.com/

* delay before print or screenshot
chrome --headless=new --print-to-pdf --timeout=5000 https://mathiasbynens.be/demo/time

chrome --headless=new --screenshot --window-size=412,892 https://developer.chrome.com/


* root on alpine
chromium --no-sandbox --headless --print-to-pdf=php/output.pdf https://applh.com 

chromium --no-sandbox --headless --print-to-pdf=./output2.pdf https://applh.com 


chromium --no-sandbox --headless --disable-gpu --print-to-pdf=./output2.pdf https://applh.com 

# remove headers
chromium --no-sandbox --headless --disable-gpu --print-to-pdf --no-pdf-header --no-pdf-footer ./output2.pdf https://applh.com

```


### PHP / chrome-php

https://github.com/chrome-php/chrome#evaluate-script-on-the-page

### Playwright Python / NodeJS

WARNING: Playwright is not working on Alpine Linux
* Browsers build are available only on Debian and Ubuntu

* Docker
* https://playwright.dev/python/docs/docker
* https://github.com/microsoft/playwright-python/blob/main/utils/docker/Dockerfile.jammy


### TUTORIALS

#### PHP

* filesystem
  * a file can store content
  * a folder can store a file or a sub-folder

* PHP
  * a variable can store value
  * an associative array can store a key + value
    * another array can be stored as value

#### VUE: ELEMENT PLUS

https://element-plus.org/en-US/guide/installation.html#import-in-browser

* icons
* https://element-plus.org/en-US/component/icon.html#icon-collection


## WORDPRESS PLUGIN

* GAIA Studio can also be used as a WordPress plugin
  * The php folder can be used as a WordPress plugin

https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/

```
* usage:
* open terminal in php folder

wp-env start

wp-env stop

wp-env destroy

```