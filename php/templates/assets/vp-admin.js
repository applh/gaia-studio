import common from 'vp-common';

import ElementPlus from 'element-plus';

import { reactive, defineAsyncComponent } from 'vue';

let store = reactive({
    page: 'login',
    formLogin: {
        email: '',
        password: '',
    },
    jobs: [],
});

// https://vuejs.org/guide/reusability/plugins.html
export default {
    install: (app, options) => {
        console.log('vp-admin.js');
        app.use(ElementPlus);

        // add store
        app.config.globalProperties.$store = store;

        // FIXME: not working ?!
        // register components
        let components = [
            // 'xp-admin-login',
        ];
        components.forEach(async function (element) {
            console.log('defining component', element);
            let compo = await import(element);
            app.component(element, compo.default);
        });

        // register async components
        // NOTE: don't forget to add the name in the JSON import
        let compos = [ 
            'xp-admin-login',
            'xp-admin-jobs',
        ];
        
        compos.forEach(element => {
            console.log('defining async component', element);
            app.component(element, defineAsyncComponent(() =>
                import(element)
            ));
        });

    }
}