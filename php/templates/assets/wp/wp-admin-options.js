console.log('wp-admin-options.js');

export default {
    template: `
    <div>
        <h2>OPTIONS</h2>
        <hr />
        <input v-model="api_nonce" type="text" name="api_nonce" placeholder="nonce" />
        <input v-model="$store.api_user" type="text" name="api_user" placeholder="user" />
        <input v-model="$store.api_password" type="password" name="api_password" placeholder="application password" autocomplete="new-password" />
        <hr />
        <em>config.php</em>
        <hr />
        <textarea v-model="$store.config_code" class="w100" rows="10" name="config_code"></textarea>
        <el-button type="primary" @click.prevent="act_save">Save</el-button>
    </div>
    `,
    methods: {
        async act_save() {
            let fd = new FormData();
            fd.append('action', 'save_config');
            fd.append('config_code', this.$store.config_code);
            let json = await this.$send_fetch(fd);
            console.log(json);
        }
    },
};