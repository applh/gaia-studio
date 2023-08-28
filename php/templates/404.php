<?php

$root = xpa_os::kv("root");
$path = xpa_os::kv("path");
$uri = xpa_os::kv("uri");
$filename = xpa_os::kv("filename");
$extension = xpa_os::kv("extension");

echo "ERROR 404 ($uri) ($path) ($filename) ($extension)";