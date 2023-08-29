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

$cmd = "chromium --no-sandbox --headless --disable-gpu --screenshot=$outdir/page-$now.$format --window-size=$ww,$wh $url";
echo "Running command: $cmd\n";
passthru($cmd);

