class TrickVideoManager {
    static addListener(input, message)
    {
        input.addEventListener("change", () => {
            let form = new FormData();
            let route = document.getElementById("trick_videos").dataset.validation;
            form.append("tag", input.value);
            fetch(route, {
                method: "POST",
                body: form
            })
                .then((response) => response.json())
                .then((result) => {
                    input.parentNode.appendChild(message);
                    if (result.valid === false) {
                        input.classList.add("is-invalid");
                        message.style.display = "block";
                        input.setAttribute("pattern", "--error--");
                    }
                    if (result.valid === true) {
                        input.classList.remove("is-invalid");
                        input.removeAttribute("pattern");
                        message.style.display = "none";
                    }
                });
        });
    }
}

export { TrickVideoManager };