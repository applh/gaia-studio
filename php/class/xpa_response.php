<?php
/**
 * xpa_response
 * 
 * created: 2023-08-29 23:03:40
 * author: applh/gaia
 * license: MIT
 */

/**
 * xpa_response
 */
class xpa_response
{
    //#class_start

    static $content_type = "text/html";
    static $content = "";
    static $readfile = "";
    static $rows = [];
    
    static function send ()
    {
        // set header
        header("Content-Type: " . static::$content_type);
        // FIXME: CORS 
        header("Access-Control-Allow-Origin: *");
        // header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        // header("Access-Control-Allow-Headers:  Authorization, X-Requested-With, Accept, Origin, Accept-Language, Last-Modified, Cache-Control, Pragma, If-Modified-Since, Access-Control-Allow-Origin");
        // header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Origin");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Origin, Access-Control-Allow-Credentials");

        if (static::$readfile) {
            // send file
            readfile(static::$readfile);
        }
        else {
            // send content
            echo static::$content;
        }
    }

    static function show_content($filename, $options=[])
    {
        $found = false;
        extract($options);
        $context ??= "pages";
        // look for pages in my-data/site-HOSTNAME
        $path_data_host = xpa_os::kv("path_data_host");
        $path_context = "$path_data_host/$context";
        $path_context = "$path_context/$filename/index.php";

        // error_log("path_context: $path_context");
        // check if page exists
        if (file_exists($path_context)) {
            $found = true;
            // include page
            include $path_context;
        } else {
            // error_log("page not found: $path_page");
        }
        return $found;
    }

    //#class_end
}

//#file_end