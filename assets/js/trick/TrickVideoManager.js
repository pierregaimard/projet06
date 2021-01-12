export class TrickVideoManager {
    static checkValidity(input, message, route)
    {
        let form = new FormData();
        form.append("tag", input.value);
        return fetch(route, {
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
    }

    static addListener(input, message, route)
    {
        input.addEventListener("change", () => {
            this.checkValidity(input, message, route);
        });
    }
}
