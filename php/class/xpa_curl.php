<?php

/**
 * xpa_curl
 * 
 * created: 2023-08-29 15:44:20
 * author: applh/gaia
 * license: MIT
 */

/**
 * xpa_curl
 */
class xpa_curl
{
    //#class_start

    static function request($url, $posts = [])
    {
        $res = "";

        error_log("xpa_curl::request($url)");

        // launch a curl request to url
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // set post data
        if (!empty($posts)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $posts);
        }

        // set timeout
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        // set user agent
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)");

        // set referer
        curl_setopt($ch, CURLOPT_REFERER, $url);

        // set cookie
        // curl_setopt($ch, CURLOPT_COOKIE, "name=gaia;");
        // curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
        // curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");

        // set header
        // curl_setopt($ch, CURLOPT_HTTPHEADER, [
        //     "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
        //     "Accept-Language: fr,fr-FR;q=0.8,en-US;q=0.5,en;q=0.3",
        //     "Accept-Encoding: gzip, deflate",
        //     "Connection: keep-alive",
        //     "Upgrade-Insecure-Requests: 1",
        // ]);

        // send request
        $res = curl_exec($ch);

        // close curl
        curl_close($ch);

        // check result if empty
        if (empty($res)) {
            error_log("xpa_curl::request() empty result");
        }

        return $res;
    }

    static function scrap_html($options = [])
    {
        extract($options);
        $url_host ??= "";
        $skip_get ??= true;

        // get the rows from db.scrap order by z desc
        $rows = xpa_model::read("scrap", order_by: "ORDER BY z desc", where: "z IS NULL");

        $hashs = [];
        $items = "";
        foreach ($rows as $row) {
            extract($row);
            // store scrap_id (as $id will be overwritten...)
            $scrap_id = $id;
            $nb_lis = 0;
            $nb_inserts = 0;

            $code ??= "";
            if ($code) {
                // complete code with html and body tags
                $code = "<html><body>$code</body></html>";
                // extract the urls and text from the content by using xpath
                $doc = new DOMDocument("1.0", "UTF-8");
                // charset must be utf-8
                $doc->loadHTML('<?xml encoding="UTF-8">' . $code);
                $xpath = new DOMXPath($doc);
                $lis = $xpath->query("//li");
                $nb_lis = $lis->length;

                // print the href and text values
                foreach ($lis as $li) {
                    //FIXME: will surely fail if second link is wrong
                    $link = $li->getElementsByTagName("a")[1];
                    // get innerHTML
                    $link_html = $doc->saveHTML($link);
                    // error_log($link_html);
                    $text = $link->nodeValue;
                    $href = $link->getAttribute("href");
                    // error_log("$text:$href");
                    if ($text && $href) {
                        // strip get parameters from href
                        if ($skip_get) {
                            $href = explode("?", $href)[0];
                        }
                        // complete href with url_host (as several href are relative)
                        $href = $url_host . $href;
                        $hash = md5($href);
                        // error_log("$hash:$href");
                        if (!isset($hashs[$hash])) {

                            $hashs[$hash] = $href;
                            $items .= "<li class=\"$hash\"><a href=\"$href\">$text</a></li>\n";

                            // check in db if hash exists
                            $rows = xpa_model::read("job", where: "hash = '$hash'");
                            // error_log(print_r($rows, true));
                            if (count($rows) == 0) {
                                // in li find tag with attribute datetime
                                $time = $li->getElementsByTagName("time")[0];
                                $datetime = $time->getAttribute("datetime");
                                // convert datetime to timestamp
                                $timestamp = strtotime($datetime);
                                $created = date("Y-m-d H:i:s", $timestamp);

                                // insert a line in db gaia.geocms
                                $row = [
                                    "path" => "job",
                                    "filename" => basename(__FILE__),
                                    "code" => $link_html,
                                    "url" => $href,
                                    "title" => $text,
                                    "content" => $text,
                                    "media" => "",
                                    "template" => "",
                                    "cat" => "job",
                                    "tags" => "",
                                    "created" => $created ?? $now,
                                    "hash" => $hash,
                                ];
                                // error_log(print_r($row, true));
                                xpa_model::insert("geocms", $row);
                                // count inserts
                                $nb_inserts++;
                            } else {
                                // delete the row
                                $row = $rows[0];
                                extract($row);
                                // xpa_model::delete("geocms", $id);
                            }
                        }
                    }
                }
            }

            // update row in geocms by $id  with z = $nb_lis
            xpa_model::update("geocms", $scrap_id, [ 
                "y" => $nb_inserts, 
                "z" => $nb_lis,
                "updated" => date("Y-m-d H:i:s"),
            ]);

        }

        return $items;
    }

    //#class_end
}

//#file_end