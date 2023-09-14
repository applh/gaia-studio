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
    <h1>XP Studio</h1>
    <hr />
    <input v-model="api_nonce" type="text" placeholder="nonce" />
    <input v-model="api_user" type="text" placeholder="user" />
    <input v-model="api_password" type="password" placeholder="application password" />
    <el-button type="success" @click.prevent="act_code_refresh">Refresh</el-button>
    <hr />
    <hr />
    <el-row>
        <el-col :span="14">
            <el-table :data="tree_data" row-key="id" :tree-props="{ children: 'children' }" :default-sort="{ prop: 'id', order: 'descending' }">
                <el-table-column prop="id" label="ID" sortable width="120"></el-table-column>
                <el-table-column prop="label" label="name" sortable width="120">
                    <template #default="{ row }">
                        <b>{{ row.name}}</b>
                        <hr/>
                        <div>{{ row.label }}</div>
                        <hr/>
                        <el-button type="primary" @click="act_code_update(row)">EDIT</el-button>
                    </template>
                </el-table-column>
                <el-table-column prop="code" label="code" sortable width="640">
                    <template #default="{ row }">
                        <textarea v-model="row.code" rows="10"></textarea>
                    </template>
                </el-table-column>
                <el-table-column prop="id" label="update" sortable width="120">
                    <template #default="{ row }">
                        <el-button type="primary" @click="act_code_update(row)">EDIT</el-button>
                        <hr/>
                        <el-button type="danger" @click="act_code_delete(row)">DELETE</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </el-col>
        <el-col :span="5">
            <em>Add a now code</em>
            <hr />
            <el-form :inline="false" :model="formInline" class="demo-form-inline">
                <el-form-item label="title">
                    <el-input v-model="formInline.label" placeholder="title" clearable />
                </el-form-item>
                <el-form-item label="name">
                    <el-input v-model="formInline.name" placeholder="name" clearable />
                </el-form-item>
                <el-form-item label="code">
                    <el-input v-model="formInline.code" type="textarea" :autosize="{ minRows: 5 }" placeholder="code" clearable />
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click.prevent="act_code_create">SAVE</el-button>
                </el-form-item>
            </el-form>
            <el-tree :data="tree_data" show-checkbox draggable></el-tree>
        </el-col>
        <el-col :span="5">
            <el-calendar v-model="cal_date"></el-calendar>
            <el-tree :data="tree_data" show-checkbox draggable></el-tree>
        </el-col>
    </el-row>
    <hr />
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
    import {
        createApp
    } from 'vue';
    import ElementPlus from 'element-plus';

    let data = {
        api_nonce: "<?php echo $rest_api_nonce; ?>",
        api_user: "<?php echo $user_login; ?>",
        api_password: "",
        uri_rest_api: "<?php echo $uri_rest_api; ?>",
        message: "hello Vue",
        cal_date: new Date(),
        tree_data: [{
                id: 1,
                label: 'Level one 1',
                children: [{
                        id: 4,
                        label: 'Level two 1-1',
                    },
                    {
                        id: 5,
                        label: 'Level two 1-2',
                    },
                ]
            },
            {
                id: 2,
                label: 'Level one 2',
            },
        ],
        formInline: {
            label: '',
            name: '',
            code: '',
        }
    };


    let methods = {
        async send_fetch(fd) {
            // make POST request
            let headers = new Headers();
            // add nonce
            if (this.api_nonce) {
                headers.append('X-WP-Nonce', this.api_nonce);
            }
            if (this.api_user && this.api_password) {
                headers.append('Authorization', 'Basic ' + btoa(this.api_user + ':' + this.api_password));
            }
            let res = await fetch(this.uri_rest_api, {
                method: "POST",
                body: fd,
                // enable cors and credentials
                mode: "cors",
                credentials: "include",
                headers,
            });
            let json = await res.json();
            console.log(json);
            return json;
        },
        async load_data() {
            let fd = new FormData();
            fd.append("@class", "code");
            fd.append("@method", "load_data");
            return await this.send_fetch(fd);
        },
        act_code_delete: async function(row) {
            console.log("act_code_delete", row);
            // make POST request
            let fd = new FormData();
            fd.append("@class", "code");
            fd.append("@method", "delete");
            fd.append("id", row.id);
            let json = await this.send_fetch(fd);
            // if json.code_load_data is defined then copy to tree_data
            if (json.code_delete) {
                this.tree_data = json.code_delete;
            }
        },
        act_code_update: async function(row) {
            // copy row to formInline
            this.formInline = Object.assign({}, row);
        },
        act_code_create: async function(event) {
            console.log("act_code_create", event);
            // build form data from formInline
            let fd = new FormData();
            fd.append("@class", "code");
            fd.append("@method", "create");
            // warning: label is used by element-plus for trees
            fd.append("title", this.formInline.label);
            fd.append("name", this.formInline.name);
            // add code as file named code.php
            let blob = new Blob([this.formInline.code], {
                type: "text/plain"
            });
            fd.append("code", blob, "code.php");
            // make POST request with cors enabled and credentials
            let json = await this.send_fetch(fd);
            // if json.code_load_data is defined then copy to tree_data
            if (json.code_create) {
                this.tree_data = json.code_create;
            }

        },
        act_code_refresh: async function() {
            // load code from api
            let json = await this.load_data();
            // if json.code_load_data is defined then copy to tree_data
            if (json.code_load_data) {
                this.tree_data = json.code_load_data;
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

            // load code from api
            let json = await this.load_data();
            // if json.code_load_data is defined then copy to tree_data
            if (json.code_load_data) {
                this.tree_data = json.code_load_data;
            }
        },
        methods,
    });
    app.use(ElementPlus)
    // mount the app
    app.mount("#app");
</script>
<style>
    /* OVERRIDE WP CSS */
    input[type="text"] {
        border: none;
        box-shadow: none;
    }

    td textarea {
        width: 100%;
        height: 100%;
    }
</style>