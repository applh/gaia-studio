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
    <hr />
    <el-button type="success">Element Plus is ready</el-button>
    <hr />
    <el-form :inline="true" :model="formInline" class="demo-form-inline">
        <el-form-item label="Approved by">
            <el-input v-model="formInline.user" placeholder="Approved by" clearable />
        </el-form-item>
        <el-form-item label="Activity zone">
            <el-select v-model="formInline.region" placeholder="Activity zone" clearable>
                <el-option label="Zone one" value="shanghai" />
                <el-option label="Zone two" value="beijing" />
            </el-select>
        </el-form-item>
        <el-form-item label="Activity time">
            <el-date-picker v-model="formInline.date" type="date" placeholder="Pick a date" clearable />
        </el-form-item>
        <el-form-item>
            <el-button type="primary" @click="onSubmit">Query</el-button>
        </el-form-item>
    </el-form>
    <hr />
    <el-tree :data="tree_data" show-checkbox draggable></el-tree>
    <hr />
    <el-tree :data="tree_data" show-checkbox draggable></el-tree>
    <hr />
    <el-table :data="tree_data" row-key="id" :tree-props="{ children: 'children' }" :default-sort="{ prop: 'id', order: 'descending' }">
        <el-table-column prop="id" sortable></el-table-column>
        <el-table-column prop="label" sortable></el-table-column>
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
            user: '',
            region: '',
            date: '',
            delivery: false,
            type: [],
            resource: '',
            desc: ''
        }
    };

    // create the vue app
    const app = createApp({
        template: "#template-app",
        // copy data to instance
        data: () => Object.assign({}, data),
        created: function() {
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
<style>
    /* OVERRIDE WP CSS */
    input[type="text"] {
        border: none;
        box-shadow: none;
    }
</style>