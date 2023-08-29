<?php


class cron_job 
{
    static function run ()
    {
        $now = date('Y-m-d H:i:s');
        error_log("cron_job::run() at $now");

        require __DIR__ . "/my-config/setup.php";

        extract(setup::load(__FILE__));

        if ($url ?? false) {    
            // load gaia starter
            include __DIR__ . "/../php/index.php";

            $time_start = microtime(true);

            // too simple, some sites don't like it
            // $html = file_get_contents($url);

            // use curl
            $html = xpa_curl::request($url);

            // error_log("html: $html");

            $time_end = microtime(true);
            // get size of output
            $size = mb_strlen($html);

            // get value in ms
            $time_delta = intval(1000 * ($time_end - $time_start));

            // insert a line in db gaia.geocms
            $row = [
                "path" => __DIR__,
                "filename" => basename(__FILE__),
                "code" => $html,
                "url" => $url,
                "title" => "title ($now)",
                "content" => "content ($now)",
                "media" => "",
                "template" => "",
                "cat" => "cronjob",
                "tags" => "cronjob",
                "created" => $now,
                "updated" => $now,
                "hash" => "",
                "x" => $time_delta,
                "y" => $size,
                "z" => 0,
                "t" => 0,
            ];
    
            // error_log(print_r($row, true));
    
            xpa_model::insert("geocms", $row);
        }
        
    }
}

cron_job::run();