#!/bin/bash

# This script is used to run the docker image

# start cron
service cron start

# CMD tail -f /dev/null
tail -f /var/app/logs/debug.log
