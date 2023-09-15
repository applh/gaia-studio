console.log('wp-admin-options.js');

export default {
    template: `
    <div>
        <h2>OPTIONS</h2>
        <hr />
        <em>auth</em>
        <hr />
        <el-row>
            <el-col :span="12">
                <input v-model="$store.api_nonce" type="password" name="api_nonce" placeholder="nonce" />
            </el-col>
            <el-col :span="12">
                <input v-model="$store.api_user" type="text" name="api_user" placeholder="user" />
                <input v-model="$store.api_password" type="password" name="api_password" placeholder="application password" autocomplete="new-password" />
            </el-col>
        </el-row>
        <hr />
        <em>config.php</em>
        <hr />
        <textarea v-model="$store.config_code" class="w100" rows="10" name="config_code"></textarea>
        <el-button type="primary" @click.prevent="act_save">Save</el-button>
        <hr />
        <el-row>
            <el-col :span="12">
                <el-calendar v-model="$store.cal_date"></el-calendar>
            </el-col>
            <el-col :span="6">
                <el-tree :data="$store.tree_data" show-checkbox draggable></el-tree>
            </el-col>
            <el-col :span="6">
                <el-tree :data="$store.tree_data" show-checkbox draggable></el-tree>
            </el-col>
        </el-row>
</div>
    `,
    methods: {
        async act_load() {
            // load code from api
            let fd = new FormData();
            fd.append("@class", "code");
            fd.append("@method", "config_load");
            let json = await this.$send_fetch(fd);
            console.log(json);
            // copy code to store
            if (json.code_config_load ?? false) {
                this.$store.config_code = json.code_config_load;
            }
        },
        async act_save() {
            // load code from api
            let fd = new FormData();
            fd.append("@class", "code");
            fd.append("@method", "config_save");
            // send config_code as file
            let blob = new Blob([this.$store.config_code], { type: 'text/plain' });
            fd.append("config_code", blob, "config.php");

            let json = await this.$send_fetch(fd);
            console.log(json);
        }
    },
    created() {
        console.log('wp-admin-options.js created');
        this.act_load();
    }
};