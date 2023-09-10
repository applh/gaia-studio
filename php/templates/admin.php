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
                    <xp-admin-menu></xp-admin-menu>

                </el-aside>
                <el-container>
                    <el-header>
                        <h1>Admin Area ({{ $store.page }}) ({{ $store.formLogin.email }})</h1>
                        <xp-test></xp-test>
                    </el-header>
                    <el-main>
                        <hr>
                        <xp-admin-login v-if="$store.page == 'login'"></xp-admin-login>
                        <xp-admin-crud v-if="$store.page == 'crud'"></xp-admin-crud>
                        <xp-admin-jobs-v2 v-if="$store.page == 'jobs-v2'"></xp-admin-jobs-v2>
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

    <template id="template-xp-admin-jobs-v2">
        <div>
            <h1>xp-admin-jobs-v2</h1>
            <hr />
            <el-tree :data="tree_data" show-checkbox draggable></el-tree>
            <hr />
            <p>Jobs (total: {{ $store.jobs.length }})</p>
            <span>table height: {{ table_height }}</span>
            <input type="range" v-model="table_height" min="0" max="2000" />
            <el-table :data="table_data"
                :default-sort="{ prop: 'z', order: 'descending' }"
                style="width: 100%" :height="table_height"
            >
                <el-table-column prop="created" label="created" :formatter="filter_date" sortable width="120"></el-table-column>
                <el-table-column prop="z" label="z" width="80" sortable></el-table-column>
                <el-table-column prop="content" label="content" width="640" sortable>
                    <template #default="{ row }">
                        <a :href="row.url" target="_blank"><h3>{{ row.title }}</h3></a>
                        <a :href="row.url" target="_blank">{{ row.url.slice(47) }}</a><br/>
                        <form @submit.prevent="act_save($event, row)">
                            <el-text v-if="row.z != null" class="mx-1" type="primary">( z={{ row.z }} )</el-text>
                            <el-text v-else class="mx-1" type="warning">( z={{ row.z }} )</el-text>
                            <input name="z" type="number" :value="row.z ?? 0" />
                            <button type="submit">save (id: {{ row.id }})</button>
                            <textarea name="content" v-model="row.content" rows="5"></textarea>
                        </form>
                    </template>
                </el-table-column>
                <el-table-column prop="id" label="id" width="80" sortable></el-table-column>
                <el-table-column prop="title" label="title" width="640" sortable>
                    <template #default="{ row }">
                        <a :href="row.url" target="_blank"><h3>{{ row.title }}</h3></a>
                    </template>
                </el-table-column>
                <el-table-column prop="url" label="url" width="640" sortable>
                    <template #default="{ row }">
                        <a :href="row.url" target="_blank">{{ row.url.slice(47) }}</a>
                    </template>
                </el-table-column>
            </el-table>
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

    <template id="template-xp-admin-crud">
        <h1>template-xp-admin-crud</h1>
    </template>

    <template id="template-xp-admin-menu">
        <h1><a href="">Welcome</a></h1>
        <el-menu :default-openeds="[]">
            <el-menu-item @click="$store.page = 'login'">login</el-menu-item>
            <el-menu-item @click="$store.page = 'crud'">crud</el-menu-item>
            <el-menu-item @click="$store.page = 'jobs-v2'">jobs v2</el-menu-item>
            <el-sub-menu index="1">
                <template #title>
                    <el-icon>
                        <message />
                    </el-icon>Navigator One
                </template>
                <el-menu-item-group>
                    <template #title>Group 1</template>
                    <el-menu-item index="1-1" @click="$store.page = 'login'">login</el-menu-item>
                    <el-menu-item index="1-2" @click="$store.page = 'jobs-v2'">jobs v2</el-menu-item>
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
    </template>
    <!-- import map -->
    <script type="importmap">
        {
            "imports": {
                "vue": "/assets/vue-esm-prod-334.js",
                "element-plus": "/assets/element-plus/index-min.mjs",
                "xp-admin-menu": "/assets/xp-admin-menu.js",
                "xp-admin-crud": "/assets/xp-admin-crud.js",
                "xp-admin-login": "/assets/xp-admin-login.js",
                "xp-admin-jobs-v2": "/assets/xp-admin-jobs-v2.js",
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