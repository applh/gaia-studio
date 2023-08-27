<?php

class index
{
    static $path_data = __DIR__ . "/../my-data";
    static $hostname = "localhost";

    static function web ()
    {
        // autoloader        
        spl_autoload_register("index::autoload");

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
        cli::kv("root", __DIR__);
        cli::kv("path_data", realpath(static::$path_data));
        // get host
        $host = $_SERVER['HTTP_HOST'] ?? "localhost";
        // remove port if any
        $host = explode(":", $host)[0];
        error_log("host: $host");
        static::$hostname = $host;

        $path_data_host = static::$path_data . "/site-" . static::$hostname;
        $path_data_host = realpath($path_data_host);
        if ($path_data_host) {
            // check if config.php exists
            $path_config = $path_data_host . "/config.php";
            if (file_exists($path_config)) {
                // include config.php
                include $path_config;
            }
            else {
                error_log("config.php not found: $path_config");
            }
        }
        else {
            error_log("path_data_host not found: $path_data_host");
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
        
        // if path starts with /assets then serve file
        if (str_starts_with($path, "/assets")) {
            index::asset($filename, $extension);
        }
        else {
            $template = $filename;
            $path_template = __DIR__ . "/templates/$template.php";
            if (!file_exists($path_template)) {
                $template = "404";
                $path_template = __DIR__ . "/templates/$template.php";
            }

            include $path_template;    
        }
    }

    static function asset ($filename, $extension)
    {
        $searchs = [
            __DIR__ . "/../my-data/assets/$filename.$extension",
            __DIR__ . "/templates/assets/$filename.$extension",
        ];
        // search for file
        $file = "";
        foreach ($searchs as $search) {
            if (file_exists($search)) {
                $file = $search;
                break;
            }
        }
        // error_log($file);
        if (file_exists($file)) {
            $mimes = [
                "css" => "text/css",
                "js" => "text/javascript",
                "jpg" => "image/jpeg",
                "png" => "image/png",
                "gif" => "image/gif",
                "svg" => "image/svg+xml",
                "ico" => "image/x-icon",
                "json" => "application/json",
                "pdf" => "application/pdf",
                "zip" => "application/zip",
                "mp3" => "audio/mpeg",
                "mp4" => "video/mp4",
            ];
            $mime = $mimes[$extension] ?? mime_content_type($file);
            header("Content-Type: $mime");
            readfile($file);
        }

    }
}

index::web();
