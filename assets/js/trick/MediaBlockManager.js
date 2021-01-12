export class MediaBlockManager {
    static manage(button, container)
    {
        button.addEventListener("click", () => {
            if (container.style.display === "" || container.style.display === "none") {
                container.style.display = "block";
                button.innerText = "Hide medias";
                return;
            }
            if (container.style.display === "block") {
                container.style.display = "none";
                button.innerText = "See medias";
            }
        });
    }
}