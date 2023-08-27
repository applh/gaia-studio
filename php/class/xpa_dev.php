<?php

class xpa_dev
{
    static function code ($classname)
    {
        if (!class_exists($classname)) {
            // create a new class from callback
            $path_root = cli::kv("root");
            $path_class = $path_root . "/class";

            $src_code = static::code_class($classname);

            // create the file
            $file = "$path_class/$classname.php";
            file_put_contents($file, $src_code);

        }
    }

    static function code_class ($classname)
    {
        $res = "";
        // TODO: add security on $classname
        // remove non alpha numeric characters
        $classname = preg_replace("/[^a-zA-Z0-9-_]/", "", $classname);
        // trim
        $classname = trim($classname);
        if ($classname) {
            // get the code of the class
            $reflection = new ReflectionClass("xpa_empty");
            // get the source code
            $src_file = $reflection->getFileName();
            $src_code = file_get_contents($src_file);
            // replace the class name
            $src_code = str_replace("xpa_empty", $classname, $src_code);
            // replace __CREATED__, __AUTHOR__, __LICENSE__
            $src_code = str_replace("__CREATED__", date("Y-m-d H:i:s"), $src_code);
            $src_code = str_replace("__AUTHOR__", "applh/gaia", $src_code);
            $src_code = str_replace("__LICENSE__", "MIT", $src_code);

            $res = $src_code;
        }
        return $res;
    }
}