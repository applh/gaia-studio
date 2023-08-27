<?php

class index
{
    static function web ()
    {
        // autoloader        
        spl_autoload_register("index::autoload");

        // run
        index::run();
    }

    static function autoload ($class_name)
    {
        $class_name = str_replace("\\", "/", $class_name);
        include __DIR__ . "/class/$class_name.php";
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
        $file = __DIR__ . "/../my-data/assets/$filename.$extension";
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
