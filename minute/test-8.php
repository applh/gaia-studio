<?php


class cron_job
{
    static function run()
    {
        $now = date('Y-m-d H:i:s');
        error_log("cron_job::run() at $now");

        require __DIR__ . "/my-config/setup.php";

        extract(setup::load(__FILE__));

        $suffix = date("ymd_His");
        $classname ??= "ai_cron_$suffix";
        if ($classname ?? false) {
            // load gaia starter
            include __DIR__ . "/../php/index.php";

            $time_start = microtime(true);

            $code = xpa_dev::code_class($classname);

            $time_end = microtime(true);
            // get size of output
            $size = mb_strlen($code);
            // hash the output
            $hash = md5($code);

            // check if hash exists in db
            $row = xpa_model::read1("geocms", "hash", $hash);
            // error_log("rows: " . print_r($rows, true));
            if ($row ?? false) {
                error_log("hash exists: $hash");
            } else {
                // get value in ms
                $time_delta = intval(1000 * ($time_end - $time_start));

                // insert a line in db gaia.geocms
                $row = [
                    "path" => "class",
                    "filename" => $classname,
                    "code" => $code,
                    "url" => $classname,
                    "title" => "title ($now)",
                    "content" => "content ($now)",
                    "media" => "",
                    "template" => "",
                    "cat" => "cronjob",
                    "tags" => "cronjob",
                    "created" => $now,
                    "updated" => $now,
                    "hash" => $hash,
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
}

cron_job::run();
