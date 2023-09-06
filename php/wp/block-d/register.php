<?php

// dynamic block
// https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/creating-dynamic-blocks/

// automatically load dependencies and version
// $asset_file = include(plugin_dir_path(__FILE__) . 'block.asset.php');

wp_register_script(
    'xperia-block-d',
    plugins_url('block.js', __FILE__),
    ['wp-blocks', 'wp-element', 'wp-polyfill'],
    '0.2'
);

function render_d ($attributes, $content)
{
    return "hello world from block render... ". date("Y-m-d H:i:s");
}

// https://developer.wordpress.org/reference/classes/wp_block_type_registry/register/
// https://developer.wordpress.org/reference/classes/wp_block_type/
register_block_type('xperia/block-d', array(
    'api_version' => 3,
    'title' => 'Xperia Block D',
    'category' => 'text',
    'icon' => 'smiley',
    'editor_script' => 'xperia-block-d', // The script name we gave in the wp_register_script() call.
    // 'render_callback' => 'render_d',
    'render_callback' => 'xp_studio::render',
    // 'supports' => array('color' => true, 'align' => true),
));


