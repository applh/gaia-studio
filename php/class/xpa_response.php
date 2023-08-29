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

    static $rows = [];
    
    static function send ()
    {
        // set header
        header("Content-Type: " . static::$content_type);
        // send content
        echo static::$content;
    }
    //#class_end
}

//#file_end