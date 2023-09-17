console.log('block-common.js', import.meta.url);

let vue = await import('/assets/vue-esm-prod-334.js');

// append div.xps-debug to #wpadminbar
let wpadminbar = document.querySelector('#wpadminbar');
let xpsDebug = document.createElement('div');
xpsDebug.classList.add('xps-debug');
wpadminbar.appendChild(xpsDebug);
// get div.xps-debug
let xpsDebugDiv = document.querySelector('div.xps-debug');

// try request Animation Frame 
// https://developer.mozilla.org/en-US/docs/Web/API/window/requestAnimationFrame
let fps = 30;
let counter = 0;
// compute fps
let t0 = performance.now();

// let animation =  setInterval(() => {
//     requestAnimationFrame(() => {
//         let t1 = performance.now();
//         fps = Math.round(1000 * counter / (t1 - t0));
//         // console.log('requestAnimationFrame', counter++);
//         xpsDebugDiv.innerHTML = fps + ' fps | frame: ' + (counter++);
//     });
// }, 1000 / fps);
