import "../../styles/trick/view.scss";
import {TrickAdd} from "./TrickAdd";
import {TrickVideoManager} from "./TrickVideoManager";
import {MediaBlockManager} from "./MediaBlockManager";

// Remove media listener
const removeMediaValidation = document.getElementById("media-remove-submit");
const removeMediaButtons = document.querySelectorAll(".remove-media-button");
removeMediaButtons.forEach((button) => {
    button.addEventListener("click", () => {
        removeMediaValidation.setAttribute("href", button.dataset.route);
    });
});

// Add/Replace image listener
const changeImageButton = document.querySelectorAll(".change-image-button");
const idImage = document.getElementById("idImage");
const modalImageTitle = document.getElementById("trickEditImageAddModal");
const submitImage = document.getElementById("submit-image");
// Replace image
changeImageButton.forEach((element) => {
    element.addEventListener("click", () => {
        idImage.setAttribute("value", element.dataset.id);
        modalImageTitle.innerText = "Replace image";
        submitImage.innerText = "Replace this image";
    });
});

// Add new image
const addImageButton = document.getElementById("add-image-button");
addImageButton.addEventListener("click", () => {
    modalImageTitle.innerText = "Add a new image";
    submitImage.innerText = "Add new image";
});

// Add/Replace video listener
const changeVideoButton = document.querySelectorAll(".change-video-button");
const idVideo = document.getElementById("idVideo");
const modalVideoTitle = document.getElementById("trickEditVideoAddModal");
const submitVideo = document.getElementById("submit-video");
// Replace video
changeVideoButton.forEach((element) => {
    element.addEventListener("click", () => {
        idVideo.setAttribute("value", element.dataset.id);
        modalVideoTitle.innerText = "Replace video tag";
        submitVideo.innerText = "Replace this video tag";
    });
});

// Add new video
const addVideoButton = document.getElementById("add-video-button");
addVideoButton.addEventListener("click", () => {
    modalVideoTitle.innerText = "Add a new video";
    submitVideo.innerText = "Add new video";
});

// Video form management
const form = document.getElementById("app-form-2");
const tagInput = document.getElementById("trick_video_tag");
const invalidMassage = TrickAdd.getValidationTemplate("This tag is not valid");
const checkTagRoute = document.getElementById("trick_videos").dataset.validation;
form.addEventListener("submit", function (event) {
    event.preventDefault();
    TrickVideoManager.checkValidity(tagInput, invalidMassage, checkTagRoute)
        .then(() => {
            if (!tagInput.classList.contains("is-invalid")) {
                form.submit();
                tagInput.setAttribute("value", "");
            }
        });
}, false);

// Media display management on mobile devices
const mediaButton = document.getElementById("app-section-media-button");
const mediaSection = document.getElementById("app-section-media");
MediaBlockManager.manage(mediaButton, mediaSection);
