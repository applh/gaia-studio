date > /app/log/cron.log

# start the cron service in background
crond

# tail -f /app/log/cron.log
tail /app/log/cron.log

# get env php_server
php_server=${PHP_SERVER:-}

# if php_server if empty, then run php server
if [ -z "$php_server" ]; then
    # get env CONTAINER_NAME or default to localhost
    # gateway.docker.internal
    # host.docker.internal
    container_name=${CONTAINER_NAME:-host.docker.internal}
    container_port=${CONTAINER_PORT:-80}
    php_root=${PHP_ROOT:-/app/php-router}
    php_server="php -S ${container_name}:${container_port} -t ${php_root} ${php_root}/index.php"
fi

which php-fpm82

php-fpm82 -t

# launch in daemon mode
# php-fpm82 -R -D 

# php_server="php-fpm82 -R -F 9000"

# OK
# php-fpm82 -R -F 

# launch PHP-FPM in daemon mode
php-fpm82 -R

echo "php server: $php_server"
# run php server
$php_server