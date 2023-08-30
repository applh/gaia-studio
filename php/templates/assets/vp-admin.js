import common from 'vp-common';

import ElementPlus from 'element-plus';

export default {
    install: (app, options) => {
        console.log('vp-admin.js');
        app.use(ElementPlus);
    }
}