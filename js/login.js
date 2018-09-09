"use strict";

function showLoginForm() {
    changeElementDisplayStyle("loginform", "block");
    document.getElementById("userinput").focus();
}

function hideLoginForm() {
    changeElementDisplayStyle("loginform", "none");
}

function showRegistrationForm() {
    changeElementDisplayStyle("registrationform", "block");
    document.getElementById("nameinput").focus();
}

function hideRegistrationForm() {
    changeElementDisplayStyle("registrationform", "none");
}

window.onload = function(event){
    var loginForm = document.getElementById("loginform");
    var registrationForm = document.getElementById("registrationform");
    var infomodal = document.getElementById("infomodal");

    var password = document.getElementById("password");
    var confirm_password = document.getElementById("confirm");

    password.onchange = validatePassword;
    confirm_password.onkeyup = validatePassword;

    window.onclick = function(event) { 
        if(event.target == loginForm)
            loginForm.style.display = "none";

        if(event.target == registrationForm)
            registrationForm.style.display = "none";

        if(event.target == infomodal)
            infomodal.style.display = "none";
    }

    function validatePassword(){
        if(password.value != confirm_password.value){
            confirm_password.setCustomValidity("Le password sono diverse.");
            return;
        }
        
        confirm_password.setCustomValidity('');
    }
}