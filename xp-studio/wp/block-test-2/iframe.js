console.log('iframe.js');

// iframe has its own vue independent of the parent
let vue = await import ("/assets/vue-esm-prod-334.js");
// iframe has its own store independent of the parent
let { store, p_store } = await import ("/assets/xp-store.js");

// let store = vue.reactive({
//     counter: 0,
//     msg: 'Hello Store (from vue).',
// });

// define a custom element
let XpVueElement = vue.defineCustomElement({
    template: `
    <div>
        <p>Vue Element</p>
        <p>{{ msg }}</p>
        <input v-model="msg" />
        <p>{{ counter }}</p>
        <input v-model="counter" />
        <button @click="counter++">{{ counter }}</button>
        <hr/>
        <input v-model="store.msg" />
        <p>{{ store().counter }}</p>
        <input v-model="store().counter" />
        <button @click="store().counter++">{{ store().counter }}</button>
        <hr/>
        <p>{{ p_store.counter }}</p>
        <input v-model="p_store.counter" />
        <button @click="p_store.counter++">{{ p_store.counter }}</button>
        <hr/>
        <div :title="store().update"></div>
    </div>
    `,
    data () {
        return {
            msg: 'Hello World (from vue).',
            counter: 0,
        }
    },
    computed: {
        p_store() {
            return p_store;
        },
    },
    methods: {
        store() {
            console.log('store', parent.xp_store);
            // WARNING: INFINITE LOOP
            // try to trigger update in local js
            // store.counter++;
            // this.counter++;
            // return store.xp_store;
            // return vue.reactive(parent.xp_store);
            return store;
        }
    },
    created() {
        console.log('created');
        // store in store
        store.xp_store = parent.xp_store;
    },
    watch: {
    }
});
console.log('defineCustomElement', XpVueElement);
customElements.define('xps-ve', XpVueElement);

// make store global for iframe to access via parent
window.iframe_store = store;
globalThis.iframe_store = store;