// visible in the extension console when extension is activated
// console.log('xp-gaia: background.js');

chrome.runtime.onMessage.addListener((request, sender, sendResponse) => {
    // console.log('xp-gaia: background.js: onMessage', request, sender);
    if (request.type === 'xp-gaia: get-images') {
        const images = document.querySelectorAll('img');
        // console.log('xp-gaia: background.js: get-images', images.length);
        const srcs = [];
        images.forEach(image => {
            srcs.push(image.src);
        });
        sendResponse(srcs);
    }
});

let scripts_ok = {};

chrome.action.onClicked.addListener(async (tab) => {
    // console.log('xp-gaia: background.js: chrome.action.onClicked', tab);
    // check if the tab has already been injected with the script
    if (scripts_ok[tab.id]) {
        // console.log('xp-gaia: background.js: chrome.action.onClicked: already injected', tab.id);
    } else {
        chrome.scripting.executeScript({
            target: { tabId: tab.id },
        files: ['scripts/extra.js']
        })
        .then(() => {
            // console.log('xp-gaia: background.js: chrome.scripting.executeScript: success', tab.id);
            // scripts_ok[tab.id] = true;
        });
    }
});