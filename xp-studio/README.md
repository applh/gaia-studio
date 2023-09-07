# XP-STUDIO

## CODE ORGANISATION

This folder should be used with wp-env

* https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/

```
wp-env start

wp-env stop

wp-env destroy

```


### CODE WITH PHP TO REGISTER A BLOCK

```php

        // https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/creating-dynamic-blocks/

        wp_register_script(
            'xperia-block-d',
            plugins_url('wp/block-d/block.js', __FILE__),
            ['wp-blocks', 'wp-element', 'wp-polyfill'],
            '0.3'
        );
                
        // https://developer.wordpress.org/reference/classes/wp_block_type_registry/register/
        // https://developer.wordpress.org/reference/classes/wp_block_type/
        // register_block_type('xperia/block-d', array(
        // WARNING: render_callback is not working in block.json
        // must be set in PHP register_block_type 
        register_block_type(__DIR__ . "/wp/block-test", array(
            'name' => 'xperia/block-d',
            'api_version' => 3,
            'title' => 'Xperia Block D',
            'category' => 'text',
            'icon' => 'smiley',
            'editor_script' => 'xperia-block-d', // The script name we gave in the wp_register_script() call.
            'render_callback' => 'render_my_block',
            'supports' => array('color' => true, 'align' => true),
            'attributes' => [
                "hello_text" => [
                    "type" => "string",
                    "default" => "Hello World",
                    // will need a js save function
                    // will be saved as HTML content
                    // "source" => "text",
                    // "selector" => "p",
                ],
                // no need of js save function
                // will be saved as json property
                "my_test" => [
                    "type" => "string",
                    "default" => "my_test",
                ],
                // will be saved as json property
                "my_meta" => [
                    "type" => "string",
                    "default" => "my_meta",
                ],
            ],
            // https://developer.wordpress.org/block-editor/reference-guides/block-api/block-supports/
            "supports" => [
                "className" => true,    // cool
                "customClassName" => true, // cool
                "html" => true,
                "anchor" => true,
                // "align" => true, // ?? not working 
            ],
    
        ));

    function render_my_block ($attributes, $content)
    {
        $search = glob(__DIR__ . '/wp/block-test/*.json');
        $res = print_r($search, true);
        return $res . " hello world from render... ". date("Y-m-d H:i:s");
        // die();
    }

```