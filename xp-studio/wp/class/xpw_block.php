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

    static function render_callback ()
    {
        header(("X-Xpw-Block: " . $_SERVER["REQUEST_URI"]));
        return date("Y-m-d H:i:s");
    }

    //#class_end
}

//#file_end