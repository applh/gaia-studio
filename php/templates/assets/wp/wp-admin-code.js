console.log('loading', import.meta.url);

export default {
    template: `#template-wp-admin-code`,
    methods: {
        act_code_delete: async function(row) {
            console.log("act_code_delete", row);
            // make POST request
            let fd = new FormData();
            fd.append("@class", "code");
            fd.append("@method", "delete");
            fd.append("id", row.id);
            let json = await this.$send_fetch(fd);
            // if json.code_load_data is defined then copy to tree_data
            if (json.code_delete) {
                this.$store.tree_data = json.code_delete;
            }
        },
        act_code_update: async function(row) {
            // copy row to formInline
            this.$store.formInline = Object.assign({}, row);
        },
        act_code_create: async function(event) {
            console.log("act_code_create", event);
            // build form data from formInline
            let fd = new FormData();
            fd.append("@class", "code");
            fd.append("@method", "create");
            // warning: label is used by element-plus for trees
            fd.append("title", this.$store.formInline.label);
            fd.append("name", this.$store.formInline.name);
            // add code as file named code.php
            let blob = new Blob([this.$store.formInline.code], {
                type: "text/plain"
            });
            fd.append("code", blob, "code.php");
            // make POST request with cors enabled and credentials
            let json = await this.$send_fetch(fd);
            // if json.code_load_data is defined then copy to tree_data
            if (json.code_create) {
                this.$store.tree_data = json.code_create;
            }

        },

    },
};