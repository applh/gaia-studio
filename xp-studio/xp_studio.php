<?php
/**
 * Plugin Name: XP Studio
 */

class xp_studio
{
    static $path_studio = __DIR__;

    static function start()
    {
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
        add_action('template_redirect', 'xpw_hook::template_redirect');

        // add a rest api entry point 
        add_action('rest_api_init', 'xpw_hook::rest_api_init');

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

        $path_blocks = __DIR__ . "/blocks/*/block.json";
        $blocks = glob($path_blocks);
        // loop on blocks
        foreach ($blocks as $block) {
            // get the block name
            $block_name = basename(dirname($block));
            // register the block
            register_block_type_from_metadata(
                dirname($block),
            );
        }

    }

}

xp_studio::start();
