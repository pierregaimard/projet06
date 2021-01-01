import "../styles/account.scss";

import Modal from "bootstrap/js/dist/modal";
import Cropper from "cropperjs";

// Account Image crop implements cropper.js
window.addEventListener("DOMContentLoaded", function () {
    const avatar = document.getElementById("avatar");
    const image = document.getElementById("crop-image");
    const input = document.getElementById("input");
    const modalElm = document.getElementById("app-image-modal");
    const modal = new Modal(modalElm);
    let cropper;

    // Rounded crop canvas function
    function getRoundedCanvas(sourceCanvas)
    {
        const canvas = document.createElement("canvas");
        const context = canvas.getContext("2d");
        const width = sourceCanvas.width;
        const height = sourceCanvas.height;

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

    const done = function (url) {
        input.value = "";
        image.src = url;
        modal.show();
    };

    const fileWatcher = function (file) {
        let reader;

        if (URL) {
            done(URL.createObjectURL(file));
        } else if (FileReader) {
            reader = new FileReader();
            reader.onload = function (e) {
                done(reader.result);
            };
            reader.readAsDataURL(file);
        }
    };

    // Form selected file
    input.addEventListener("change", function (e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            fileWatcher(files[0]);
        }
    });

    modalElm.addEventListener("shown.bs.modal", function () {
        cropper = new Cropper(image, {
            aspectRatio: 1,
            viewMode: 2,
        });
    });

    modalElm.addEventListener("hidden.bs.modal", function () {
        cropper.destroy();
        cropper = null;
    });

    // Asynchronous cropped file upload
    document.getElementById("app-account-avatar-crop").addEventListener("click", function () {
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
            canvas.toBlob(function (blob) {
                const formData = new FormData();
                const route = document.getElementById("app-account-image-section").dataset.route;
                formData.append("avatar", blob);
                fetch(route, {
                    method: "POST",
                    body: formData,
                })
                    .then(function (response) {
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
const removeAccountButton = document.getElementById("app-remove-account-button");
const removeModal = new Modal(
    document.getElementById("app-account-remove-confirm")
);

removeAccountButton.addEventListener("click", function (e) {
    removeModal.show();
});
