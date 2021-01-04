import "../styles/account.scss";

import Modal from "bootstrap/js/dist/modal";
import Cropper from "cropperjs";

// Account Image crop implements cropper.js
window.addEventListener("DOMContentLoaded", () => {
    let avatar = document.getElementById("avatar");
    let image = document.getElementById("crop-image");
    let input = document.getElementById("input");
    let modalElm = document.getElementById("app-image-modal");
    let modal = new Modal(modalElm);
    let cropper;

    // Rounded crop canvas function
    function getRoundedCanvas(sourceCanvas)
    {
        let canvas = document.createElement("canvas");
        let context = canvas.getContext("2d");
        let width = sourceCanvas.width;
        let height = sourceCanvas.height;

        canvas.width = width;
        canvas.height = height;
        context.imageSmoothingEnabled = true;
        context.drawImage(sourceCanvas, 0, 0, width, height);
        context.globalCompositeOperation = "destination-in";
        context.beginPath();
        context.arc(width / 2, height / 2, Math.min(width, height) / 2, 0, 2 * Math.PI, true);
        context.fill();
        return canvas;
    }

    let done = (url) => {
        input.value = "";
        image.src = url;
        modal.show();
    };

    let fileWatcher = (file) => {
        let reader;

        if (URL) {
            done(URL.createObjectURL(file));
        } else if (FileReader) {
            reader = new FileReader();
            reader.onload = (e) => {
                done(reader.result);
            };
            reader.readAsDataURL(file);
        }
    };

    // Form selected file
    input.addEventListener("change", (e) => {
        let files = e.target.files;
        if (files && files.length > 0) {
            fileWatcher(files[0]);
        }
    });

    modalElm.addEventListener("shown.bs.modal", () => {
        cropper = new Cropper(image, {
            aspectRatio: 1,
            viewMode: 2,
        });
    });

    modalElm.addEventListener("hidden.bs.modal", () => {
        cropper.destroy();
        cropper = null;
    });

    // Asynchronous cropped file upload
    document.getElementById("app-account-avatar-crop").addEventListener("click", () => {
        let croppedCanvas;
        let canvas;

        modal.hide();

        if (cropper) {
            croppedCanvas = cropper.getCroppedCanvas({
                width: 200,
                height: 200,
            });
            canvas = getRoundedCanvas(croppedCanvas);
            avatar.src = canvas.toDataURL();
            canvas.toBlob((blob) => {
                let formData = new FormData();
                let route = document.getElementById("app-account-image-section").dataset.route;
                formData.append("avatar", blob);
                fetch(route, {
                    method: "POST",
                    body: formData,
                })
                    .then((response) => {
                        if (response.status === 500) {
                            response.json().then(
                                (result) => { alert(result.result); }
                            );
                        }
                    });
            });
        }
    });
});

// Remove account modal
let removeAccountButton = document.getElementById("app-remove-account-button");
let removeModal = new Modal(
    document.getElementById("app-account-remove-confirm")
);

removeAccountButton.addEventListener("click", (e) => {
    removeModal.show();
});
