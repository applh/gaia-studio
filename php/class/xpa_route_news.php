<?php
/**
 * xpa_route_news
 * 
 * created: 2023-09-01 14:17:55
 * author: applh/gaia
 * license: MIT
 */

/**
 * xpa_route_news
 */
class xpa_route_news
{
    //#class_start

    static function index ()
    {
        error_log("xpa_route_news::index");
        
        $context = "post";
        $found = false;
        $searchs = [
            "xpa_response::show_content",
            "xpa_route_pages::show_page",
            "xpa_route_pages::show_template",
        ];
        foreach ($searchs as $search) {
            if (!$found && is_callable($search)) {
                $filename = xpa_os::kv("filename") ?? "";
                $found = $search($filename, [
                    "context" => $context
                ]);
            }
        }

    }

    //#class_end
}

//#file_end