import {CommentBuilder} from "./CommentBuilder";

export class CommentManager {
    constructor(commentsContainer, loadMoreButton, limit, loadRoute, deleteRoute)
    {
        this.commentsContainer = commentsContainer;
        this.loadMoreButton = loadMoreButton;
        this.limit = limit;
        this.loadRoute = loadRoute;
        this.deleteRoute = deleteRoute;
        this.offset = 0;
    }

    // Hide loadMore button if no more comment can be loaded.
    _displayLoadMore(loadMore)
    {
        if (loadMore === false) {
            this.loadMoreButton.style.display = "none";
        }
    }

    // Global comment element
    _getCommentElement(comment)
    {
        // Global container
        const globalContainer = CommentBuilder.getGlobalContainer();

        // Avatar container
        const avatarContainer = CommentBuilder.getAvatarContainer(comment.src);
        globalContainer.append(avatarContainer);

        // Comment container
        const contentContainer = CommentBuilder.getContentContainer(comment.author, comment.createdAt, comment.comment);

        // Comment delete button
        if (comment.toDelete === true) {
            const deleteButton = CommentBuilder.getDeleteButton();
            deleteButton.addEventListener("click", () => {
                this.offset--;
                this._deleteComment(comment.id, globalContainer);
            });
            contentContainer.append(deleteButton);
        }

        globalContainer.append(contentContainer);

        return globalContainer;
    }

    // Ajax request [TrickCommentController::loadComments]
    _fetchComments()
    {
        const form = new FormData();
        form.append("offset", this.offset);
        fetch(this.loadRoute, {
            method: "POST",
            body: form
        })
            .then((response) => response.json())
            .then((result) => {
                this._displayLoadMore(result.loadMore);
                for (let comment of result.comments) {
                    this.commentsContainer.append(this._getCommentElement(comment));
                }
            });
    }

    // Ajax request [TrickCommentController::deleteComment]
    _deleteComment(id, globalContainer)
    {
        const form = new FormData();
        form.append("id", id);
        form.append("offset", this.offset);
        fetch(this.deleteRoute, {
            method: "POST",
            body: form
        })
            .then((response) => response.json())
            .then((result) => {
                if (result.done === true) {
                    globalContainer.remove();
                }
                this._displayLoadMore(result.loadMore);
            });
    }

    // First comment load
    getComments()
    {
        this._fetchComments();
    }

    // Load more action
    getMoreComments()
    {
        this.offset += this.limit;
        this._fetchComments();
    }
}
