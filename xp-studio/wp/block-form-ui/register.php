<?php
// https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/writing-your-first-block-type/
// https://github.com/WordPress/gutenberg-examples/tree/trunk/blocks-non-jsx/01-basic

register_block_type( __DIR__, [
    "render_callback" => function ($attributes, $content) {
        // get url rest api
        $url_rest_api = xp_studio::$uri_rest_api;
        $form_key = "form-ui-" . uniqid();
        $className = $attributes["className"] ?? "";
        $ui_type = $attributes["ui_type"] ?? "text";
        $ui_label = $attributes["ui_label"] ?? "";
        $ui_name = $attributes["ui_name"] ?? "";
        $ui_html = $attributes["ui_html"] ?? "";

        if ($ui_type == "textarea") {
            $ui_html = <<<HTML
            <label>
                <span>$ui_label</span>
                <textarea name="message" placeholder="$ui_label" rows="10" required $ui_html></textarea>
            </label>
            HTML;
    
        }
        elseif ($ui_type == "button") {
            $ui_html = <<<HTML
            <label>
                <span></span>
                <input type="hidden" name="@method" value="$ui_name" $ui_html/>
                <button type="submit">$ui_label</button>
            </label>
            <div class="feedback">...</div>
            HTML;
    
        }
        else {
            // text, email, etc...
            $ui_html = <<<HTML
            <label>
                <span>$ui_label</span>
                <input type="$ui_type" name="$ui_name" placeholder="$ui_label" required autocomplete="on" $ui_html/>
            </label>
            HTML;
    
        }

        $now = date("Y-m-d H:i:s");
        $html = <<<HTML
        <div class="xps-form-ui $className" data-key="$form_key">
            $content
            $ui_html
        </div>
        HTML;
        return $html;
    }
] );


