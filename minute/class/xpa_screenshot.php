<?php

class xpa_screenshot 
{
    static function save ($url, $prefix="page", $options=[])
    {
        // warning will create local variables
        extract($options);

        // FIXME: should be in config
        $curdir = __DIR__;
        $outdir = "$curdir/../../my-data/cron/img";
        $outdir2 = realpath($outdir);
        if (!file_exists($outdir2)) {
            echo "Creating dir: $outdir\n";
            mkdir($outdir);
            $outdir = realpath($outdir);
        }
        else {
            $outdir = $outdir2;
        }
        $now = date("ymd-His");
        
        // $ww = 800;
        // $wh = 800;
        $ww ??= 1600;
        $wh ??= 1600;
        // WARNING: timeout is in milliseconds
        $timeout ??= 10000; 
        
        // webp can be 10x smaller than png
        // $format = "png";
        $format = "webp";
        $options = [
            "--no-sandbox",
            "--headless",
            "--disable-gpu",
            "--screenshot=$outdir/$prefix-$now.$format",
            "--window-size=$ww,$wh",
            "--timeout=$timeout",
        ];
        $cmd = "chromium " . implode(" ", $options) . " $url";
        // $cmd = "chromium --no-sandbox --headless --disable-gpu --screenshot=$outdir/$prefix-$now.$format --window-size=$ww,$wh --timeout=$timeout $url";
        echo "Running command: $cmd\n";
        passthru($cmd);
        
    }

    static function pdf1 ($options=[])
    {
        // select random max_urls in $urls

        $urls = $options["urls"] ?? [];
        $max_urls = $options["max_urls"] ?? 1;

        $keys = array_keys($urls);
        shuffle($keys);
        $keys = array_slice($keys, 0, $max_urls);
        foreach ($keys as $name) {
            $url = $urls[$name];
            static::pdf($url, $name, $options);
        }
    }

    static function pdf ($url, $prefix="page", $options=[])
    {
        // warning will create local variables
        extract($options);

        // FIXME: should be in config
        $curdir = __DIR__;
        $outdir = "$curdir/../../my-data/cron/pdf";
        $outdir2 = realpath($outdir);
        if (!file_exists($outdir2)) {
            echo "Creating dir: $outdir\n";
            mkdir($outdir);
            $outdir = realpath($outdir);
        }
        else {
            $outdir = $outdir2;
        }
        $now = date("ymd-His");
        
        // $ww = 800;
        // $wh = 800;
        $ww ??= 1600;
        $wh ??= 1600;
        // WARNING: timeout is in milliseconds
        $timeout ??= 10000; 
        
        // webp can be 10x smaller than png
        // $format = "png";
        $format = "webp";
        $options = [
            "--no-sandbox",
            "--headless",
            "--disable-gpu",
            "--print-to-pdf=$outdir/$prefix-$now.pdf",
            "--no-pdf-header-footer",
            "--window-size=$ww,$wh",
            "--timeout=$timeout",
                ];
        $cmd = "chromium " . implode(" ", $options) . " $url";
        // $cmd = "chromium --no-sandbox --headless --disable-gpu --screenshot=$outdir/$prefix-$now.$format --window-size=$ww,$wh --timeout=$timeout $url";
        echo "Running command: $cmd\n";
        passthru($cmd);
        
    }

    static function chromium ()
    {
        
    }
}
