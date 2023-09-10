console.log('iframe.js');

let vue = await import ("/assets/vue-esm-prod-334.js");

let store = vue.reactive({
    counter: 0,
});

// define a custom element
let XpVueElement = vue.defineCustomElement({
    template: `
    <div>
        <p>Vue Element</p>
        <p>{{ msg }}</p>
        <p>{{ counter }}</p>
        <input v-model="counter" />
        <button @click="counter++">{{ counter }}</button>
        <hr/>
        <p>{{ st_counter }}</p>
        <input v-model="st_counter" />
        <button @click="st_counter++">{{ st_counter }}</button>
    </div>
    `,
    computed: {
        st_counter: {
            get() {
                return store.counter
            },
            set(value) {
                store.counter = value;
            }
        }
    },
    data () {
        return {
            msg: 'Hello World (from vue).',
            counter: 0,
        }
    }
});
console.log('defineCustomElement', XpVueElement);
customElements.define('xps-ve', XpVueElement);
