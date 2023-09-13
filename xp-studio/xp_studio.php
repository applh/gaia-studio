<?php
/**
 * Plugin Name: XP Studio
 */

class xp_studio
{
    static $path_studio = __DIR__;
    static $uri_rest_api = "/wp-json/xp-studio/v1/api";
    static $cache_duration = 86400; // in seconds

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
        
        // register post types
        xp_studio::register_post_types();
    }

    static function register_post_types ()
    {
        // register post type code
        // https://developer.wordpress.org/reference/functions/register_post_type/

        // TODO: excerpt is used as code, should use another field ?!
        register_post_type("xps-code", [
            "label" => "Code",
            "public" => true,
            "hierarchical" => true,
            "show_in_rest" => true,
            "menu_icon" => "dashicons-editor-code",
            "supports" => ["title", "editor", "author", "thumbnail", "excerpt", "custom-fields", "revisions", "page-attributes", "post-formats"], 
            "can_export" => true,
        ]);
        // add category and tag support
        register_taxonomy_for_object_type('category', 'xps-code');
        register_taxonomy_for_object_type('post_tag', 'xps-code');

        // WARNING: can slow down app if too many inexisting classes
        // add autoload_code
        spl_autoload_register("xp_studio::autoload_cache_code");
        spl_autoload_register("xp_studio::autoload_db_code");
    }

    static function autoload_cache_code ($classname)
    {
        $path_data = xp_studio::path_data();
        // save the code in a file $path_data/class/$classname.php
        // check if folder exists
        $path = "$path_data/class";
        if (!file_exists($path)) {
            mkdir($path);
        }
        $path = "$path/$classname.php";
        $path = realpath($path);
        if ($path !== false) {
            $mtime = filemtime($path);
            $now = time();
            $ttl= $mtime + xp_studio::$cache_duration - $now;
            if ($ttl > 0) {
                // include the file
                include $path;
                header("X-XP-Studio-Cache: $path");
                header("X-XP-Studio-Cache-ttl: $ttl");
            }
            else {
                // WARNING: can be dangerous
                // remove the file
                unlink($path);
            }
        }
    }

    static function autoload_db_code ($classname)
    {
        // WARNING: can slow down app if too many inexisting classes
        // search in post type xps-code with category php
        $args = [
            "post_type" => "xps-code",
            "category_name" => "php",
            "posts_per_page" => 1,
            "post_status" => "publish",
            // get by slug
            "name" => $classname,
        ];
        $query = new WP_Query($args);
        // check if found
        if ($query->have_posts()) {
            // load the post
            $query->the_post();
            // get the excerpt
            $excerpt = get_the_excerpt();
            if ($excerpt != "") {
                $path_data = xp_studio::path_data();
                // save the code in a file $path_data/class/$classname.php
                // check if folder exists
                $path = "$path_data/class";
                if (!file_exists($path)) {
                    mkdir($path);
                }
                $path = "$path/$classname.php";
                // check file mtime if older than 1 day
                // then update the file
                if (file_exists($path)) {
                    $mtime = filemtime($path);
                    $now = time();
                    $diff = $now - $mtime;
                    if ($diff > xp_studio::$cache_duration) {
                        // update the file
                        file_put_contents($path, $excerpt);
                    }
                }
                else {
                    // create the file
                    file_put_contents($path, $excerpt);
                }

                // include the file
                include $path;
            }

        } 
        wp_reset_postdata();
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
        include __DIR__ . '/wp/block-basic/register.php';        
        include __DIR__ . '/wp/block-form/register.php';        
        include __DIR__ . '/wp/block-form-ui/register.php';        
        include __DIR__ . '/wp/block-vue/register.php';        
        // include __DIR__ . '/wp/block-test/register.php';

        // include __DIR__ . '/wp/block-test-2/register.php';        


        // REACT BLOCKS
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
