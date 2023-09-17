<?php

/**
 * Plugin Name: XP Studio
 * Description: XP Studio adds powerful Code Management System features to WP. (AI = Content MS * Code MS)
 */

class xp_studio
{
    static $path_studio = __DIR__;
    static $uri_rest_api = "/wp-json/xp-studio/v1/api";
    static $cache_duration = 86400; // in seconds

    static $autoloaders = [
        "wp" => "xp_studio::autoload_wp",
        "gaia" => "xp_studio::autoload_gaia",
        "cache" => "xp_studio::autoload_cache_code",
//        "db" => "xp_studio::autoload_db_code",
    ];

    static function start()
    {
        // add autoloaders
        spl_autoload_register("xp_studio::autoload_router");

        // activation / deactivation hooks
        register_activation_hook(__FILE__, "xpw_hook::activation");
        register_deactivation_hook(__FILE__, "xpw_hook::deactivation");

        // common init
        add_action("init", "xp_studio::init");

        // admin or front init
        if (is_admin()) {
            xpw_admin::setup_admin();
        } else {
            xp_studio::setup_front();
        }
    }

    static function setup_front()
    {
        // get config file from data folder
        $path_data = xp_studio::path_data();
        $path_config = "$path_data/config.php";
        if (file_exists($path_config)) {
            // include the config file
            include $path_config;
        } else {
            // create the config file
            $now = date("Y-m-d H:i:s");
            $code = <<<CODE
            <?php
            /**
             * created: $now
             * Description: Note: This folder is used as a data folder for xp-studio plugin
             */
            CODE;

            file_put_contents($path_config, $code);
        }

        xp_studio::over_host(true);
    }

    static function over_host($wait = false)
    {
        // check hosts to ovverride WP
        $host = $_SERVER['SERVER_NAME'];
        $wp_hosts = xpa_os::kv("wp/hosts") ?? [];
        // debug header
        header("X-XP-Studio-Host: $host");
        $host_route = $wp_hosts[$host] ?? false;
        if ($host_route) {
            header("X-XP-Studio-Host-Route: $host_route");
            
            // check if is_callable
            if (is_callable($host_route)) {
                $host_route();
                // WARNING: STOP WP HERE
                die();
            }
            elseif ($wait) {
                header("X-XP-Studio-Host-Wait: $host_route");
                // wait for init as may need to load code from db
                add_action("init", "xp_studio::over_host");
            }
        }
    }

    static function autoload_router($classname)
    {
        // loop on autoloaders
        foreach (xp_studio::$autoloaders as $name => $autoloader) {
            if (is_callable($autoloader)) {
                $found = $autoloader($classname);
                if ($found) {
                    return true;
                }
            }
        }
    }

    static function autoload_wp($classname)
    {
        // check if file exists in wp/class/$classname.php
        $path = __DIR__ . "/wp/class/$classname.php";
        if (file_exists($path)) {
            include $path;
            $found = true;
        }
        return $found ?? false;
    }

    static function autoload_gaia($classname)
    {
        // check if file exists in wp/class/$classname.php
        $path = __DIR__ . "/php/class/$classname.php";
        if (file_exists($path)) {
            include $path;
            $found = true;
        }
        return $found ?? false;
    }

