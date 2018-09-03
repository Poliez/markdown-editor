"use strict";

function showLoginForm(formId) {

    if (typeof formId == "undefined")
        formId = "loginform";

    changeElementDisplayStyle(formId, "block");
}

function hideLoginForm(formId) {

    if (typeof formId == "undefined")
        formId = "loginform";

    changeElementDisplayStyle(formId, "none");
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