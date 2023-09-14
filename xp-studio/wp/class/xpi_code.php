<?php
/**
 * xpi_code
 * 
 * created: 2023-09-10 08:02:05
 * author: applh/gaia
 * license: MIT
 */

/**
 * xpi_code
 */
class xpi_code
{
    //#class_start
    static function load_data ()
    {
        $res = [];

        // FIXME: not working ??
        // security check
        // check if current user can update plugins
        // if (!current_user_can('activate_plugins')) {
        //     // get current user capabilities
        //     $user = wp_get_current_user();
        //     $res = $user;
        //     return $res;
        // }

        // find post_type = 'xps-code' where category = 'php'
        $args = [
            "post_type" => "xps-code",
            "category_name" => "php",
            "posts_per_page" => -1,
            "post_status" => "publish",
        ];

        $query = new WP_Query($args);
        $founds = $query->get_posts();

        // check if found
        foreach($founds as $post) {
            // get the excerpt
            $excerpt = $post->post_excerpt;
            $post_name = $post->post_name;
            $item  = [
                "id" => $post->ID,
                "label" => $post->post_title,
                "name" => $post_name,
                "code" => $excerpt,
            ];
            $res[] = $item;
        }

        return $res;
    }

    static function create ()
    {
        $res = [];
        // get the post data: title, name, code
        $title = $_REQUEST['title'] ?? "";
        $name = $_REQUEST['name'] ?? "";
        // $code = $_REQUEST['code'] ?? "";
        // get code from $FILES["code.php"]
        $tmp_name = $_FILES["code"]["tmp_name"] ?? "";
        if ($tmp_name != "") {
            $code = file_get_contents($tmp_name);
        }
        // sanitize name
        // remove all non alpha numeric, keep - and _
        $name = preg_replace("/[^a-zA-Z0-9-_]/", "", $name);
        // check if name is empty
        if (!empty($name)) {
            // add new post in post_type = 'xps-code' and category = 'php'
            $category = "php";
            $category_id = get_cat_ID($category);
            // deactivate wp_insert_post() hook on excerpt
            remove_filter('content_save_pre', 'wp_filter_post_kses');
            remove_filter('content_filtered_save_pre', 'wp_filter_post_kses');
            // insert the post
            $post = [
                "post_title" => $title,
                "post_content" => "",
                "post_name" => $name,
                "post_excerpt" => $code ?? "",
                "post_type" => "xps-code",
                "post_status" => "publish",
                "post_category" => [$category_id],
            ];
            $id = wp_insert_post($post);

            // FIXME: get $user as admin to remove sanitize filters ?
            // hack: update excerpt to raw code
            global $wpdb;
            $wpdb->update(
                $wpdb->posts,
                ["post_excerpt" => $code],
                ["ID" => $id],
                ["%s"],
                ["%d"]
            );

            $res = xpi_code::load_data();
        }
        return $res;
    }

    static function delete ()
    {
        $res = [];
        // get the post data: id
        $id = intval($_REQUEST['id'] ?? 0);
        // check if id is empty
        if ($id > 0) {
            // delete the post
            wp_delete_post($id);
            $res = xpi_code::load_data();
        }
        return $res;
    }
    //#class_end
}

//#file_end