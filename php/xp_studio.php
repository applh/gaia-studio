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
            $block_type = $block_types->get_registered("xperia/block-d");
            $res = $block_type->render_callback ?? "---";
            // check if is_callable
            if (is_callable($res)) {
                $res = $res([], "");
            }

            return $res;
        });

        add_action("init", "xp_studio::register_blocks");
    }

    // register blok shortcode
    static function register_blocks()
    {

        // register_block_type_from_metadata(
        //     __DIR__ . '/shortcode',
        //     array(
        //         'render_callback' => 'xp_studio::render_block_core_shortcode',
        //     )
        // );

        // register_block_type_from_metadata(
        //     __DIR__ . '/block-r',
        // );

        // https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/creating-dynamic-blocks/
        // $plugin_dir = plugin_dir_path(__FILE__);
        // include("$plugin_dir/wp/block-d/register.php");

        wp_register_script(
            'xperia-block-d',
            plugins_url('wp/block-d/block.js', __FILE__),
            ['wp-blocks', 'wp-element', 'wp-polyfill'],
            '0.3'
        );
                
        // https://developer.wordpress.org/reference/classes/wp_block_type_registry/register/
        // https://developer.wordpress.org/reference/classes/wp_block_type/
        register_block_type('xperia/block-d', array(
            'api_version' => 3,
            'title' => 'Xperia Block D',
            'category' => 'text',
            'icon' => 'smiley',
            'editor_script' => 'xperia-block-d', // The script name we gave in the wp_register_script() call.
            'render_callback' => 'xp_studio::render',
            // 'supports' => array('color' => true, 'align' => true),
        ));
        
        
    }

    static function render ()
    {
        return "hello world from render... ". date("Y-m-d H:i:s");
        // die();
    }

    static function render_block_core_shortcode($attributes, $content)
    {
        return wpautop($content);
    }
}

xp_studio::start();
