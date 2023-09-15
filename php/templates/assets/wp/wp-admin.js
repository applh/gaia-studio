console.log('wp-admin-options.js');

import { reactive, defineAsyncComponent } from 'vue';


// common store
const store = reactive({
    async_components: [],
    api_nonce: '',
    api_user: '',
    api_password: '',
    config_code: '',
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
});

let send_fetch = async function (fd) {
    // make POST request
    let headers = new Headers();
    // add nonce
    if (store.api_nonce) {
        headers.append('X-WP-Nonce', store.api_nonce);
    }

    if (store.api_user && store.api_password) {
        let b64 = btoa(store.api_user + ':' + store.api_password);
        headers.append('Authorization', 'Basic ' + b64);
    }

    let res = await fetch(store.api_rest_uri, {
        method: "POST",
        body: fd,
        headers,
    });
    let json = await res.json();
    console.log(json);
    return json;
}


// vue plugin declaration
export default {
    store, // hack to make store available

    install(app, options) {
        console.log('wp-admin-options.js install');
        // register async components
        store.async_components.forEach((component) => {
            console.log('registering', component);
            app.component(component, defineAsyncComponent(
                () => import(component))
            );
        });

        // add store to app
        app.config.globalProperties.$store = store;
        app.config.globalProperties.$send_fetch = send_fetch;
    }
};