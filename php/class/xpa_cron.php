<?php
/**
 * xpa_cron
 * 
 * created: 2023-09-05 12:23:33
 * author: applh/gaia
 * license: MIT
 */

/**
 * xpa_cron
 */
class xpa_cron
{
    //#class_start

    static function load ()
    {
        $params = [];
        $params["callback"] = "xpa_cron::test";
        return $params;
    }

    static function run_task ($file)
    {
        $script = basename($file, ".php");
        $now = date('Y-m-d H:i:s');
        error_log("cron_job::run($script) at $now");

        $time_start = microtime(true);

        extract(xpa_cron::load($file) ?? []);
        error_reporting(E_ERROR);
        if ($callback ?? false) {
            // check if is callable
            if (is_callable($callback)) {
                error_log("callback: $callback");
                $callback($options ?? []);
            }
        }

        $time_end = microtime(true);
        
    }

    static function test ()
    {
        $now = date('Y-m-d H:i:s');
        error_log("xpa_cron::test($now)");
    }

    //#class_end
}

//#file_end