<?php
/**
 * Plugin Name: XP Studio
 */

class xp_studio
{
    static function start()
    {
        // add a shortcode test to display php date and hour
        add_shortcode('test', function () {
            $res = "hello world " . date("Y-m-d H:i:s");
            // get the block type registry
            $block_types = WP_Block_Type_Registry::get_instance();
            // get the block type
            $block_type = $block_types->get_registered("xps/test");
            $res = $block_type->render_callback ?? "---";
            // check if is_callable
            if (is_callable($res)) {
                $res = $res([], "");
            }

            return $res;
        });

        add_action("init", "xp_studio::init");

        // add a hook on template_redirect is404
        add_action('template_redirect', 'xp_studio::template_redirect');


    }

    static function init ()
    {
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
            $data = [
                "now" => date("Y-m-d H:i:s"),
            ];
            status_header(200);
            header("Content-Type: application/json");
            echo json_encode($data, JSON_PRETTY_PRINT);
            die();
        }
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
