// scss
import "./styles/app.scss";

// Stimulus
import "./bootstrap";

import "@popperjs/core";

// Bootstrap
import "bootstrap";

import Toast from 'bootstrap/js/dist/toast';

// Toasts init
let toasts = document.getElementsByClassName('toast');
for (let toastElm of toasts) {
    let toast = new Toast(toastElm);
    toast.show();
}
