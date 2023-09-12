console.log('block-vue/script.js');
// called twice in editor : parent and iframe
// called once in frontend
console.log('parent', parent);


async function init() {
    // import ./block-vue.js
    let block_vue = await import("./block-vue.js");

    // search for iframe[role=editor-canvas]
    let iframe = document.querySelector('iframe');
    console.log('iframe', iframe);
    console.log('wp', wp);

}

init();