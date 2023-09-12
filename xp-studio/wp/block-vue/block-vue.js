console.log('block-vue/block-vue.js');
// will be called once in editor : parent

// load vue
let vue = await import("/assets/vue-esm-prod-334.js");
console.log('vue', vue);
// make vue accessible from parent
window.xps_vue = vue;

// create store
let xps_store = vue.reactive({
    counter: 0
});
// make store accessible from parent
window.xps_store = xps_store;

// called once in parent
console.log('parent', window.parent);
// search for iframe[name=editor-canvas]
let iframe = document.querySelector('iframe[name=editor-canvas]');
console.log('iframe', iframe);

if (iframe) {
    // inject iframe.js inside iframe
    let script = document.createElement('script');
    script.src = import.meta.url.replace('block-vue.js', 'iframe.js');
    script.type = 'module';
    console.log('script', script.src);
    iframe.contentDocument.head.appendChild(script);
}

let XceControl2 = vue.defineCustomElement({
    template:
        `<div>
        <p>XceControl2</p>
        <p>{{ cs.counter }}</p>
        <button @click="refresh(cs.counter++)">{{ cs.counter }}</button>
        <input @keyup="refresh()" v-model="cs.counter" />
        <hr/>
        <time-formatted datetime="2019-12-01"
        year="numeric" month="long" day="numeric"
        hour="numeric" minute="numeric" second="numeric"
        time-zone-name="short"
      ></time-formatted>      
    </div>`,
    computed: {
        cs() {
            return xps_store;
        }
    },
    methods: {
        // hack: use only once in building html template 
        // to avoid duplicate sync
        store() {
            // call function iframe_sync from iframe
            // if (iframe) iframe.contentWindow.iframe_sync();

            return xps_store;
        },
        refresh() {
            if (iframe) iframe.contentWindow.iframe_sync();

        },
    },
});
console.log('defineCustomElement', XceControl2);
customElements.define('xce-control-2', XceControl2);


window.defineCE1 = function (wind, methods, computed) {
    // define custom element xps_ce_control_1
    // FIXME: ERROR WHEN USING PARENT vue ??
    let XceControl1 = vue.defineCustomElement({
        template:
            `<div>
        <p>Vue Element</p>
        <p :title="i_store.update">{{ ps.counter }}</p>
        <button @click="refresh(ps.counter++)">{{ ps.counter }}</button>
        <input @keyup="refresh()" v-model="ps.counter" />
        <hr/>
        <div>i_store: <button @click="i_store.counter++">{{ i_store.counter }}</button></div>
        <div>
            <span>compo: {{ counter }}</span>
            <button @click="counter++">{{ counter }}</button>
        </div>
    </div>`,
        data: () => {
            return {
                counter: 0,
            }
        },
        methods,
        computed,
    });
    console.log('defineCustomElement', XceControl1);
    wind.customElements.define('xce-control-1', XceControl1);

}

window.defineCEtime = function (wind, methods = null, computed = null) {
    // https://javascript.info/custom-elements
    // hack: wind can be the window of iframe
    class TimeFormatted extends wind.HTMLElement {

        connectedCallback() {
            console.log('connectedCallback');
            let date = new Date(this.getAttribute('datetime') || Date.now());

            this.innerHTML = new Intl.DateTimeFormat("default", {
                year: this.getAttribute('year') || undefined,
                month: this.getAttribute('month') || undefined,
                day: this.getAttribute('day') || undefined,
                hour: this.getAttribute('hour') || undefined,
                minute: this.getAttribute('minute') || undefined,
                second: this.getAttribute('second') || undefined,
                timeZoneName: this.getAttribute('time-zone-name') || undefined,
            }).format(date);
        }

    }

    wind.customElements.define("time-formatted", TimeFormatted);
}

defineCEtime(window);

window.defineCEtime2 = function (wind, methods = null, computed = null) {
    // https://javascript.info/custom-elements
    // hack: wind can be the window of iframe
    class TimeFormatted extends wind.HTMLElement {

        render() { // (1)
            let date = new Date(this.getAttribute('datetime') || Date.now());
        
            this.innerHTML = new Intl.DateTimeFormat("default", {
              year: this.getAttribute('year') || undefined,
              month: this.getAttribute('month') || undefined,
              day: this.getAttribute('day') || undefined,
              hour: this.getAttribute('hour') || undefined,
              minute: this.getAttribute('minute') || undefined,
              second: this.getAttribute('second') || undefined,
              timeZoneName: this.getAttribute('time-zone-name') || undefined,
            }).format(date)
            + '<div>' + (this.getAttribute('counter') || '?') + '</div>'
            + '<div>xps_store: ' + xps_store.counter + '</div>'
            ;
          }
        
          connectedCallback() { // (2)
            if (!this.rendered) {
              this.render();
              this.rendered = true;
            }
          }
        
          static get observedAttributes() { // (3)
            return ['datetime', 'year', 'month', 'day', 'hour', 'minute', 'second', 'time-zone-name', 'counter'];
          }
        
          attributeChangedCallback(name, oldValue, newValue) { // (4)
            this.render();
          }
        
    }

    wind.customElements.define("time-formatted-2", TimeFormatted);
}

defineCEtime2(window);