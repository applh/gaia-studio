<div id="app">
    <form>
        <input v-model="mm">
        <button>send</button>
        <h3>{{ mm }}</h3>
    </form>
</div>

<script type="module">

console.log('hello');
let vue = await import("/wp-content/plugins/xperia-main/media/vue.esm-browser.prod.js");
let data = {
mm: 'hello',
}
let app = vue.createApp({
    data: () => data
});
// hack: need app to be available in created()
app.mount('#app')

</script>

<pre>

<?php

echo date("Y-m-d H:i:s");

// $attributes
// $content
// $block

// WARNING: deprecated meta
// editor_script
// script
// view_script
// editor_style
// style

print_r($block ?? []);

/**
 * @since 6.1.0 Added the `editor_script_handles`, `script_handles`, `view_script_handles,
 *              `editor_style_handles`, and `style_handles` properties.
 *              Deprecated the `editor_script`, `script`, `view_script`, `editor_style`, and `style` properties.
 */
?>
</pre>