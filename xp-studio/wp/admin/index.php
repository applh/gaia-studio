<?php
// security 
if (!is_callable('add_action')) {
    die();
}

$uri_rest_api = xp_studio::$uri_rest_api;
$user = wp_get_current_user();
$user_login = $user->user_login ?? "";

// rest api nonce
$rest_api_nonce = wp_create_nonce('wp_rest');

?>
<div id="app"></div>

<template id="template-app">
    <h1>XP Studio ({{ page }})</h1>
    <el-container>
        <el-aside width="60px">
            <el-menu default-active="2" class="el-menu-vertical-demo" :collapse="isCollapse" @open="handleOpen" @close="handleClose">
                <el-menu-item index="1">
                    <img class="icon" src="/assets/element-plus/icon.png" alt="" @click="act_nav('code')" />
                    <template #title>Code</template>
                </el-menu-item>
                <el-menu-item index="2">
                    <img class="icon" src="/assets/element-plus/icon.png" alt="" @click="act_nav('options')" />
                    <template #title>Options</template>
                </el-menu-item>
                <el-menu-item index="3">
                    <img class="icon" src="/assets/element-plus/icon.png" alt="" @click="act_code_refresh" />
                    <template #title>Refresh</template>
                </el-menu-item>
                <el-sub-menu index="4">
                    <template #title>
                        <img class="icon" src="/assets/element-plus/icon.png" alt="" />
                        <span>More</span>
                    </template>
                    <el-menu-item-group>
                        <template #title><span>Group One</span></template>
                        <el-menu-item index="1-1">item one</el-menu-item>
                        <el-menu-item index="1-2">item two</el-menu-item>
                    </el-menu-item-group>
                    <el-menu-item-group title="Group Two">
                        <el-menu-item index="1-3">item three</el-menu-item>
                    </el-menu-item-group>
                    <el-sub-menu index="1-4">
                        <template #title><span>item four</span></template>
                        <el-menu-item index="1-4-1">item one</el-menu-item>
                    </el-sub-menu>
                </el-sub-menu>
            </el-menu>
        </el-aside>
        <el-main v-if="page == 'options'" class="options">
            <wp-admin-options></wp-admin-options>
        </el-main>
        <el-main v-if="page == 'code'" class="code">
            <wp-admin-code></wp-admin-code>
        </el-main>
    </el-container>

</template>

<template id="template-wp-admin-code">
    <el-row width="100%">
        <el-col :span="14">
            <el-table :data="$store.tree_data" row-key="id" :tree-props="{ children: 'children' }" :default-sort="{ prop: 'id', order: 'descending' }">
                <el-table-column prop="label" label="name" sortable width="120">
                    <template #default="{ row }">
                        <b>{{ row.name}}</b>
                        <hr />
                        <div>{{ row.label }}</div>
                        <hr />
                        <el-button type="primary" @click="act_code_update(row)">EDIT</el-button>
                    </template>
                </el-table-column>
                <el-table-column prop="code" label="code" sortable width="640">
                    <template #default="{ row }">
                        <textarea v-model="row.code" name="row_code" rows="10"></textarea>
                    </template>
                </el-table-column>
                <el-table-column prop="id" label="update" sortable width="120">
                    <template #default="{ row }">
                        <el-button type="primary" @click="act_code_update(row)">EDIT</el-button>
                        <hr />
                        <el-button type="danger" @click="act_code_delete(row)">DELETE</el-button>
                    </template>
                </el-table-column>
                <el-table-column prop="id" label="ID" sortable width="120"></el-table-column>
            </el-table>
        </el-col>
        <el-col :span="8" class="bg-200 pd-1">
            <em>Add a new code</em>
            <hr />
            <el-form :inline="false" :model="$store.formInline" label-width="60px" class="demo-form-inline">
                <el-form-item label="title">
                    <el-input v-model="$store.formInline.label" name="edit_label" placeholder="title" clearable />
                </el-form-item>
                <el-form-item label="name">
                    <el-input v-model="$store.formInline.name" name="edit_name" placeholder="name" clearable />
                </el-form-item>
                <el-form-item label="code">
                    <el-input v-model="$store.formInline.code" type="textarea" name="edit_code" :autosize="{ minRows: 5, maxRows: 50 }" placeholder="code" clearable />
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click.prevent="act_code_create">SAVE</el-button>
                </el-form-item>
            </el-form>
            <hr />
            <el-tree :data="$store.tree_data" show-checkbox draggable></el-tree>
            <el-calendar v-model="$store.cal_date"></el-calendar>
            <el-tree :data="$store.tree_data" show-checkbox draggable></el-tree>
        </el-col>
    </el-row></template>

<!-- import map -->
<script type="importmap">
    {
        "imports": {
            "vue": "/assets/vue-esm-prod-334.js",
            "element-plus": "/assets/element-plus/index-min.mjs",
            "wp-admin": "/assets/wp/wp-admin.js",
            "wp-admin-code": "/assets/wp/wp-admin-code.js",
            "wp-admin-options": "/assets/wp/wp-admin-options.js"
        }
    }
</script>
<script type="module">
    import {
        createApp
    } from 'vue';

    import ElementPlus from 'element-plus';
    import wp_admin from 'wp-admin';

    // store the async components
    wp_admin.store.async_components = ["wp-admin-code", "wp-admin-options"];
    // store the api config from PHP to JS
    wp_admin.store.api_rest_uri = "<?php echo $uri_rest_api; ?>";
    wp_admin.store.api_nonce = "<?php echo $rest_api_nonce; ?>";
    wp_admin.store.api_user = "<?php echo $user_login; ?>";
    wp_admin.store.api_password = "";

    let data = {
        page: "code",
        isCollapse: true,
        message: "hello Vue",
    };

    let methods = {
        act_nav(page) {
            console.log("act_nav", page);
            this.page = page;
        },
        handleOpen(key, keyPath) {
            console.log(key, keyPath)
        },
        handleClose(key, keyPath) {
            console.log(key, keyPath)
        },
        act_code_refresh: async function() {
            // load code from api
            let fd = new FormData();
            fd.append("@class", "code");
            fd.append("@method", "load_data");
            let json = await this.$send_fetch(fd);
            // if json.code_load_data is defined then copy to tree_data
            if (json.code_load_data) {
                this.$store.tree_data = json.code_load_data;
            }
        }
    }

    // create the vue app
    const app = createApp({
        template: "#template-app",
        // copy data to instance
        data: () => Object.assign({}, data),
        created: async function() {
            // load css assets /assets/element-plus/index-min.css
            let link = document.createElement('link');
            link.rel = 'stylesheet';
            link.type = 'text/css';
            link.href = '/assets/element-plus/index-min.css';
            document.head.appendChild(link);
            // load data
            this.act_code_refresh();
        },
        methods,
    });
    app.use(ElementPlus);
    app.use(wp_admin);
    // mount the app
    app.mount("#app");
</script>
<style>
    /* OVERRIDE WP CSS */
    input[type="text"],
    input[type="text"]:focus {
        border: none;
        box-shadow: none;
    }

    td textarea {
        width: 100%;
        height: 100%;
    }

    .bg-200 {
        background-color: #ccc;
    }

    .pd-1 {
        padding: 1rem;
    }

    .icon {
        width: 16px;
        height: 16px;
    }

    .code {
        background-color: #ccc;
    }

    .w100 {
        width: 100%;
    }
</style>