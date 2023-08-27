<?php

class response 
{
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
}