console.log('LOADING... xp-store.js');

export let store = null;
let parent_store = null;

// check if parent window is available
if (window.parent) {
     console.log('parent', window.parent);
     // check if parent has xp_store
    if (window.parent.xp_store) {
        console.log('parent.xp_store', window.parent.xp_store);
        parent_store = window.parent.xp_store;
    }
    else {
        console.log('no parent.xp_store');
    }
}
else {
    console.log('no parent');
}


let vue = await import ("/assets/vue-esm-prod-334.js");

// let proxy_src = vue.reactive({
//     counter: 0,
//     msg: 'Hello Store (from vue).',
// });
let proxy_src = {
    counter: 0,
    msg: 'Hello Store (from vue).',
}



store = vue.reactive({
    update:0,
    counter: 0,
    msg: 'Hello Store (from vue).',
});

// store = vue.reactive(proxy_src);

export let p_store = new Proxy(proxy_src, {
    get: function(target, prop, receiver) {
        console.log('get', target, prop, receiver);
        console.log('target', target);
        // return Reflect.get(...arguments);
        // return store[prop];
        // sync local with parent
        store[prop] = parent_store[prop];
        return parent_store[prop];
    },
    set: function(target, prop, value, receiver) {
        console.log('set', target, prop, value, receiver);
        console.log('target', target);
        // return Reflect.set(...arguments);
        // set parent_store prop
        parent_store[prop] = value;
        // duplicate to local store
        store[prop] = value;
        return parent_store[prop];
    }
});

if (!parent_store) {
    // make it global for iframe to access via parent
    globalThis.xp_store = store;
}
else {
    // add iframe store to global
    globalThis.iframe_store = p_store;
}

// export store

export default { store, p_store };
