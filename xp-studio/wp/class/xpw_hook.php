<?php
/**
 * xpw_hook
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
            else {
                xpw_hook::run_code();
            }
        }
    }

    static function run_code ()
    {
        // WARNING: if $uri has no extension, WP will try to find a page
        // check if $uri is a xps-code name
        $uri = $_SERVER["REQUEST_URI"];
        header('X-Xps-Uri: ' . $uri);

        $path = parse_url($uri, PHP_URL_PATH) ?? "/";
        extract(pathinfo($path));
        $filename ??= "";
        if ($filename != "") {
            $filename = str_replace("/", "-", $filename);
            // replace - by _
            $filename = str_replace("-", "_", $filename);
            // lowercase
            $filename = strtolower($filename);
            header('X-Xps-Filename: ' . $filename);
            // get the post by path
            $post = get_page_by_path($filename, OBJECT, "xps-code");
            if ($post->ID ?? false) {
                status_header(200);
                // TODO: should add more security checks
                // hack: trigger cache creation and file loading
                $classname = trim($post->post_name);
                ob_start();
                // warning: hack may cause several autoload of the same file
                if (class_exists($classname)) {
                    // nothingto do
                }
                $response = ob_get_clean();
                echo $response;
            }    
            die();
        }

    }

    static function rest_api_init ()
    {
        // TODO: make dynamic uri for rest api
        xp_studio::$uri_rest_api = "/wp-json/xp-studio/v1/api";

        // register a rest api entry point
        // will be callable at /wp-json/xp-studio/v1/api
        // https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/#cookie-authentication
        // X-XP-Nonce header
        register_rest_route('xp-studio/v1', '/api', [
            'methods' => ['GET', 'POST'],
            'callback' => 'xpw_hook::rest_api_test',
            "permission_callback" => "xpw_hook::rest_api_permission",
        ]);
    }

    static function rest_api_permission ()
    {
        // check if current user is admin
        // $res = current_user_can('activate_plugins');
        // warning: public access
        // each api will have to check security permissions
        return true;
    }

    static function check_user ($request)
    {
        // get Authorization header
        $auth = $request->get_header("Authorization");
        // check if is basic
        if (substr($auth, 0, 6) == "Basic ") {
            // get user and password
            $user_pass = base64_decode(substr($auth, 6));
            // split user and password
            list($user, $pass) = explode(":", $user_pass);
            // trim
            $user = trim($user);
            $pass = trim($pass);

            // check if user and password are valid
            $user = wp_authenticate($user, $pass);
            if ($user->ID ?? false) {
                // set current user
                wp_set_current_user($user);
            }
        }
        return $auth; // wp_get_current_user();
    }

    static function rest_api_test (WP_REST_Request $request)
    {
        // check user
        $nonce = $request->get_header("X-WP-Nonce");
        $auth = xpw_hook::check_user($request);

        // get action
        $class = $request->get_param("@class") ?? "form";
        $method = $request->get_param("@method") ?? "";
        // TODO: ADD SECURITY CHECK
        // sanitize class and method
        
        $callback = "xpi_$class::$method";
        // check if is_callable
        if (is_callable($callback)) {
            // call the callback
            $res = $callback($request);
        }

        // return a json response
        return new WP_REST_Response([
            "status" => "ok",
            "nonce" => $nonce, // "X-XP-Nonce
            "auth" => $auth,
            "date" => date("Y-m-d H:i:s"),
            "user" => wp_get_current_user(),
            "{$class}_{$method}" => $res ?? null,
            "request" => $request->get_params(),
            "files" => $_FILES,
            "cookies" => $_COOKIE,
        ]);
    }
    //#class_end
}

//#file_end