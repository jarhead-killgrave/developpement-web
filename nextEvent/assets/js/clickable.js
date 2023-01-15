"use strict";


/**
 * Function that extends the clickable area of all elements with the class "clickable"
 *
 */
function extendClickableArea() {
    let  clickableElements = document.getElementsByClassName("clickable");
    for (let i = 0; i < clickableElements.length; i++) {
        clickableElements[i].addEventListener("click", function (event) {
            let target = event.target;
            let  link = target.getAttribute("data-href");
            if (link) {
                window.location.href = link;
            }
        });
    }
}