<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN</title>
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
                    <el-menu :default-openeds="[]">
                        <el-menu-item index="1-1" @click="$store.page = 'login'">login</el-menu-item>
                        <el-menu-item index="1-2" @click="$store.page = 'jobs'">jobs</el-menu-item>
                        <el-sub-menu index="1">
                            <template #title>
                                <el-icon>
                                    <message />
                                </el-icon>Navigator One
                            </template>
                            <el-menu-item-group>
                                <template #title>Group 1</template>
                                <el-menu-item index="1-1" @click="$store.page = 'login'">login</el-menu-item>
                                <el-menu-item index="1-2" @click="$store.page = 'jobs'">jobs</el-menu-item>
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
                        <h1>Admin Area ({{ $store.page }}) ({{ $store.formLogin.email }})</h1>
                        <xp-test></xp-test>
                    </el-header>
                    <el-main>
                        <hr>
                        <xp-admin-login v-if="$store.page == 'login'"></xp-admin-login>
                        <xp-admin-jobs v-if="$store.page == 'jobs'"></xp-admin-jobs>
                        <hr>
                    </el-main>
                    <el-footer>
                        <p>Gaia Studio &copy;2023</p>
                        <el-button type="primary" style="margin-right: 16px" @click="drawer = true">
                            Options
                        </el-button>
                        <el-button type="success">Home</el-button>
                        <el-input v-model="$store.page" />
                    </el-footer>
                </el-container>
            </el-container>
            <el-drawer v-model="drawer" title="I am the title" :with-header="false" direction="btt">
                <span>Hi there!</span>
            </el-drawer>
        </div>
    </template>

    <template id="template-xp-admin-login">
        <div>
            <h1>xp-admin-login</h1>
            <p>Please fill the login form</p>
            <el-form :label-position="labelPosition" label-width="100px" :model="formLabelAlign" style="max-width: 600px">
                <el-form-item label="Email">
                    <el-input v-model="$store.formLogin.email" />
                </el-form-item>
                <el-form-item label="Password">
                    <el-input v-model="$store.formLogin.password" />
                </el-form-item>
                <el-form-item label="">
                    <el-button type="primary">Login</el-button>
                </el-form-item>
            </el-form>
        </div>
    </template>

    <template id="template-xp-admin-jobs">
        <div>
            <h1>xp-admin-jobs</h1>
            <p>Jobs (total: {{ $store.jobs.length }}) (visible: {{ nb_show() }})</p>
            <label>
                <span>level min</span>
                <input name="z" type="number" v-model="level_min" />
                <input name="z" type="range" v-model="level_min" min="-10" max="10"/>
            </label>

            <ol class="jobs">
                <template v-for="job in $store.jobs">
                    <li v-if="select(job)" class="job">
                        <a :href="job.url">{{ job.title }} ({{ company(job) }} | {{ job.id }})</a>
                        <div>{{ job.created.slice(5, 10) }}</div>
                        <input name="z" type="range" v-model="job.z" min="-10" max="10" />
                        <input name="z" type="text" v-model="job.z" />
                        <button type="submit" @click.prevent="act_save(job)">save</button>
                        <textarea name="content" v-model="job.content" rows="5"></textarea>
                    </li>
                </template>
            </ol>
        </div>
    </template>

    <!-- import map -->
    <script type="importmap">
        {
            "imports": {
                "vue": "/assets/vue-esm-prod-334.js",
                "element-plus": "/assets/element-plus/index-min.mjs",
                "xp-admin-login": "/assets/xp-admin-login.js",
                "xp-admin-jobs": "/assets/xp-admin-jobs.js",
                "vp-admin": "/assets/vp-admin.js",
                "vp-app": "/assets/vp-app.js",
                "vp-common": "/assets/vp-common.js"
            }
        }
    </script>
    <script type="module">
        import {
            createApp,
            defineAsyncComponent,
        } from 'vue';
        import vp_admin from 'vp-admin';

        // import xp_admin_login from 'xp-admin-login';

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
        })
        app.use(vp_admin);
        app.mount('#app');
    </script>
</body>

</html>