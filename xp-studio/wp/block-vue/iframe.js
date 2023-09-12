console.log('iframe.js');

// not working ?? vue is linked to parent js context ??
// retrieve vue from parent
// let vue = window.parent.xps_vue;

let vue = await import("/assets/vue-esm-prod-334.js");
console.log('vue from parent', vue);

// retrieve store from parent
let store = window.parent.xps_store;
console.log('store from parent', store);

let i_store = vue.reactive({
    counter: 0,
    // hack: use update in template to force refresh
    update: 0,
});
// make store accessible from parent
window.iframe_sync = function () {
    i_store.update++;
    console.log('iframe_sync', i_store);
}

let methods = {
    refresh() {
        // console.log('refresh');
        // only update the current component
        // this.$forceUpdate();
        i_store.update++;
        // this.$refs.titi.setAttribute('datetime', new Date());
    },
};

let computed = {
    i_store() {
        return i_store;
    },
    ps() {
        return store;
    },
};

function defineCE1 (methods, computed) {
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
        <hr/>
        <time-formatted-2 ref="titi" :counter="ps.counter" datetime="2019-12-01"
        year="numeric" month="long" day="numeric"
        hour="numeric" minute="numeric" second="numeric"
        time-zone-name="short"
      ></time-formatted-2> 
        <hr/>
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
    customElements.define('xce-control-1', XceControl1);

}

defineCE1(methods, computed);

// call parent function defineCE1 if exists
// if (window.parent.defineCE1) {
//     console.log('call parent defineCE1');
//     window.parent.defineCE1(window, methods, computed);
// }

// call parent function defineCEtime if exists
if (window.parent.defineCEtime) {
    console.log('call parent defineCEtime');
    window.parent.defineCEtime(window, methods, computed);
}

if (window.parent.defineCEtime2) {
    console.log('call parent defineCEtime2');
    window.parent.defineCEtime2(window, methods, computed);
}