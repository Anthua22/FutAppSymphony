'use strict';

let container;
let spinner;

document.addEventListener('DOMContentLoaded',e=>{
    container = document.getElementById('main');
    spinner = document.getElementById('spinner');

    setTimeout(()=>{
        container.classList.remove('d-none');
        spinner.classList.add('d-none');
        }, 1000);
})