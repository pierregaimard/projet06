import "../../styles/trick/view.scss";

// Remove media listener
const removeMediaValidation = document.getElementById("media-remove-submit");
const removeMediaButtons = document.querySelectorAll(".remove-media-button");
removeMediaButtons.forEach((button) => {
    button.addEventListener("click", () => {
        removeMediaValidation.setAttribute("href", button.dataset.route);
    });
});

// Add/Replace image listener
const changeMediaButton = document.querySelectorAll(".change-image-button");
const idImage = document.getElementById("idImage");
const modalImageTitle = document.getElementById("trickEditImageAddModal");
const submitImage = document.getElementById("submit-image");
// Replace image
changeMediaButton.forEach((element) => {
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