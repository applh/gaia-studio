<?php

/**
 * xpa_route_pages
 * 
 * created: 2023-08-28 08:05:44
 * author: applh/gaia
 * license: MIT
 */

/**
 * xpa_route_pages
 */
class xpa_route_pages
{
    //#class_start

    static function index()
    {
        $found = false;
        $searchs = [
            "xpa_response::show_content",
            "xpa_route_pages::show_page",
            "xpa_route_pages::show_template",
        ];
        ob_start();
        foreach ($searchs as $search) {
            if (!$found && is_callable($search)) {
                $filename = xpa_os::kv("filename") ?? "";
                $found = $search($filename);
            }
        }
        $content = ob_get_clean();
        if (empty(xpa_response::$content)) {
            xpa_response::$content = $content;
        }
    }

    static function show_template ($filename)
    {
        $found = false;

        $path_root = xpa_os::kv("root");

        // auto look for templates
        $template = $filename;
        $path_template = "$path_root/templates/$template.php";
        if (!file_exists($path_template)) {
            $template = "404";
            $path_template = "$path_root/templates/$template.php";
        }

        include $path_template;

        return $found;

    }

    static function show_page ($filename)
    {
        $found = false;

        $path_data = xpa_os::kv("path_data");

        // look for common pages in my-data
        $path_pages = "$path_data/pages";
        $path_page = "$path_pages/$filename/index.php";
        // check if page exists
        if (file_exists($path_page)) {
            $found = true;
            // include page
            include $path_page;
        } else {
            // error_log("page not found: $path_page");
        }

        return $found;
    }

    //#class_end
}

//#file_end