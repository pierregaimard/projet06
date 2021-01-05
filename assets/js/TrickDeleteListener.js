function TrickDeleteListener() {
    let deleteButtons = document.querySelectorAll(".delete-trick-button");
    let trickName = document.getElementById("trick-delete-name");
    let trickSubmit = document.getElementById("trick-delete-submit");
    deleteButtons.forEach((button) => {
        button.addEventListener("click", () => {
            trickName.innerText = button.dataset.name;
            trickSubmit.setAttribute("href", button.dataset.route);
        });
    });
}

export { TrickDeleteListener };