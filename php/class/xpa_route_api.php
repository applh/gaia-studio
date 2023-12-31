<?php
/**
 * xpa_route_api
 * 
 * created: 2023-09-01 14:04:29
 * author: applh/gaia
 * license: MIT
 */

/**
 * xpa_route_api
 */
class xpa_route_api
{
    //#class_start
    static $response = [];

    static function index ()
    {

        $now = date("Y-m-d H:i:s");
        static::$response["now"] = $now;

        $dirs = xpa_os::kv("dirs");
        $filename = xpa_os::kv("filename");

        static::$response["filename"] = $filename ?? "";
        static::$response["dirs"] = $dirs ?? [];
        $nb_dirs = count($dirs);
        if ($nb_dirs == 1) {
            // security
            // use filename as suffix to callable act_$filename
            $filename = str_replace("-", "_", $filename);
            // remove all non alpha numeric except _
            $filename = preg_replace("/[^a-zA-Z0-9_]/", "", $filename);

            $act = "xpa_route_api::act_$filename";
            // replace - by _
            static::$response["act"] = $act;
            if (is_callable($act)) {
                $act();
            }
        }

        static::$response["request"] = $_REQUEST;
        // static::$response["request_post"] = $_POST;
        static::$response["files"] = $_FILES;
        // $json = json_encode($response, JSON_PRETTY_PRINT);
        // echo $json;
        xpa_response::$content_type = "application/json";
        xpa_response::$content = json_encode(static::$response, JSON_PRETTY_PRINT);
    }

    static function act_hello ()
    {
        static::$response["hello"] = date("Y-m-d H:i:s");
    }

    static function act_db_read ()
    {
        static::$response["db_read"] = date("Y-m-d H:i:s");
    }

    static function act_chrome_ext ()
    {
        $url = $_REQUEST["url"] ?? "";
        $title = $_REQUEST["title"] ?? "";
        $title1 = $_REQUEST["title1"] ?? "";
        extract(parse_url($url));
        $host ??= "";

        // insert in table geocms
        $row = [];
        $row["path"] = "chrome-ext";
        $row["filename"] = basename(__FILE__);
        $row["code"] = json_encode($_REQUEST, JSON_PRETTY_PRINT);
        $row["url"] = $url;
        $row["title"] = $title;
        $row["cat"] = "chrome-ext";
        $row["tags"] = "chrome-ext";
        $row["created"] = date("Y-m-d H:i:s");

        xpa_model::insert("geocms", $row);

        static::$response["chrome-ext"] = $row;

    }

    static function act_jobs ()
    {
        // get the action requested
        $action = $_REQUEST["action"] ?? "read";
        $limit = intval($_REQUEST["limit"] ?? 1000);

        if ($action == "update") {
            // job is json encoded
            $job = $_REQUEST["job"] ?? "";
            // json_decode the job
            $job = json_decode($job, true);
            // error_log(print_r($job, true));
            // update the job
            $id = intval($job["id"] ?? 0);
            // list of cols to update
            $cols = [ "z", "content" ];
            $updates = [];
            foreach ($cols as $col) {
                // values can be false or 0, ...
                if (isset($job[$col])) {
                    $updates[$col] = $job[$col];
                }
            }
            xpa_model::update("geocms", $id, $updates);
        }

        // return the updated list
        $rows = xpa_model::read("job", 
            order_by: "ORDER BY z DESC, created DESC", 
            limit: $limit,
            where: "(z is null) OR (z > 0)",
        );
        static::$response["jobs"] = $rows;
    }

    //#class_end
}

//#file_end