"use strict";

var manager = new TimedFunctionExecutionManager(uploadText);

function timedUpload(text, documentId) {
    manager.startTimer(
        text,
        documentId
    );
}

function TimedFunctionExecutionManager(func) {
    var markdownUploadTimer = undefined;

    this.func = func;

    this.startTimer = function() {
        if (typeof markdownUploadTimer != "undefined")
            clearTimeout(markdownUploadTimer);

        setTimeout(
            func(
                Array.prototype.slice.call(arguments)
            ),
            1500
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