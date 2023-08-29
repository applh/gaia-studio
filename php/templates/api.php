<?php

$now = date('Y-m-d H:i:s');

$path = xpa_os::kv("path");
$filename = xpa_os::kv("filename");
$extension = xpa_os::kv("extension");

$response = [];

$response['now'] = $now;
// debug
$response["path"] = $path;
$response["filename"] = $filename;
$response["extension"] = $extension;

// request
$response['request'] = $_REQUEST;
// files
$response['files'] = $_FILES;


// sqlite request
$limit = intval($_REQUEST['limit'] ?? 100);
$offset = intval($_REQUEST['offset'] ?? 0);
$users = xpa_model::read("users", $limit, $offset);
$response["total"] = count($users);
$response['users'] = $users;


// header
header('Content-Type: application/json');
echo json_encode($response);