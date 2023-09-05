<?php


class cron_job
{
    static function run()
    {
        $script = basename(__FILE__, ".php");
        $now = date('Y-m-d H:i:s');
        error_log("cron_job::run($script) at $now");

        // load gaia starter
        include __DIR__ . "/../php/index.php";
        require __DIR__ . "/my-config/setup.php";
        $time_start = microtime(true);
        static::run_task();
        $time_end = microtime(true);
    }

    static function run_task ()
    {
        extract(setup::load(__FILE__));
        error_reporting(E_ERROR);
        
        xpa_curl::scrap_html($options ?? []);
        
    }
}

cron_job::run();
