<?php

$plugin_url = plugins_url('admin', dirname(__FILE__));
$curdir = __DIR__;
// find file "$curdir/dist/assets/index-*.js"
$files = glob("$curdir/dist/assets/index-*.js");
$index_js = $files[0] ?? "";
$index_js = basename($index_js);
$index_js_url = "$plugin_url/dist/assets/$index_js";

// find file "$curdir/dist/assets/index-*.css"
$files = glob("$curdir/dist/assets/index-*.css");
$index_css = $files[0] ?? "";
$index_css = basename($index_css);
$index_css_url = "$plugin_url/dist/assets/$index_css";

// rest api nonce
$rest_api_nonce = wp_create_nonce('wp_rest');

?>

<script type="module" crossorigin src="<?php echo $index_js_url ?? "" ?>"></script>
<link rel="stylesheet" href="<?php echo $index_css_url ?? "" ?>">
<div id="app" data-wp-nonce="<?php echo $rest_api_nonce ?>"></div>
