<?php
/**
 * xpw_block
 * 
 * created: 2023-09-16 22:36:39
 * author: applh/gaia
 * license: MIT
 */

/**
 * xpw_block
 */
class xpw_block
{
    //#class_start

    static $allowed = [];
    
    static function render_callback ($attrs, $content, $block)
    {
        // block context could be used to get parent attributes, etc...
        // https://developer.wordpress.org/block-editor/reference-guides/block-api/block-context/

        header(("X-Xpw-Block: " . $_SERVER["REQUEST_URI"]));
        $now = date("Y-m-d H:i:s");
        return
        "<div>$now</div>" 
        .$content;
    }

    //#class_end
}

//#file_end