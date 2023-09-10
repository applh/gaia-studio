<?php
// security 
if (!is_callable('add_action')) {
    die();
}
?>
<div id="app"></div>

<template id="template-app">
    <h1>XP Studio</h1>
    <button>{{ message }}</button>
    <el-button type="success">Element Plus is ready</el-button>
</template>

<!-- import map -->
<script type="importmap">
    {
        "imports": {
            "vue": "/assets/vue-esm-prod-334.js",
            "element-plus": "/assets/element-plus/index-min.mjs"
        }
    }
</script>
<script type="module">
    import { createApp } from 'vue';
    import ElementPlus from 'element-plus';

    let data = {
        message: "hello Vue",
    };

    // create the vue app
    const app = createApp({
        template: "#template-app",
        // copy data to instance
        data: () => Object.assign({}, data),
        created: function () {
            // load css assets /assets/element-plus/index-min.css
            let link = document.createElement('link');
            link.rel = 'stylesheet';
            link.type = 'text/css';
            link.href = '/assets/element-plus/index-min.css';
            document.head.appendChild(link);
        },
    });
    app.use(ElementPlus)
    // mount the app
    app.mount("#app");
</script>