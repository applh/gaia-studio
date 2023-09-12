<?php
// https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/writing-your-first-block-type/
// https://github.com/WordPress/gutenberg-examples/tree/trunk/blocks-non-jsx/01-basic

register_block_type( __DIR__, [
    "render_callback" => function ($attributes, $content) {
        $now = date("Y-m-d H:i:s");
        return "Hello World ($now) $content";
    }
] );
// Notice: Function register_block_script_handle was called <strong>incorrectly</strong>. The asset file (/var/www/html/wp-content/plugins/xp-studio/wp/block-basic/block.asset.php) for the "editorScript" defined in "gutenberg-examples/example-01-basic" block definition is missing. Please see <a href="https://wordpress.org/documentation/article/debugging-in-wordpress/">Debugging in WordPress</a> for more information. (This message was added in version 5.5.0.) in /var/www/html/wp-includes/functions.php on line 5905
