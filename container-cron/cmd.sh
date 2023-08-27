date > /app/log/cron.log

crond

# tail -f /app/log/cron.log
tail /app/log/cron.log

# get env php_server
php_server=${PHP_SERVER:-}

# if php_server if empty, then run php server
if [ -z "$php_server" ]; then
    # get env CONTAINER_NAME or default to localhost
    container_name=${CONTAINER_NAME:-localhost}
    container_port=${CONTAINER_PORT:-4321}
    php_root=${PHP_ROOT:-/app/php-router}
    php_server="php -S ${container_name}:${container_port} -t ${php_root} ${php_root}/index.php"
fi

echo "php server: $php_server"
# run php server
$php_server