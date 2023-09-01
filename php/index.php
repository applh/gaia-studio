<?php

class index
{
    static $path_data = __DIR__ . "/../my-data";
    static $hostname = "localhost";

    static function starter ()
    {
        // setup
        index::setup();

        $app_mode = getenv("APP_MODE") ?: "web";
        if ($app_mode == "web") {
            // run
            index::web();
        }            
        else {
            // error_log("APP_MODE... $app_mode");
        }
    }

    static function web ()
    {
        // run
        index::run();
    }

    static function autoload ($class_name)
    {
        $found = false;
        $class_name = str_replace("\\", "/", $class_name);
        // remove namespace
        $class_name = basename($class_name);
        $loaders = [
            "index::autoload_file",
            "index::autoload_db",
        ];
        foreach ($loaders as $loader) {
            if (is_callable($loader)) {
                $found = $loader($class_name);
                if ($found) {
                    return $found;
                }
            }
        }

        return $found;
    }

    static function autoload_file ($class_name)
    {
        $found = false;
        // check if file exists
        $search_file = __DIR__ . "/class/$class_name.php";
        if (file_exists($search_file)) {
            include $search_file;
            $found = true;
        }
        return $found;
    }

    static function autoload_db ($class_name)
    {
        // check if row exists in db with filename = $class_name
        $found = false;
        $md5 = md5($class_name);
        // FIXME: use path_cache from config.php
        $path_cache = xpa_os::kv("path_cache") ?? "/app/tmp";
        $path_md5 = "$path_cache/class-$md5.php";
        // if cache file exists include it
        if (file_exists($path_md5)) {
            include $path_md5;
            $found = true;
        }

        // search in db
        // WARNING: this is only available after the db is setup
        // WARNING: don't forget SQL index on column filename
        if (!$found && is_writable($path_cache)) {
            $row = xpa_model::read1("code", "filename", $class_name);
            if ($row ?? false) {
                $code = $row["code"] ?? "";
                if ($code) {
                    // create the cache file
                    file_put_contents($path_md5, $code);
                    // include the cache file
                    include $path_md5;
                    $found = true;
                }                
            }
    
        }

        return $found;
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

        static::$path_data = realpath(static::$path_data);
        if (static::$path_data) {
            xpa_os::kv("root", __DIR__);
            // store the path data
            xpa_os::kv("path_data", static::$path_data);
            // get host
            $host = $_SERVER['HTTP_HOST'] ?? "localhost";
            // remove port if any
            $host = explode(":", $host)[0];
            // error_log("host: $host");
            static::$hostname = $host;

            $path_data_host = static::$path_data . "/site-" . static::$hostname;
            // error_log("path_data_host: $path_data_host");
            $path_data_host = realpath($path_data_host);
            // store the path data host
            xpa_os::kv("path_data_host", $path_data_host);

        }
        else {
            // error_log("path_data not found: " . static::$path_data);
        }

        if (static::$path_data) {
            $path_config = static::$path_data . "/config.php";
            if (file_exists($path_config)) {
                // include config.php
                include $path_config;
            }
            else {
                error_log("config.php not found: $path_config");
            }
        }

        if ($path_data_host ?? false) {
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
            // automatic routing if $dirs[0] is not empty
            $sub_router = $dirs[0] ?? "";
            $sub_router = str_replace("-", "_", $sub_router);
            $sub_router = "xpa_route_$sub_router";
            // error_log("sub_router: $sub_router");
            if (is_callable("$sub_router::index")) {
                $sub_router::index();
            }
            else {
                // error_log("sub_router not found: $sub_router");
            }

            xpa_response::send();
            
            // if path starts with /assets then serve file
            // if ("assets" == ($dirs[0] ?? "")) {
            //     xpa_route_assets::index();
            // }

        }
    }


}

index::starter();
