<?php

$name = "pw";
$timestamp = time();
$now0 = date("Ymd-His", $timestamp);
$path_ipython = "./my-output";

$command = "HOME=/var/www/home /var/www/venv/bin/jupyter nbconvert --to notebook -y --allow-chromium-download --log-level 50 --execute --output task-$name-$now0.ipynb --output-dir $path_ipython task-$name.ipynb";

echo $command;

$output = shell_exec($command);

echo $output;