    static function init()
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
            } else {
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


        // register post types
        xp_studio::register_post_types();
        
        // register blocks after post types as blocks can be defined as 'xps-block'
        xp_studio::register_blocks();
        // hook template for xps-blocks
        add_filter('template_include', 'xpw_hook::template_include');

    }

    static function register_post_types()
    {
        // register post type code
        // https://developer.wordpress.org/reference/functions/register_post_type/

        // TODO: excerpt is used as code, should use another field ?!
        register_post_type("xps-code", [
            "label" => "XP Codes",
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
        // spl_autoload_register("xp_studio::autoload_cache_code");
        // spl_autoload_register("xp_studio::autoload_db_code");
        xp_studio::$autoloaders["db"] = "xp_studio::autoload_db_code";

        // register post type xps-blocks
        register_post_type("xps-block", [
            "label" => "XP Blocks",
            "public" => true,
            "hierarchical" => true,
            "show_in_rest" => true,
            "menu_icon" => "dashicons-editor-code",
            "supports" => ["title", "editor", "author", "thumbnail", "excerpt", "custom-fields", "revisions", "page-attributes", "post-formats"],
            "can_export" => true,
        ]);
        // add category and tag support
        register_taxonomy_for_object_type('category', 'xps-blocks');
        register_taxonomy_for_object_type('post_tag', 'xps-blocks');

        flush_rewrite_rules();
    }


    static function autoload_cache_code($classname)
    {
        $path_data = xp_studio::path_data();
        // save the code in a file $path_data/class/$classname.php
        // check if folder exists
        $path = "$path_data/class";
        if (!file_exists($path)) {
            mkdir($path);
        }
        // $classname could have namespace
        // so easier to hash md5 to get a unique filename
        $hash = md5($classname);
        $path = "$path/cache-$hash.php";
        $path = realpath($path);
        if ($path !== false) {
            $mtime = filemtime($path);
            $now = time();
            $ttl = $mtime + xp_studio::$cache_duration - $now;
            if ($ttl > 0) {
                // headers before include as hack could send output in include
                header("X-XP-Studio-Cache: $path");
                header("X-XP-Studio-Cache-ttl: $ttl");

                // hack: include once the file as another autoloader could also include it
                // in case the file doesn't define the class...
                // include the file
                include_once $path;
                $found = true;
            } else {
                // WARNING: can be dangerous
                // remove the file
                unlink($path);
            }
        }
        return $found ?? false;
    }

    static function autoload_db_code($classname)
    {
        // debug
        header("X-XP-Studio-DB: $classname");
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
                // $classname could have namespace
                // so easier to hash md5 to get a unique filename
                $hash = md5($classname);
                $path = "$path/cache-$hash.php";
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
                } else {
                    // create the file
                    file_put_contents($path, $excerpt);
                }

                // include the file
                include_once $path;
                $found = true;
            }
        }
        wp_reset_postdata();

        return $found ?? false;
    }

    static function path_data()
    {
        // set the path data for gaia to another plugon folder xp-data
        $path_data = __DIR__ . "/../xp-data";

        // check there's a folder with prefix xp-data
        $found = glob("$path_data-*");
        if (empty($found)) {
            // add a random md5 hash as suffix to avoid conflict and hide the folder
            $md5 = md5(password_hash(uniqid(), PASSWORD_DEFAULT));
            $path_data .= "-$md5";
            // get now
            $now = date("Y-m-d H:i:s");
            // create the folder
            mkdir($path_data);
            // add index.php
            $code = <<<CODE
            <?php
            /**
             * Plugin Name: XP Data
             * created: $now
             * Description: Note: This folder is used as a data folder for xp-studio plugin
             * /
 
            CODE;

            file_put_contents($path_data . "/index.php", $code);
        } else {
            $path_data = $found[0];
        }
        $path_data = realpath($path_data);
        return $path_data;
    }

    // register blok shortcode
    static function register_blocks()
    {
        // register blocks
        // TODO: loop with glob on /wp/block-*/register.php
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

        // register dynalic blocks from xps-blocks post type
        $xps_blocks = get_posts([
            "post_type" => "xps-block",
            "post_status" => "publish",
            "posts_per_page" => -1,
        ]);
        // loop on blocks
        foreach ($xps_blocks as $xps_block) {
            // get the block name
            $block_name = $xps_block->post_name;
            $block_title = $xps_block->post_title;
            wp_register_script(
                "xps-block-$block_name",
                //plugins_url('block-js.php', xp_studio::$path_studio . "/wp/editor/block-js.php"),
                // plugins_url("wp/editor/block-js.php?bn=$block_name&bt=$block_title", __FILE__),
                "/xps-block/$block_name",
                ['wp-blocks', 'wp-element', 'wp-polyfill'],
                '0.2'
            );
            
            // register the block
            register_block_type("xps-block/$block_name", [
                "api_version" => 3,
                "name" => "xps-block/$block_name",
                "title" => $block_title, // $xps_block->post_title,
                "icon" => "smiley",
                "category" => "text",
                "editor_script_handles" => [ "xps-block-$block_name" ],
                "render_callback" => "xpw_block::render_callback",
            ]);
        }
    }
}

xp_studio::start();
