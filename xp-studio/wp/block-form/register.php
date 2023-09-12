<?php
// https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/writing-your-first-block-type/
// https://github.com/WordPress/gutenberg-examples/tree/trunk/blocks-non-jsx/01-basic

register_block_type( __DIR__, [
    "render_callback" => function ($attributes, $content) {
        // get url rest api
        $url_rest_api = xp_studio::$uri_rest_api;
        $form_key = "form-" . uniqid();
        $className = $attributes["className"] ?? "";
        $now = date("Y-m-d H:i:s");
        $html = <<<HTML
        <div class="xps-form $className" data-key="$form_key">
            <form method="POST">
                $content
            </form>
        </div>
        <script type="module">
            let url_rest_api = "$url_rest_api";
            // load vue
            let vue = await import('/assets/vue-esm-prod-334.js');
            // find form .xps-form form
            let form = document.querySelector('[data-key="$form_key"] form');
            // add event listener on submit
            form.addEventListener('submit', async (e) => {
                // prevent default
                e.preventDefault();
                // get form data
                let formData = new FormData(form);
                // send data to api
                let response = await fetch(url_rest_api, {
                    method: 'POST',
                    body: formData,
                });
                // get json response
                let json = await response.json();
                // log json
                console.log(json);
                // check if form_contact is defined
                if (json.form_contact) {
                    // get form_contact
                    let form_contact = json.form_contact;
                    // get feedback
                    let feedback = form.querySelector('.feedback');
                    // set feedback
                    feedback.innerHTML = form_contact;
                }
            });
        </script>
        HTML;
        return $html;
    }
] );

$default_html = <<<HTML
<form method="POST">
    <label>
        <span>name</span>
        <input type="text" name="name" placeholder="name" required autocomplete="on" />
    </label>
    <label>
        <span>email</span>
        <input type="email" name="email" placeholder="email" required autocomplete="on" />
    </label>
    <label>
        <span>message</span>
        <textarea name="message" placeholder="message" rows="10" required></textarea>
    </label>
    <label>
        <span></span>
        <input type="hidden" name="@method" value="contact" />
        <button type="submit">SUBMIT</button>
    </label>
    <div class="feedback">...</div>
</form>

HTML;

class xpw_form
{
    static function contact ()
    {
        $now = date("Y-m-d H:i:s");
        $res = "...processing... ($now)";
        return $res;
    }
}