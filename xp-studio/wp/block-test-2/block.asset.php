<?php

// WARNING:
// MANDATORY this file must exists if block.json is using property editorScript
// these information will be used by WP call to wp_register_script
return
    array(
        'dependencies' =>
        array(
            'wp-blocks',
            'wp-element',
            'wp-polyfill'
        ),
        'version' => '0.1'
    );
