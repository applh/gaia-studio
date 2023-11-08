<?php
$now = date('Y-m-d H:i:s');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UIKIT</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.17.8/dist/css/uikit.min.css" />
    <style>
        header, footer {
            text-align: center;
        }
        /* .s2 even  bacground color gray */
        .s2:nth-child(even) {
            background-color: #ccc;
        }
        h1, h2, h3 {
            text-align: center;
        }
        .s2 img {
            width: 100%;
        }
        main p {
            max-width: 800px;
            display: block;
            margin: auto;
            text-align: justify;
            padding: 1rem;
        }
    </style>
    <!-- UIkit JS -->
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.17.8/dist/js/uikit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.17.8/dist/js/uikit-icons.min.js"></script>

</head>

<body>
    <header class="uk-section">
        <div class="uk-container">
            <p><?php echo $now ?></p>
        </div>
    </header>
    <main class="uk-container-expand">
        <div class="uk-container">
            <h1>UIKIT</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum quo sed, unde voluptatem commodi rerum harum quidem atque. Et consequuntur tempore similique dolor ratione explicabo necessitatibus nihil asperiores dolores suscipit?</p>
        </div>
        <div data-src="/assets/photo.jpg" sizes="" uk-img class="uk-height-medium"></div>
        <section class="s2 uk-section">
            <h2>title2</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum quo sed, unde voluptatem commodi rerum harum quidem atque. Et consequuntur tempore similique dolor ratione explicabo necessitatibus nihil asperiores dolores suscipit?</p>
            <div data-src="/assets/photo.jpg" sizes="" uk-img class="uk-height-medium"></div>
            <div class="uk-grid uk-child-width-1-2@s uk-child-width-1-3@m">
                <section class="uk-section">
                    <h3>title3</h3>
                    <img src="/assets/photo.jpg" alt="">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum quo sed, unde voluptatem commodi rerum harum quidem atque. Et consequuntur tempore similique dolor ratione explicabo necessitatibus nihil asperiores dolores suscipit?</p>
                </section>
                <section class="uk-section">
                    <h3>title3</h3>
                    <img src="/assets/photo.jpg" alt="">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum quo sed, unde voluptatem commodi rerum harum quidem atque. Et consequuntur tempore similique dolor ratione explicabo necessitatibus nihil asperiores dolores suscipit?</p>
                </section>
                <section class="uk-section">
                    <h3>title3</h3>
                    <img src="/assets/photo.jpg" alt="">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum quo sed, unde voluptatem commodi rerum harum quidem atque. Et consequuntur tempore similique dolor ratione explicabo necessitatibus nihil asperiores dolores suscipit?</p>
                </section>
            </div>
        </section>
        <section class="s2 uk-section">
            <h2>title2</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum quo sed, unde voluptatem commodi rerum harum quidem atque. Et consequuntur tempore similique dolor ratione explicabo necessitatibus nihil asperiores dolores suscipit?</p>
            <img src="/assets/photo.jpg" alt="">
            <div class="uk-grid uk-child-width-1-2@s uk-child-width-1-3@m">
                <section class="uk-section">
                    <h3>title3</h3>
                    <img src="/assets/photo.jpg" alt="">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum quo sed, unde voluptatem commodi rerum harum quidem atque. Et consequuntur tempore similique dolor ratione explicabo necessitatibus nihil asperiores dolores suscipit?</p>
                </section>
                <section class="uk-section">
                    <h3>title3</h3>
                    <img src="/assets/photo.jpg" alt="">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum quo sed, unde voluptatem commodi rerum harum quidem atque. Et consequuntur tempore similique dolor ratione explicabo necessitatibus nihil asperiores dolores suscipit?</p>
                </section>
                <section class="uk-section">
                    <h3>title3</h3>
                    <img src="/assets/photo.jpg" alt="">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum quo sed, unde voluptatem commodi rerum harum quidem atque. Et consequuntur tempore similique dolor ratione explicabo necessitatibus nihil asperiores dolores suscipit?</p>
                </section>
            </div>
        </section>
        <section class="s2 uk-section">
            <h2>title2</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum quo sed, unde voluptatem commodi rerum harum quidem atque. Et consequuntur tempore similique dolor ratione explicabo necessitatibus nihil asperiores dolores suscipit?</p>
            <img src="/assets/photo.jpg" alt="">
            <div class="uk-grid uk-child-width-1-2@s uk-child-width-1-3@m">
                <section class="uk-section">
                    <h3>title3</h3>
                    <img src="/assets/photo.jpg" alt="">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum quo sed, unde voluptatem commodi rerum harum quidem atque. Et consequuntur tempore similique dolor ratione explicabo necessitatibus nihil asperiores dolores suscipit?</p>
                </section>
                <section class="uk-section">
                    <h3>title3</h3>
                    <img src="/assets/photo.jpg" alt="">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum quo sed, unde voluptatem commodi rerum harum quidem atque. Et consequuntur tempore similique dolor ratione explicabo necessitatibus nihil asperiores dolores suscipit?</p>
                </section>
                <section class="uk-section">
                    <h3>title3</h3>
                    <img src="/assets/photo.jpg" alt="">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum quo sed, unde voluptatem commodi rerum harum quidem atque. Et consequuntur tempore similique dolor ratione explicabo necessitatibus nihil asperiores dolores suscipit?</p>
                </section>
        </section>
    </main>
    <footer class="uk-section">
        <div class="uk-container">
            <p>FOOTER</p>
            <div class="info"></div>
        </div>
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