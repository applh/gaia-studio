<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APP</title>
    <!-- import Element Plus CSS -->
    <link rel="stylesheet" href="/assets/element-plus/index-min.css">
    <link rel="stylesheet" href="/assets/css/app.css">
    <style>
    </style>

</head>

<body>
    <div id="app"></div>
    <template id="app-template">
        <div class="common-layout">
            <el-container>
                <el-aside width="200px">
                    <h1>Welcome</h1>
                    <el-menu :default-openeds="['1']">
                        <el-sub-menu index="1">
                            <template #title>
                                <el-icon>
                                    <message />
                                </el-icon>Navigator One
                            </template>
                            <el-menu-item-group>
                                <template #title>Group 1</template>
                                <el-menu-item index="1-1">Option 1</el-menu-item>
                                <el-menu-item index="1-2">Option 2</el-menu-item>
                            </el-menu-item-group>
                            <el-menu-item-group title="Group 2">
                                <el-menu-item index="1-3">Option 3</el-menu-item>
                            </el-menu-item-group>
                            <el-sub-menu index="1-4">
                                <template #title>Option4</template>
                                <el-menu-item index="1-4-1">Option 4-1</el-menu-item>
                            </el-sub-menu>
                        </el-sub-menu>
                        <el-sub-menu index="2">
                            <template #title>
                                <el-icon><icon-menu /></el-icon>Navigator Two
                            </template>
                            <el-menu-item-group>
                                <template #title>Group 1</template>
                                <el-menu-item index="2-1">Option 1</el-menu-item>
                                <el-menu-item index="2-2">Option 2</el-menu-item>
                            </el-menu-item-group>
                            <el-menu-item-group title="Group 2">
                                <el-menu-item index="2-3">Option 3</el-menu-item>
                            </el-menu-item-group>
                            <el-sub-menu index="2-4">
                                <template #title>Option 4</template>
                                <el-menu-item index="2-4-1">Option 4-1</el-menu-item>
                            </el-sub-menu>
                        </el-sub-menu>
                        <el-sub-menu index="3">
                            <template #title>
                                <el-icon>
                                    <setting />
                                </el-icon>Navigator Three
                            </template>
                            <el-menu-item-group>
                                <template #title>Group 1</template>
                                <el-menu-item index="3-1">Option 1</el-menu-item>
                                <el-menu-item index="3-2">Option 2</el-menu-item>
                            </el-menu-item-group>
                            <el-menu-item-group title="Group 2">
                                <el-menu-item index="3-3">Option 3</el-menu-item>
                            </el-menu-item-group>
                            <el-sub-menu index="3-4">
                                <template #title>Option 4</template>
                                <el-menu-item index="3-4-1">Option 4-1</el-menu-item>
                            </el-sub-menu>
                        </el-sub-menu>
                    </el-menu>
                </el-aside>
                <el-container>
                    <el-header>
                        <h1>Member Area</h1>
                    </el-header>
                    <el-main>
                        <p>Please fill the login form</p>
                        <el-form :label-position="labelPosition" label-width="100px" :model="formLabelAlign" style="max-width: 600px">
                            <el-form-item label="Email">
                                <el-input v-model="formLogin.email" />
                            </el-form-item>
                            <el-form-item label="Password">
                                <el-input v-model="formLogin.password" />
                            </el-form-item>
                            <el-form-item label="">
                                <el-button type="primary">Login</el-button>
                            </el-form-item>

                        </el-form>
                    </el-main>
                    <el-footer>
                        <p>Gaia Studio &copy;2023</p>
                        <el-button type="primary" style="margin-right: 16px" @click="drawer = true">
                            Options
                        </el-button>
                        <el-button type="success">Home</el-button>
                    </el-footer>
                </el-container>
            </el-container>
            <el-drawer v-model="drawer" title="I am the title" :with-header="false" direction="btt">
                <span>Hi there!</span>
            </el-drawer>
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

        let data = {
            drawer: false,
            formLogin: {
                email: '',
                password: '',
            },
            labelPosition: 'right',
        };

        let app = createApp({
            template: '#app-template',
            data: () => data,
        });
        app.use(vp_app);
        app.mount('#app');
    </script>
</body>

</html>