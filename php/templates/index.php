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
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #ccc;
            min-height: 100vw;
            font-size:2vw;
        }
        * {
            box-sizing: border-box;
        }
        main {
            /* border: 1px solid #000; */
        }
        footer {
            padding: 2rem;
        }
        h1 {
            margin: 0;
            padding: 2rem;
            color: red;
            text-align: center;
            font-size: 2rem;
        }
        img {
            width: 100%;
            height: auto;
        }
        .info {
            text-align: center;
        }

        /* no margin or padding when printing */
        @media print {
            @page {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <header></header>
    <main>
        <h1>Hello <?php echo $now; ?></h1>
        <img src="/assets/photo.jpg" alt="photo">
    </main>
    <footer>
        <div class="info"></div>
    </footer>
    <script type="module">
        let ww = window.innerWidth;
        let wh = window.innerHeight;
        let act_resize = () => {
            ww = window.innerWidth;
            wh = window.innerHeight;
            console.log(ww, wh);
            let info = document.querySelector(".info");
            info.innerHTML = `${ww} x ${wh}`;

        }
        act_resize();
        window.addEventListener("resize", act_resize);
    </script>
</body>

</html>
