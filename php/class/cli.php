<?php

class cli 
{
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
}