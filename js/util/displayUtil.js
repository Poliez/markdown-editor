"use strict";

function changeElementDisplayStyle(elementId, displayStyle) {

    if (typeof elementId !== "string")
        throw "elementId is not a string!";

    if (typeof displayStyle !== "string")
        throw "displayStyle is not a string!";

    document.getElementById(elementId).style.display = displayStyle;
}

function showInfoModal() {
    changeElementDisplayStyle("infomodal", "block");
}

function hideInfoModal() {
    changeElementDisplayStyle("infomodal", "none");
}