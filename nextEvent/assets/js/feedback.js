"use strict";


/**
 * Show temporary feedback to the user.
 *
 * @param {string} message - The message to show.
 */
function showFeedback(message) {
    let feedback = document.body.querySelector("#feedBack");
    if (feedback !== null) {
        console.log("Feedback already exists.");
        feedback.innerHTML = message;
        feedback.classList.add("show");
        setTimeout(() => {
            console.log("Removing feedback.");
            feedback.classList.remove("show");
        }, 3000);
    }
}