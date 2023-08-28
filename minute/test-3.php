<?php

// $url = "https://applh.com";
// $url = "http://php-lh.test:3666/";
$url = "http://appcron:80";

$curdir = __DIR__;
$outdir = "$curdir/../my-data/pdf";
if (!file_exists($outdir)) {
    mkdir($outdir);
    $outdir = realpath($outdir);
}
echo "Current dir: $curdir\n";
passthru("whoami");
passthru("ls -l $curdir");
$now = date("ymd-His");
// $ww = 800;
// $wh = 800;
$ww = 1600;
$wh = 1600;
$timeout = 12000;
$options = [
    "--no-sandbox",
    "--headless",
    "--disable-gpu",
    "--print-to-pdf=$outdir/page-$now.pdf",
    "--no-pdf-header-footer",
    "--window-size=$ww,$wh",
    "--timeout=$timeout",
];
$cmd = "chromium " . implode(" ", $options) . " $url";
// $cmd = "chromium --no-sandbox --headless --disable-gpu --print-to-pdf=$outdir/page-$now.pdf --no-pdf-header-footer --window-size=$ww,$wh --timeout=$timeout $url";
echo "Running command: $cmd\n";
passthru($cmd);

