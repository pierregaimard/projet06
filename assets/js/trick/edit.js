import "../../styles/trick/view.scss";

const removeMediaValidation = document.getElementById('media-remove-submit');
const removeMediaButtons = document.querySelectorAll('.remove-media-button');
removeMediaButtons.forEach((button) => {
    button.addEventListener('click', () => {
        removeMediaValidation.setAttribute('href', button.dataset.route);
    });
});