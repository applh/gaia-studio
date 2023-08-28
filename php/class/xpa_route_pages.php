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

    static function index ()
    {
        $found = false;

        $path_root = xpa_os::kv("root");
        $path_data = xpa_os::kv("path_data");

        $filename = xpa_os::kv("filename") ?? "";
        if (!$found) {
            // look for pages in my-data/site-HOSTNAME
            $path_data_host = xpa_os::kv("path_data_host");
            $path_pages = "$path_data_host/pages";
            $path_page = "$path_pages/$filename/index.php";
            // check if page exists
            if (file_exists($path_page)) {
                $found = true;
                // include page
                include $path_page;
            }
            else {
                // error_log("page not found: $path_page");
            }
        }

        if (!$found) {
            // look for common pages in my-data
            $path_pages = "$path_data/pages";
            $path_page = "$path_pages/$filename/index.php";
            // check if page exists
            if (file_exists($path_page)) {
                $found = true;
                // include page
                include $path_page;
            }
            else {
                // error_log("page not found: $path_page");
            }
        }

        if (!$found) {
            // auto look for templates
            $template = $filename;
            $path_template = "$path_root/templates/$template.php";
            if (!file_exists($path_template)) {
                $template = "404";
                $path_template = "$path_root/templates/$template.php";
            }

            include $path_template;    

        }    }

    //#class_end
}

//#file_end