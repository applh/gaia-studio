<?php
$now = date('Y-m-d H:i:s');

$template = "index";

// router logic
$uri = $_SERVER['REQUEST_URI'] ?? '/index.php';
extract(parse_url($uri));
$path ??= '/index.php';
extract(pathinfo($path));
$filename ??= 'index';
$extension ??= 'php';


// autoloader
spl_autoload_register(function ($class_name) {
    $class_name = str_replace("\\", "/", $class_name);
    include __DIR__ . "/class/$class_name.php";
});

if ($filename == "api") {
    // template is api
    $template = "api";
}
if ($filename == "adminer") {
    // template is api
    $template = "adminer";
}
include "templates/$template.php";
