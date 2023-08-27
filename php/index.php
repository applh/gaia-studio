<?php
$now = date('Y-m-d H:i:s');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaia Studio</title>
    <style>
        html, body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #ccc;
        }
        * {
            box-sizing: border-box;
        }
        h1 {
            margin: 0;
            padding: 2rem;
            color: red;
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>Hello <?php echo $now; ?></h1>
</body>

</html>
<?php

// echo "<h1>Hello from PHP at $now</h1>";

// phpinfo();


// NOT WORKING: test email
//$res = mail("test@applh.com", "My Subject", "Line 1\nLine 2\nLine 3");
//var_dump($res);

// $curdir = __DIR__;
// echo "Current dir: $curdir\n";
// passthru("whoami");
// passthru("ls -l $curdir");
// $now = date("ymd-His");
// $cmd = "chromium --no-sandbox --headless --print-to-pdf=$curdir/my-$now.pdf https://applh.com";
// echo "Running command: $cmd\n";
// passthru($cmd);
