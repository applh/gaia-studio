<?php
/**
 * Plugin Name: XP Studio
 */

class xp_studio
{
    static function start()
    {
        // add a shortcode test to display php date and hour
        add_shortcode('test', function ($attrs, $content = null) {
            $attrs = shortcode_atts([
                "name" => "",
                "bloc" => "",
            ], $attrs);
            extract($attrs);
            $bloc ??= "";

            if ($bloc == "") {
                $res = "hello world " . date("Y-m-d H:i:s");
            }
            else {
                // get the block type registry
                $block_types = WP_Block_Type_Registry::get_instance();
                // get the block type
                $block_type = $block_types->get_registered($name);
                $res = $block_type->render_callback ?? "---";
                // check if is_callable
                if (is_callable($res)) {
                    $res = $res([], "");
                }

            }

            return $res;
        });

        // add autoloader
        spl_autoload_register("xp_studio::autoload");
        
        add_action("init", "xp_studio::init");

        if (is_admin()) {
            xpw_admin::setup_admin();
        }
    }

    static function autoload ($classname) {
        // check if file exists in wp/class/$classname.php
        $path = __DIR__ . "/wp/class/$classname.php";
        if (file_exists($path)) {
            include $path;
        }
    }

    static function init ()
    {
        // add a hook on template_redirect is404
        add_action('template_redirect', 'xp_studio::template_redirect');

        // https://github.com/WordPress/gutenberg-examples/blob/trunk/blocks-jsx/meta-block/index.php
        // register_post_meta(
        //     'post',
        //     'myguten_meta_block_field',
        //     array(
        //         'show_in_rest' => true,
        //         'single'       => true,
        //         'type'         => 'string',
        //     )
        // );

        xp_studio::register_blocks();
    }


    static function template_redirect()
    {
        // check if is 404
        if (is_404()) {
            // MAKE A SIMPLE BRIDGE TO GAIA
            // load gaia unique entry point
            // cool: gaia will handle all requests with a single entry point
            ob_start();
            include __DIR__ . "/php/index.php";
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

    static function path_data ()
    {
        // set the path data for gaia to another plugon folder xp-data
        $path_data = __DIR__ . "/../xp-data";
        // check if exists
        if (!file_exists($path_data)) {
            // create the folder
            mkdir($path_data);
            // add index.php
            $code = <<<CODE
            <?php
            /**
             * Plugin Name: XP Data
             * /
 
            CODE;

            file_put_contents($path_data . "/index.php",$code);
        }
        $path_data = realpath($path_data);
        return $path_data;
    }

    // register blok shortcode
    static function register_blocks()
    {
        // register blocks
        include __DIR__ . '/wp/block-test-2/register.php';        
        // include __DIR__ . '/wp/block-test/register.php';

    }

}

xp_studio::start();
