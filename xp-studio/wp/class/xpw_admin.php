<?php
/**
 * xpa_os
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

    //#class_end
}

//#file_end