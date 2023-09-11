console.log('app-vue.js');

let vue = await import ("/assets/vue-esm-prod-334.js");
console.log('vue', vue);

// get import meta url
let url = import.meta.url;
// remove filename
url = url.substring(0, url.lastIndexOf('/') + 1);
console.log('url', url);

// find iframe.editor-canvas
let iframe = document.querySelector('iframe[name=editor-canvas]');
console.log('iframe', iframe);
// add a new script tag to the iframe
let script = document.createElement('script');
script.src = url + '/iframe.js';
script.type = 'module';
console.log('script', script.src);
iframe.contentDocument.head.appendChild(script);

let XpVueElement = vue.defineCustomElement({
    template: '<p>Vue Element</p>',
});
console.log('defineCustomElement', XpVueElement);
customElements.define('xps-ve', XpVueElement);

let { store } = await import ("/assets/xp-store.js");


// vue app
let app = vue.createApp({
    template: `
        <div>
            <p>Vue App</p>
            <p>{{ msg }}</p>
            <p>{{ counter }}</p>
            <input v-model="counter" />
            <button @click="counter++">{{ counter }}</button>
            <hr/>
            <input v-model="store.msg" />
            <p>{{ store.counter }}</p>
            <input v-model="store.counter" />
            <button @click="store.counter++">{{ store.counter }}</button>
            <hr/>
            <input v-model="synchro().counter" />
            <hr/>
            <Teleport to=".to1">
                <p>SPOCK</p>
            </Teleport>
            <hr/>
        </div>
    `,
    data() {
        return {
            msg: 'Hello World (from vue).',
            counter: 0,
        }
    },
    computed: {
        store() {
            return store;
        }
    },
    methods: {
        synchro(){
            // check if global has iframe_store
            if (iframe) {
                console.log('iframe.contentWindow.iframe_store', iframe.contentWindow.iframe_store);
                if (iframe.contentWindow.iframe_store) {
                    // change update
                    iframe.contentWindow.iframe_store.update++;
                }
                // console.log('iframe_store', globalThis.iframe_store);
            }

            return store;
        },
    },
});
// append div#vue-app after h1
// let h1 = document.querySelector('div[role=toolbar]');
let anchor = document.querySelector('.components-panel');
let div = document.createElement('div');
div.id = 'vue-app';
anchor.after(div);
// let xve = new XpVueElement();
let xve = document.createElement('xps-ve');
anchor.after(xve);
// mount vue app
app.mount('#vue-app');
