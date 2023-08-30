<?php
/**
 * xpa_route_assets
 * 
 * created: 2023-08-28 00:07:57
 * author: applh/gaia
 * license: MIT
 */

/**
 * xpa_route_assets
 */
class xpa_route_assets
{
    //#class_start

    static function index ()
    {
        $path_root = xpa_os::kv("root") ?? __DIR__ . "/..";
        $dirs = xpa_os::kv("dirs") ?? [];
        $filename = xpa_os::kv("filename") ?? "";
        $extension = xpa_os::kv("extension") ?? "";

        $searchs = [
            "$path_root/../my-data/assets",
            "$path_root/templates/assets",
        ];
        $dir1 = $dirs[1] ?? "";
        // error_log("dir1: $dir1");
        if ($dir1) {
            $searchs[] = "$path_root/../my-data/assets/$dir1";
            $searchs[] = "$path_root/templates/assets/$dir1";
        }

        // search for file
        $file = "";
        foreach ($searchs as $search) {
            $search = "$search/$filename.$extension";
            if (file_exists($search)) {
                $file = $search;
                // error_log("file found: $file");
                break;
            }
        }
        // error_log($file);
        if ($file && file_exists($file)) {
            $mimes = [
                "css" => "text/css",
                "js" => "text/javascript",
                "mjs" => "text/javascript",
                "jpg" => "image/jpeg",
                "png" => "image/png",
                "gif" => "image/gif",
                "svg" => "image/svg+xml",
                "ico" => "image/x-icon",
                "json" => "application/json",
                "pdf" => "application/pdf",
                "zip" => "application/zip",
                "mp3" => "audio/mpeg",
                "mp4" => "video/mp4",
                "ttf" => "font/ttf",
            ];
            // alpine error ?!
            // Call to undefined function mime_content_type()
            // $mime = $mimes[$extension] ?? mime_content_type($file);
            $mime = $mimes[$extension] ?? "application/octet-stream";
            header("Content-Type: $mime");
            readfile($file);
        }

    }
    //#class_end
}

//#file_end