<?php

class starter 
{
    static function run ()
    {
        // get current dir
        $curdir = __DIR__;
        include "$curdir/my-data/config.php";

        $now = date("Y-m-d H:i:s");

        // exec shell whoami
        $shell = shell_exec('whoami');

        $url = config::get("url");
        // hash
        $url_hash = md5($url);
        $found = null;
        if ($url) {
            $html = file_get_contents($url);
            $search = config::get("search");
            if ($search) {
                $found = strpos($html, $search);
                if ($found !== false) {
                    $found = "found: $search";
                    $callback =  "gaia::update";
                } else {
                    $found = "not found: $search";
                }
            }
        }
        if ($callback ?? false) {
            if (is_callable($callback)) {
                $callback();
            } else {
                error_log("starter::run() callback not callable");
            }
        }
        // return json response
        $response = [];
        $response["now"] = $now;
        $response["shell"] = trim($shell);
        $response["url"] = $url;
        $response["url_hash"] = $url_hash;
        $response["found"] = $found ?? "";
        $response["callback"] = $callback ?? "";
        $response["config"] = config::$kv;
        header("Content-Type: application/json");
        echo json_encode($response, JSON_PRETTY_PRINT);
    }
}

starter::run();