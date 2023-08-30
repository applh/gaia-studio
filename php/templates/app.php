<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APP</title>
    <!-- import Element Plus CSS -->
    <link rel="stylesheet" href="/assets/element-plus/index-min.css">
</head>

<body>
    <div id="app"></div>
    <template id="app-template">
        <div>
            <h1>App</h1>
            <p>Welcome app</p>
            <el-button type="success">Success</el-button>
        </div>
    </template>
    <!-- import map -->
    <script type="importmap">
        {
            "imports": {
                "vue": "/assets/vue-esm-prod-334.js",
                "element-plus": "/assets/element-plus/index-min.mjs",
                "vp-admin": "/assets/vp-admin.js",
                "vp-app": "/assets/vp-app.js",
                "vp-common": "/assets/vp-common.js"
            }
        }
    </script>
    <script type="module">
        import {
            createApp
        } from '/assets/vue-esm-prod-334.js';
        import vp_app from 'vp-app';

        let app = createApp({
            template: '#app-template'
        })
        app.use(vp_app);
        app.mount('#app');
    </script>
</body>

</html>