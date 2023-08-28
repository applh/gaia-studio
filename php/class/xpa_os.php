<?php
/**
 * xpa_os
 * 
 * created: 2023-08-28 08:02:05
 * author: applh/gaia
 * license: MIT
 */

/**
 * xpa_os
 */
class xpa_os
{
    //#class_start

    static $store = [];

    static function kv ($key, $value=null)
    {
        // get the value
        $res = static::$store[$key] ?? null;
        // set the value
        if (func_num_args() > 1) {
            static::$store[$key] = $value;
        }
        return $res;
    }
    //#class_end
}

//#file_end