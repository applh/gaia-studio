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
        register_post_meta(
            'post',
            'myguten_meta_block_field',
            array(
                'show_in_rest' => true,
                'single'       => true,
                'type'         => 'string',
            )
        );

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
        include __DIR__ . '/wp/block-test/register.php';
        include __DIR__ . '/wp/block-test-2/register.php';

        // register_block_type_from_metadata(
        //     __DIR__ . '/wp/shortcode',
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

        // wp_register_script(
        //     'xperia-block-d',
        //     plugins_url('wp/block-d/block.js', __FILE__),
        //     ['wp-blocks', 'wp-element', 'wp-polyfill'],
        //     '0.3'
        // );
                
        // https://developer.wordpress.org/reference/classes/wp_block_type_registry/register/
        // https://developer.wordpress.org/reference/classes/wp_block_type/
        // register_block_type('xperia/block-d', array(
        // WARNING: render_callback is not working in block.json
        // must be set in PHP register_block_type 
        // register_block_type(__DIR__ . "/wp/block-test", array(
            // 'name' => 'xperia/block-d',
            // 'api_version' => 3,
            // 'title' => 'Xperia Block D',
            // 'category' => 'text',
            // 'icon' => 'smiley',
            // 'editor_script' => 'xperia-block-d', // The script name we gave in the wp_register_script() call.
            // 'render_callback' => 'xp_studio::render',
            // 'supports' => array('color' => true, 'align' => true),
            // 'attributes' => [
            //     "hello_text" => [
            //         "type" => "string",
            //         "default" => "Hello World",
            //         // will need a js save function
            //         // will be saved as HTML content
            //         // "source" => "text",
            //         // "selector" => "p",
            //     ],
            //     // no need of js save function
            //     // will be saved as json property
            //     "my_test" => [
            //         "type" => "string",
            //         "default" => "my_test",
            //     ],
            //     // will be saved as json property
            //     "my_meta" => [
            //         "type" => "string",
            //         "default" => "my_meta",
            //     ],
            // ],
            // https://developer.wordpress.org/block-editor/reference-guides/block-api/block-supports/
            // "supports" => [
            //     "className" => true,    // cool
            //     "customClassName" => true, // cool
            //     "html" => true,
            //     "anchor" => true,
            //     // "align" => true, // ?? not working 
            // ],
    
        // ));
        
        // FIXME: WP doesn't find the block.json file ?!
        // $block_test = __DIR__ . '/wp/block-test';
        // $block_test = realpath($block_test);
        // $block_test = plugin_dir_path(__FILE__) . 'wp/block-test/block.json';
        // header("x-debug-block-test: $block_test");

        // register_block_type_from_metadata($block_test, [
        //     'render_callback' => 'xp_studio::render',
        // ]);

    }

    static function render ()
    {
        $search = glob(__DIR__ . '/wp/block-test/*.json');
        $res = print_r($search, true);
        return $res . " hello world from render... ". date("Y-m-d H:i:s");
        // die();
    }

    static function render_block_core_shortcode($attributes, $content)
    {
        return wpautop($content);
    }
}

xp_studio::start();
