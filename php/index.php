<?php

class index
{
    static $path_data = __DIR__ . "/../my-data";
    static $hostname = "localhost";

    static function web ()
    {
        // setup
        index::setup();

        // run
        index::run();
    }

    static function autoload ($class_name)
    {
        $class_name = str_replace("\\", "/", $class_name);
        // remove namespace
        $class_name = basename($class_name);
        // cehck if file exists
        $search_file = __DIR__ . "/class/$class_name.php";
        if (file_exists($search_file)) {
            include $search_file;
        }
    }

    static function setup ()
    {
        // autoloader        
        spl_autoload_register("index::autoload");
        // composer vendor
        $path_vendor = __DIR__ . "/vendor/autoload.php";
        if (file_exists($path_vendor)) {
            include $path_vendor;
        }

        xpa_os::kv("root", __DIR__);
        xpa_os::kv("path_data", realpath(static::$path_data));
        // get host
        $host = $_SERVER['HTTP_HOST'] ?? "localhost";
        // remove port if any
        $host = explode(":", $host)[0];
        // error_log("host: $host");
        static::$hostname = $host;

        $path_data_host = static::$path_data . "/site-" . static::$hostname;
        $path_data_host = realpath($path_data_host);
        if ($path_data_host) {
            // store the path data host
            xpa_os::kv("path_data_host", $path_data_host);

            // check if config.php exists
            $path_config = $path_data_host . "/config.php";
            if (file_exists($path_config)) {
                // include config.php
                include $path_config;
            }
            else {
                // error_log("config.php not found: $path_config");
            }
        }
        else {
            // error_log("path_data_host not found: $path_data_host");
        }
        

        // host can be the name used by the browser
        // host can be the name used by the server (example: cron job)

        // server name is docker container name
        // $server_name  = $_SERVER['SERVER_NAME'] ?? "localhost";
        // error_log("server_name: $server_name");
    }

    static function run ()
    {
        $now = date('Y-m-d H:i:s');

        $template = "index";
        
        // router logic
        $uri = $_SERVER['REQUEST_URI'] ?? '/index.php';
        extract(parse_url($uri));
        $path ??= '/index.php';
        extract(pathinfo($path));
        $filename ??= 'index';
        $filename = $filename ?: 'index';

        $extension ??= 'php';
        
        // dir parts
        $dirname2 = trim($dirname, "/");
        $dirs = [];
        $nb_dirs = 0;
        if ($dirname2) {
            $dirs = explode("/", $dirname2);
            $nb_dirs = count($dirs);    
        }
        // store dirs, filenae, extension
        xpa_os::kv("uri", $uri);
        xpa_os::kv("path", $path);
        xpa_os::kv("dirs", $dirs);
        xpa_os::kv("filename", $filename);
        xpa_os::kv("extension", $extension);

        // debug
        // error_log($dirname2);
        // error_log(print_r($dirs, true));

        if ($nb_dirs == 0) {
            xpa_route_pages::index();
        }
        else {
            // if path starts with /assets then serve file
            if ("assets" == ($dirs[0] ?? "")) {
                xpa_route_assets::index();
            }

        }
    }


}

index::web();
