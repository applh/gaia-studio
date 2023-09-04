<?php

/**
 * xpa_chromium
 * 
 * created: 2023-09-04 20:55:30
 * author: applh/gaia
 * license: MIT
 */

use HeadlessChromium\BrowserFactory;
use HeadlessChromium\Page;

/**
 * xpa_chromium
 */
class xpa_chromium
{
    //#class_start

    static function page($options = [])
    {
        // https://github.com/chrome-php/chrome#available-options
        $options ??= [];
        extract($options);
        $urls ??= [];
        $now ??= date("ymd-His");
        $selector ??= "li";
        $timeout ??= 120000;
        $max_try ??= 10;
        // FIXME: 1600x1600 is too big ?!
        $width ??= 1200;
        $height ??= 1200;

        try {
            // create browser factory
            $browserFactory = new BrowserFactory('chromium');

            // starts headless chrome
            // https://github.com/chrome-php/chrome#available-options
            $browser = $browserFactory->createBrowser([
                // "keepAlive" => true,
                "debugLogger" => "php://stdout",
                "noSandbox" => true,    // WARNING: required if running as root
                "windowSize" => [ $width, $height ],
                "enableImages" => false,
                // "userAgent" => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36",
                "customFlags" => [
                    "--disable-gpu", // WARNING: required if running as server command
                    "--disable-dev-shm-usage",
                    "--disable-setuid-sandbox",
                    "--no-first-run",
                    "--no-sandbox",
                    "--no-zygote",
                    "--single-process",
                ],
            ]);

            // creates a new page and navigate to an url
            $page = $browser->createPage();

            // loop on urls
            $res = [];
            foreach ($urls as $url) {
                // navigate to an url
                $page
                    ->navigate($url)
                    ->waitForNavigation(
                        // Page::NETWORK_IDLE, 
                        Page::DOM_CONTENT_LOADED,
                        // Page::LOAD,
                        $timeout,
                    );

                // get page title
                // $title = $page->evaluate('document.title')->getReturnValue();

                $js_code_1 = <<<JS

    function gaia_scrap ()
    {
        let res = [];
        document
            .querySelectorAll('$selector')
            .forEach(e => (e.innerText.length > 0) ? res.push(e.innerHTML) : null);
        return res;
    }
JS;

                $js_code_2 = "gaia_scrap()";

                $page->addScriptTag([
                    "content" => $js_code_1,
                ])->waitForResponse($timeout);

                $url_res = $page->evaluate($js_code_2)->getReturnValue($timeout);
                // var_dump($res);

                // loop and sleep 1s
                for ($i = 0; $i < $max_try; $i++) {
                    sleep(1);
                    // echo "loop $i\n";
                    $url_res = $page->evaluate($js_code_2)->getReturnValue($timeout);
                    // if the result is not empty
                    if (!empty($url_res)) {
                        // var_dump($res);
                        // break the loop
                        break;
                    }
                }

                // append url_res to res
                $res = array_merge($res, $url_res);
            }

            $outdir ??= __DIR__ . '/assets/my-tmp/';
            if (!file_exists($outdir)) {
                mkdir($outdir, 0777, true);
            }
            // save list as html file
            $items = "";
            foreach ($res as $item) {
                $items .= "<li>$item</li>";
            }
            $html = "<html><body><ol>$items</ol></body></html>";
            file_put_contents("$outdir/example-$now.html", $html);

            if ($options['screenshot'] ?? false) {
                // screenshot
                $screenshot = $page->screenshot([
                    // 'captureBeyondViewport' => true,
                    // 'clip' => $page->getFullPageClip(),
                ]);
                echo "(Saving screenshot)\n";
                $screenshot->saveToFile("$outdir/example.png", 120000);

                echo <<<HTML
        <img src="/assets/my-tmp/example.png" alt="my-example.png">
        HTML;
            }

            // close the browser
            $browser->close();

            // echo $html;
        } catch (Exception $e) {
            $error = "Exception: " . $e->getMessage() . "\n";
        }

        return [
            "items" => $items ?? "",
            "res" => $res,
            "urls" => $urls ?? [],
            "error" => $error ?? "",
        ];
    }

    //#class_end
}

//#file_end