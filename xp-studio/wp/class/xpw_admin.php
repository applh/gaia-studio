<?php
/**
 * xpw_admin
 * 
 * created: 2023-09-10 08:02:05
 * author: applh/gaia
 * license: MIT
 */

/**
 * xpw_admin
 */
class xpw_admin
{
    //#class_start
    static function setup_admin ()
    {
        // add a menu
        add_action('admin_menu', 'xpw_admin::admin_menu');

        // https://developer.wordpress.org/reference/hooks/allowed_block_types_all/
        add_filter( "allowed_block_types_all", "xpw_admin::allowed_block_types_all", 10, 2);

        // https://developer.wordpress.org/block-editor/how-to-guides/javascript/loading-javascript/
        // enqueue_block_editor_assets
        add_action("enqueue_block_editor_assets", "xpw_admin::enqueue_block_editor_assets");

        // https://developer.wordpress.org/reference/hooks/admin_footer-hook_suffix/
        // example: admin_footer-post.php, etc...
        add_action('admin_footer', 'xpw_admin::admin_footer');

        // https://developer.wordpress.org/reference/hooks/add_meta_boxes_post_type
        
        // add meta box
        // WARNING: will call callback with only 2 parameter ($post_type, $post)
        add_action('add_meta_boxes', 'xpw_admin::meta_box_cb', 10, 2);
    }

    static function admin_menu ()
    {
        // add a menu
        add_plugins_page(
            'XP Studio',
            'XP Studio',
            'manage_options',
            'xp-studio',
            'xpw_admin::admin_page'
        );
        // unregister form.css if page is xp-studio
        add_action('admin_enqueue_scripts', 'xpw_admin::admin_enqueue_scripts');
    }

    static function admin_enqueue_scripts ()
    {
        // check if is xp-studio
        if (isset($_GET['page']) && $_GET['page'] == 'xp-studio') {
            // breaks too many things
            // unregister form.css
            // wp_deregister_style('forms');
        }
    }

    static function admin_page ()
    {
        // load the admin page
        include __DIR__ . "/../admin/index.php";
    }

    static function admin_footer() 
    {
        global $pagenow;
        if ($pagenow == 'post.php') {
            echo "<script>console.log('admin_footer for post.php'); </script>";
        }
        echo "<script>console.log('admin_footer'); </script>";

    }

    static function enqueue_block_editor_assets ()
    {
        // load block editor extra script
        wp_enqueue_script('xp-editor',
            plugins_url('/wp/editor/xp-editor.js', xp_studio::$path_studio . "/wp"),
        );
    }


    static function allowed_block_types_all ($allowed_blocks, $editor_context)
    {   
        if ($editor_context->post->post_type == "xps-block") {
            // add script only if post type is xps-block
            wp_enqueue_script('xp-editor-xps-block',
                plugins_url('/wp/editor/xp-editor-xps-block.js', xp_studio::$path_studio . "/wp"),
                [ 'xp-editor' ]
            );
            return xpw_block::$allowed;
        }

        return $allowed_blocks;

    }

    static function meta_box_cb ($post_type, $post=null)
    {
        // check if $post_type starts with xps-
        if (substr($post_type, 0, 4) != "xps-") {
            return;
        }
        // TODO: add option to choose post types using the meta box
        // https://developer.wordpress.org/reference/functions/add_meta_box/
        add_meta_box(
            'xpw_meta_box',
            'XP Studio',
            'xpw_admin::meta_box_content',
            $post_type,
            'normal',
            'high'
        );
    }

    // meta_box_cb_post_type
    static function meta_box_cb_post_type ($post)
    {
        // check if $post_type starts with xps-
        if (substr($post->post_type, 0, 4) != "xps-") {
            return;
        }

        // TODO: add option to choose post types using the meta box
        // https://developer.wordpress.org/reference/functions/add_meta_box/
        add_meta_box(
            'xpw_meta_box',
            'XP Studio',
            'xpw_admin::meta_box_content',
            $post->post_type,
            'normal',
            'high'
        );
    }

    static function meta_box_content ($post)
    {
        // users can hide the meta box, but the code is still there
        // load the meta box content
        include __DIR__ . "/../admin/meta-box.php";
    }

    //#class_end
}

//#file_end