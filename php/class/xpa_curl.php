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

    //#class_end
}

//#file_end