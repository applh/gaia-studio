<?php
/**
 * xpa_os
 * 
 * created: 2023-09-10 08:02:05
 * author: applh/gaia
 * license: MIT
 */

/**
 * xpw_hook
 */
class xpw_hook
{
    //#class_start

    static function template_redirect()
    {
        // check if is 404
        if (is_404()) {
            // MAKE A SIMPLE BRIDGE TO GAIA
            // load gaia unique entry point
            // cool: gaia will handle all requests with a single entry point
            ob_start();
            include xp_studio::$path_studio . "/php/index.php";

            $response = ob_get_clean();
            // FIXME: should test if is 404 again
            // check status
            if (xpa_response::$status == 200) {
                status_header(200);
                // content-type is set by gaia
                echo $response;
                die();
            }
        }
    }

    static function rest_api_init ()
    {
        // register a rest api entry point
        // will be callable at /wp-json/xp-studio/v1/api
        register_rest_route('xp-studio/v1', '/api', [
            'methods' => ['GET', 'POST'],
            'callback' => 'xpw_hook::rest_api_test',
            "permission_callback" => "__return_true",
        ]);
    }

    static function rest_api_test (WP_REST_Request $request)
    {
        // return a json response
        return new WP_REST_Response([
            "status" => "ok",
            "date" => date("Y-m-d H:i:s"),
            "message" => "hello world",
            "request" => $request->get_params(),
            "files" => $_FILES,
        ]);
    }
    //#class_end
}

//#file_end