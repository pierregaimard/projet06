import "../styles/home.scss";

let button = document.getElementById("app-tricks-button");
let tricksSection = document.getElementById("app-tricks-section");

button.addEventListener("click", function () {
    tricksSection.style.display = "block";
});