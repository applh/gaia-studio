<?php

// tip: using anonymous function allow to reuse the folder name as block name
register_block_type(__DIR__, [
    "render_callback" => function ($attributes, $content) {
        return "GOGO... ". date("Y-m-d H:i:s");
    },
]);
