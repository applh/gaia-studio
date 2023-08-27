<?php

// $url = "https://applh.com";
// $url = "http://php-lh.test:3666/";
$url = "http://appcron:80";

$curdir = __DIR__;
$outdir = "$curdir/my-out";
if (!file_exists($outdir)) {
    mkdir($outdir);
}
echo "Current dir: $curdir\n";
passthru("whoami");
passthru("ls -l $curdir");
$now = date("ymd-His");
$cmd = "chromium --no-sandbox --headless --disable-gpu --print-to-pdf=$outdir/page-$now.pdf --no-pdf-header-footer $url";
echo "Running command: $cmd\n";
passthru($cmd);

