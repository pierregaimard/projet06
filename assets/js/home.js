import "../styles/home.scss";
import * as List from "list.js";
import {TrickDeleteListener} from './TrickDeleteListener';

// Display tricks button
let button = document.getElementById("app-tricks-button");
let tricksSection = document.getElementById("app-tricks-section");
button.addEventListener("click", function () {
    tricksSection.style.display = "block";
});

// Load more tricks feature
let tricksDiv = document.getElementById("app-tricks");
let loadMore = document.getElementById("load-more-tricks");
let up = document.getElementById("up");
let options = {
    valueNames: [ "category", "name" ]
};
let tricksList = new List("app-tricks-container", options);
const nbCardsToLoad = 4;

up.style.display = "none";

loadMore.addEventListener("click", function () {
    let formData = new FormData();
    formData.append("offset", loadMore.dataset.offset);
    formData.append("limit", String(nbCardsToLoad));
    fetch(loadMore.dataset.route, {
        method: "POST",
        body: formData
    })
        .then((response) => response.json())
        .then((result) => {
            for (let card of result.data) {
                tricksDiv.innerHTML += card;
            }
            TrickDeleteListener();
            let offset = String(Number(loadMore.dataset.offset) + nbCardsToLoad);
            loadMore.setAttribute("data-offset", offset);

            if (result.end === true) {
                loadMore.style.display = "none";
            }
            if (offset > 8) {
                up.style.display = "block";
            }
            tricksList = new List("app-tricks-container", options);
            tricksSection.scrollIntoView(false);
        });
});

TrickDeleteListener();