<?php


class cron_job
{
    static function run()
    {
        // load gaia starter
        include __DIR__ . "/../php/index.php";
        // require __DIR__ . "/my-config/setup.php";
        xpa_cron::run_task(__FILE__);
    }

}

cron_job::run();
