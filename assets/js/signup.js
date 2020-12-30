import "../styles/signup.scss";
import "../styles/form.scss";

let form = document.getElementById("app-login");

form.addEventListener("submit", function (event) {
    if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
    }

    form.classList.add("was-validated");
}, false);
