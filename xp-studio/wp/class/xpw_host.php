<?php
/**
 * xpw_host
 * 
 * created: 2023-09-15 12:23:19
 * author: applh/gaia
 * license: MIT
 */

/**
 * xpw_host
 */
class xpw_host
{
    //#class_start

    static function test ()
    {
        // xpa_dev::code("xpw_webpage", __DIR__);

        // debug memory_usage
        $memory_usage = memory_get_usage();
        $memory_usage = ceil(0.001 * $memory_usage);
        $server_name = $_SERVER["SERVER_NAME"];
        $uri = $_SERVER["REQUEST_URI"];

        header("X-Memory-Usage: $memory_usage Ko");
        xpa_response::$content = "server_name: $server_name ($uri) \n";
        xpa_response::send();
    }

    //#class_end
}

//#file_end