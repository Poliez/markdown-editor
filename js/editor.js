"use strict";

var uploadManager = new TimedFunctionExecutionManager(uploadText, 1500);
var markdownProcessor = new MarkdownProcessor();

function timedUpload(text, documentId) {

    if(typeof documentId == "undefined")
        return;

    updateConverted();

    uploadManager.startTimer(
        text,
        documentId
    );
}

function TimedFunctionExecutionManager(func, timeout) {
    var markdownUploadTimer = undefined;

    this.func = func;
    this.timeout = timeout;
    this.startTimer = function() {
        if (typeof markdownUploadTimer != "undefined")
            clearTimeout(markdownUploadTimer);

        setTimeout(
            func(
                Array.prototype.slice.call(arguments)
            ),
            timeout
        );
    }
}

function uploadText(array) {
	var request = new XMLHttpRequest();

    request
        .open(
            "POST",
            "util/updateDocument.php",
            true
        );

	request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    request.send(
        "text=" + array[0]
        + "&documentId=" + array[1] 
    );
}

function downloadSelectedDocument(selectedDocumentId) {
	var request = new XMLHttpRequest();

	request.onreadystatechange = function () {
		if (this.readyState != 4 || this.status != 200)
			return;

		setEditorText(this.responseText);
        updateConverted();
	};

    request
        .open(
            "GET",
            "util/getDocument.php?documentId=" 
            	+ selectedDocumentId
            	+ "&preventCache=" 
				+ Math.random(),
            true
        );

    request.send();
}

function setEditorText(text) {
	var textArea = document.getElementById("writing-area"); 

	textArea.value = text;
}

function updateConverted() {
    var textArea = document.getElementById("writing-area");
    var htmlContainer = document.getElementById("converted-container");

    if(htmlContainer)
        htmlContainer.innerHTML = markdownProcessor.convertToHtml(textArea.value);
}

window.onload = function(event){
    var requestmodal = document.getElementById("requestmodal");
    var infomodal = document.getElementById("infomodal");
    var deletemodal = document.getElementById("deletemodal");

    window.onclick = function(event) { 
        if(event.target == requestmodal)
            requestmodal.style.display = "none";

        if(event.target == infomodal)
            infomodal.style.display = "none";

        if(event.target == deletemodal)
            deletemodal.style.display = "none";
    }

    updateConverted();
}