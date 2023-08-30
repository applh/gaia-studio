<?php

require __DIR__ . "/my-config/setup.php";

extract(setup::load(__FILE__));

$curdir = __DIR__;
$outdir = "$curdir/../my-data/cron/img";
if (!file_exists($outdir)) {
    mkdir($outdir);
    $outdir = realpath($outdir);
}
else {
    $outdir = realpath($outdir);
}

echo "Current dir: $curdir\n";
passthru("whoami");
passthru("ls -l $curdir");
$now = date("ymd-His");

// $ww = 800;
// $wh = 800;
$ww = 1600;
$wh = 3200;

// webp can be 10x smaller than png
// $format = "png";
$format = "webp";

$urls ??= [];
// select only one url
$url_max = 1;
// random shuffle (not working with associative array)
$keys = array_keys($urls);
shuffle($keys);
// slice $keys to $url_max
$keys = array_slice($keys, 0, $url_max);

// loop through urls
foreach ($keys as $name) {
    $url = $urls[$name];
    // take screenshot of the page
    $cmd = "chromium --no-sandbox --headless --disable-gpu --screenshot=$outdir/$name-$now.$format --window-size=$ww,$wh $url";
    echo "Running command: $cmd\n";
    passthru($cmd);
}

