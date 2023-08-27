<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN</title>
</head>

<body>
    <div id="app"></div>
    <template id="app-template">
        <div>
            <h1>Admin</h1>
            <p>Welcome admin</p>
        </div>
    </template>
    <!-- import map -->
    <script type="importmap">
        {
            "imports": {
                "vue": "/assets/vue-esm-prod-334.js",
                "vp-admin": "/assets/vp-admin.js",
                "vp-app": "/assets/vp-app.js",
                "vp-common": "/assets/vp-common.js"
            }
        }
    </script>
    <script type="module">
        import { createApp } from 'vue';
        import vp_admin from 'vp-admin';

        let app = createApp({
            template: '#app-template'
        })
        app.use(vp_admin);
        app.mount('#app');
    </script>
</body>

</html>