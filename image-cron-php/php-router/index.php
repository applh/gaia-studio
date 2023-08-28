<?php

// debug
// error_log(print_r(getenv(), true));
// print_r(getenv());
// FIXME: env is empty when called by nginx -> php-fpm

$app_php = (getenv('APP_PHP'));
if (!empty($app_php)) {
    $path_app0 = __DIR__ . "/$app_php";
    // echo "APP_PHP search: $path_app0\n";
    
    $path_app = realpath($path_app0);
    // if path exists then include it
    if ($path_app) {
        include_once($path_app);
    }
    else {
        // echo "APP_PHP: $path_app0 not found\n";
    }
}
