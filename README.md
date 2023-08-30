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

## CRON

* With Alpine linux, the cron daemon is available as part of busybox.

https://mixu.wtf/cron-in-docker-alpine-image/

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

### Playwright Python

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