import {TrickVideoManager} from "./TrickVideoManager";

export class TrickAdd {
    static typeImage = "images";
    static typeVideo = "videos";

    static getGlobalContainer(id)
    {
        const globalContainer = document.createElement("div");
        globalContainer.setAttribute("class", "d-flex mb-3 justify-content-between align-items-center w-100");
        globalContainer.setAttribute("id", id + "-container");

        return globalContainer;
    }

    static getBaseTemplate(id)
    {
        const inputContainer = document.createElement("div");
        inputContainer.setAttribute("class", "w-75 text-body file-type");
        inputContainer.setAttribute("id", id);

        return inputContainer;
    }

    static getValidationTemplate(message)
    {
        // Div
        const feedBack = document.createElement("div");
        feedBack.setAttribute("class", "invalid-feedback");

        // Text message
        const templateText = document.createTextNode(message);
        feedBack.appendChild(templateText);

        return feedBack;
    }

    static getInputTemplate(id, name, required)
    {
        const inputTemplate = document.createElement("input");
        inputTemplate.setAttribute("id", id);
        inputTemplate.setAttribute("name", name);
        inputTemplate.setAttribute("class", "form-control");

        if (required === true) {
            inputTemplate.setAttribute("required", "required");
        }

        return inputTemplate;
    }

    static getImageTemplate(id, name, required)
    {
        const fileTemplate = this.getInputTemplate(id, name, required);
        fileTemplate.setAttribute("type", "file");
        fileTemplate.setAttribute("accept", "image/jpeg");

        return fileTemplate;
    }

    static getTagTemplate(id, name, required)
    {
        const tagTemplate = this.getInputTemplate(id, name, required);
        tagTemplate.setAttribute("type", "text");
        let checkTagRoute = document.getElementById("trick_videos").dataset.validation
        TrickVideoManager.addListener(tagTemplate, this.getValidationTemplate("This tag is not valid."), checkTagRoute);

        return tagTemplate;
    }

    static getRemoveButtonTemplate()
    {
        // Button
        const button = document.createElement("button");
        button.setAttribute("class", "btn btn-sm btn-outline-info");

        // Button text
        let text = document.createTextNode("Remove");
        button.appendChild(text);

        return button;
    }

    static setRemoveButton(globalContainer, counter, id)
    {
        let button = this.getRemoveButtonTemplate(id);
        button.addEventListener("click", () => {
            globalContainer.remove();
        });
        globalContainer.append(button);
    }

    static getField(counter, type, required = false)
    {
        let inputTemplate;
        let id;
        let name;
        let idBaseTemplate = `trick_${type}_${counter}`;

        switch (type) {
            case this.typeImage:
                id = `trick_${type}_${counter}_uploadedFile`;
                name = `trick[${type}][${counter}][uploadedFile]`;
                inputTemplate = this.getImageTemplate(id, name, required);
                break;
            case this.typeVideo:
                id = `trick_${type}_${counter}_tag`;
                name = `trick[${type}][${counter}][tag]`;
                inputTemplate = this.getTagTemplate(id, name, required);
                break;
        }

        let fieldTemplate = this.getBaseTemplate(idBaseTemplate);
        let globalContainer = this.getGlobalContainer(id);

        fieldTemplate.append(inputTemplate);

        globalContainer.append(fieldTemplate);
        this.setRemoveButton(globalContainer, counter, id);

        return globalContainer;
    }
}
