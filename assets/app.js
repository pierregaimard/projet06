// scss
import "./styles/app.scss";

// Stimulus
import "./bootstrap";

import "@popperjs/core";

// Bootstrap
import "bootstrap";

import Toast from "bootstrap/js/dist/toast";

// Toasts init
let toasts = document.getElementsByClassName("toast");
for (let toastElm of toasts) {
    let toast = new Toast(toastElm);
    toast.show();
}

// Form management
let form = document.getElementById("app-form");

form.addEventListener("submit", function (event) {
    if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
    }

    form.classList.add("was-validated");
}, false);
