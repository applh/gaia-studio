<?php
// security 
if (!is_callable('add_action')) {
    die();
}

$uri_rest_api = xp_studio::$uri_rest_api;


?>
<div id="app"></div>

<template id="template-app">
    <h1>XP Studio</h1>
    <hr/>
    <em>Add a now code</em>
    <hr/>
    <el-form :inline="false" :model="formInline" class="demo-form-inline">
        <el-form-item label="title">
            <el-input v-model="formInline.title" placeholder="title" clearable />
        </el-form-item>
        <el-form-item label="name">
            <el-input v-model="formInline.name" placeholder="name" clearable />
        </el-form-item>
        <el-form-item label="code">
            <el-input v-model="formInline.code" type="textarea" autosize placeholder="code" clearable />
        </el-form-item>
        <el-form-item>
            <el-button type="primary" @click.prevent="act_code_create">CREATE</el-button>
        </el-form-item>
    </el-form>
    <hr />
    <el-tree :data="tree_data" show-checkbox draggable></el-tree>
    <hr />
    <el-tree :data="tree_data" show-checkbox draggable></el-tree>
    <hr />
    <el-table :data="tree_data" row-key="id" :tree-props="{ children: 'children' }" :default-sort="{ prop: 'id', order: 'descending' }">
        <el-table-column prop="id" label="ID" sortable width="120"></el-table-column>
        <el-table-column prop="label" label="title" sortable width="240"></el-table-column>
        <el-table-column prop="name" label="name" sortable width="240"></el-table-column>
        <el-table-column prop="code" label="code" sortable width="640">
            <template #default="{ row }">
                <textarea v-model="row.code" rows="10"></textarea>
            </template>
        </el-table-column>
        <el-table-column prop="id" label="delete" sortable width="120">
            <template #default="{ row }">
                <el-button type="danger" @click="act_code_delete(row)">DELETE</el-button>
            </template>
        </el-table-column>
    </el-table>
    <hr />
    <el-calendar v-model="cal_date" />
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
            title: '',
            name: '',
            code: '',
        }
    };

    async function load_data() {
        // load data from api
        let url = data.uri_rest_api;
        //  + "?@class=code&@method=load_data";

        // make POST request
        let fd = new FormData();
        fd.append("@class", "code");
        fd.append("@method", "load_data");
        let res = await fetch(url, {
            method: "POST",
            body: fd,
        });
        let json = await res.json();
        console.log(json);
        return json;
    }

    let methods = {
        act_code_delete: async function(row) {
            console.log("act_code_delete", row);
            // make POST request
            let fd = new FormData();
            fd.append("@class", "code");
            fd.append("@method", "delete");
            fd.append("id", row.id);
            let res = await fetch(this.uri_rest_api, {
                method: "POST",
                body: fd,
            });
            let json = await res.json();
            console.log(json);
            // if json.code_load_data is defined then copy to tree_data
            if (json.code_delete) {
                this.tree_data = json.code_delete;
            }
        },
        act_code_create: async function(event) {
            console.log("act_code_create", event);
            // build form data from formInline
            let fd = new FormData();
            fd.append("@class", "code");
            fd.append("@method", "create");
            fd.append("title", this.formInline.title);
            fd.append("name", this.formInline.name);
            // add code as file named code.php
            let blob = new Blob([this.formInline.code], {
                type: "text/plain"
            });
            fd.append("code", blob, "code.php");
            // make POST request with cors enabled and credentials
            let res = await fetch(this.uri_rest_api, {
                method: "POST",
                body: fd,
                mode: "cors",
                credentials: "include",
            });
            let json = await res.json();
            console.log(json);
            // if json.code_load_data is defined then copy to tree_data
            if (json.code_create) {
                this.tree_data = json.code_create;
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
            let json = await load_data();
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