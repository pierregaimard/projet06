import "../../styles/trick/add.scss";
import {TrickAdd} from "./TrickAdd";

let imageContainer = document.getElementById("trick_images");
let videoContainer = document.getElementById("trick_videos");
let addImageButton = document.getElementById("add-trick-image");
let addVideoButton = document.getElementById("add-trick-video");
let imageCount = imageContainer.querySelectorAll("input").length;
let videoCount = videoContainer.querySelectorAll("input").length;

// Remove item event listener
let collection = document.querySelectorAll(".elm-collection");
collection.forEach((element) => {
    let button = element.childNodes[3];
    button.addEventListener("click", () => {
        element.remove();
    });
});

// First image field initialization
if (imageCount === 0) {
    imageCount++;
    let field = TrickAdd.getField(imageCount, TrickAdd.typeImage, true);
    imageContainer.append(field);
}

// First video field initialization
if (videoCount === 0) {
    videoCount++;
    let field = TrickAdd.getField(videoCount, TrickAdd.typeVideo, true);
    videoContainer.append(field);
}

// Add Image event listener
addImageButton.addEventListener("click", () => {
    imageCount++;
    let field = TrickAdd.getField(imageCount, TrickAdd.typeImage, true);
    imageContainer.append(field);
});

// Add Video event listener
addVideoButton.addEventListener("click", () => {
    videoCount++;
    let field = TrickAdd.getField(videoCount, TrickAdd.typeVideo, true);
    videoContainer.append(field);
});

// Unique trick name
let trickName = document.getElementById("trick_name");
let message = TrickAdd.getValidationTemplate("This name already exists.");
trickName.addEventListener("keyup", () => {
    let form = new FormData();
    let route = document.getElementById("trick_name_check_route").dataset.route;
    form.append("name", trickName.value);
    fetch(route, {
        method: "POST",
        body: form
    })
        .then((response) => response.json())
        .then((result) => {
            trickName.parentNode.appendChild(message);
            if (result.unique === false) {
                trickName.classList.add("is-invalid");
                message.style.display = "block";
                trickName.setAttribute("pattern", `^(?!${result.value})$`);
            }
            if (result.unique === true) {
                trickName.classList.remove("is-invalid");
                trickName.setAttribute("pattern", "^[a-zA-Z0-9]+([ -]?[a-zA-Z0-9])*$");
                message.style.display = "none";
            }
        });
});
