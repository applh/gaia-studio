// this script is ative on every request ?!
// html, css, js, images, ...

const images = document.querySelectorAll('img');
// console.log('xp-gaia: img', images.length);
images.forEach(image => {
    console.log(image.src);
});

// ping remote server
let remote_host= 'http://localhost:4321';
let remote_path= '/api/chrome-ext';
let remote_url= remote_host + remote_path;
// console.log('xp-gaia: remote_url', remote_url);
let fd = new FormData();
h1 = document.querySelector('h1');
let title1 = h1 ? h1.innerText : '';

fd.append('url', window.location.href);
fd.append('title', document.title);
fd.append('title1', title1);
// NO CORS request, so no cookies
headers = new Headers();
headers.append('Access-Control-Allow-Origin', '*');
headers.append('Access-Control-Allow-Credentials', 'true');

// headers.append('Content-Type', 'application/json');
// headers.append('Accept', 'application/json');


fetch(remote_url, {
    method: 'POST',
    mode: 'cors',
    headers: headers,
    body: fd,        
})
.then(response => response.json())
.then(data => {
    // console.log('xp-gaia: remote_data', data);
});

