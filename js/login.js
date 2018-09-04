"use strict";

function showLoginForm() {
    changeElementDisplayStyle("loginform", "block");
}

function hideLoginForm() {
    changeElementDisplayStyle("loginform", "none");
}

function showRegistrationForm() {
    changeElementDisplayStyle("registrationform", "block");
}

function hideRegistrationForm() {
    changeElementDisplayStyle("registrationform", "none");
}

function changeElementDisplayStyle(elementId, displayStyle) {

    if (typeof elementId !== "string")
        throw "elementId is not a string!";

    if (typeof displayStyle !== "string")
        throw "displayStyle is not a string!";

    document.getElementById(elementId).style.display = displayStyle;
}

var modalForm = document.getElementById("loginform");

window.onclick = function(event) {
    if (event.target == modalForm) {
        modalForm.style.display = "none";
    }
}