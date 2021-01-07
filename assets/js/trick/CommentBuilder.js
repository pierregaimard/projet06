class CommentBuilder {
    // Global comment container
    static getGlobalContainer()
    {
        const globalContainer = document.createElement("div");
        globalContainer.setAttribute("class", "d-flex flex-row justify-content-start comment-container mb-3");

        return globalContainer;
    }

    // d-flex: left element: avatar
    static getAvatarContainer(src)
    {
        // Avatar container
        const avatarContainer = document.createElement("div");
        avatarContainer.setAttribute("class", "me-3");

        // Avatar img
        const img = document.createElement("img");
        img.setAttribute("src", src);
        img.setAttribute("width", "50");
        img.classList.add("rounded-circle");

        avatarContainer.append(img);

        return avatarContainer;
    }

    // d-flex: right element: comment content (author, creation time and content)
    static getContentContainer(author, createdAt, comment)
    {
        // Comment container
        const contentContainer = document.createElement("div");

        // Title
        const title = document.createElement("h6");
        title.classList.add("mt-2");

        // Author (title content)
        const authorNode = document.createTextNode(author);
        const br = document.createElement("br");

        title.append(authorNode);
        title.append(br);

        // Created at (in title container)
        const small = document.createElement("small");
        small.setAttribute("class", "fw-lighter snow");
        const createdAtNode = document.createTextNode(createdAt);
        small.append(createdAtNode);

        title.append(small);
        contentContainer.append(title);

        // Comment paragraph
        const commentNode = document.createElement("p");
        commentNode.classList.add("fw-lighter");
        const commentTextNode = document.createTextNode(comment);
        commentNode.append(commentTextNode);
        contentContainer.append(commentNode);

        return contentContainer;
    }

    // Delete button is displayed if user is the comment author
    static getDeleteButton()
    {
        // Button container
        const buttonContainer = document.createElement("p");
        const button = document.createElement("button");
        button.setAttribute("class", "btn btn-sm btn-outline-danger");

        // Button text
        const text = document.createTextNode("Delete");
        button.append(text);
        buttonContainer.append(button);

        return buttonContainer;
    }
}

export {CommentBuilder};
