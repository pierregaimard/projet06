import "../../styles/trick/view.scss";
import {CommentManager} from "./CommentManager";
import {trickDeleteListener} from "../trickDeleteListener";
import {MediaBlockManager} from "./MediaBlockManager";

// Display image modal in media section.
const imgButtons = document.querySelectorAll("#trick-view-image-modal-button");
const modalImage = document.getElementById("trick-view-image-modal-image");
imgButtons.forEach((element) => {
    element.addEventListener("click", () => {
        modalImage.setAttribute("src", element.dataset.image);
    });
});

// Comments manager
const commentsContainer = document.getElementById("trick-comments-container");
const loadMore = document.getElementById("load-more-comments");
const limit = parseInt(loadMore.dataset.limit, 10);
const loadRoute = commentsContainer.getAttribute("data-route-load");
const deleteRoute = commentsContainer.getAttribute("data-route-delete");

const commentManager = new CommentManager(commentsContainer, loadMore, limit, loadRoute, deleteRoute);

window.addEventListener("DOMContentLoaded", () => {
    commentManager.getComments();
}, false);

loadMore.addEventListener("click", () => {
    commentManager.getMoreComments();
});

trickDeleteListener();

// Media display management on mobile devices
const mediaButton = document.getElementById("app-section-media-button");
const mediaSection = document.getElementById("app-section-media");
MediaBlockManager.manage(mediaButton, mediaSection);