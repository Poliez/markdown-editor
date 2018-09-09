"use strict";

var selectedElement = undefined;
var selectedElementId = undefined;
var selectedColor = undefined;
var selectedIsFolder = undefined;
var selectedDocumentId = undefined;

function showHideFolder(folder, contentId) {
    var elem = document.getElementById(contentId);

    setElementParams(
    	true,
    	contentId.replace("folder", "")
	);

    selectElem(folder);

    if (elem.style.display == "none") {
        elem.style.display = "block";
        return;
    }

    elem.style.display = "none";
}

function selectFile(file, fileId) {
	selectedDocumentId = fileId;
	setElementParams(false, fileId);
	selectElem(file);
	
	var area = document.getElementById("writing-area");
	
	if(area)
		area.disabled = false;
}

function setElementParams(isFile, fileId){
	selectedIsFolder = isFile;
	selectedElementId = fileId;
}

function selectElem(elem) {

    if (typeof selectedColor == "undefined") {
        saveElemStyle(elem);
        return;
    }

    if (elem.style.backgroundColor == "whitesmoke")
        return;

    selectedElement.style.backgroundColor = selectedColor;

    saveElemStyle(elem);
}

function saveElemStyle(elem) {
    selectedElement = elem;
    selectedColor = elem.style.backgroundColor;
    elem.style.backgroundColor = "whitesmoke	";
}

function refreshExplorer(explorerText){
	document.getElementById("explorer").innerHTML = explorerText;

	selectedElement = undefined;
	selectedColor = undefined;
	selectedElementId = undefined;
	selectedIsFolder = undefined;
	selectedDocumentId = undefined;

	resetTextArea();
}

function resetTextArea() {
	var textArea = document.getElementById("writing-area");
	textArea.disabled = true;
	textArea.value = "# Eccoti nell'editor!\nSeleziona un documento per cominciare a scrivere.\n\n*Se ancora non hai documenti puoi crearne uno selezionando ![Aggiungi Documento](./../images/add-note.svg)*";
}

function newFolder(folderName) {
	var request = new XMLHttpRequest();

	request.onreadystatechange = function () {
		if (this.readyState != 4 || this.status != 200)
			return;

		refreshExplorer(this.responseText);
	};

	request
		.open(
			"GET", 
			"explorer.php?folderName=" 
				+ encodeURIComponent(folderName) 
				+ "&preventCache=" 
				+ Math.random(),
			true
		);

	request.send();
}

function newDocument(documentName) {
	var request = new XMLHttpRequest();

	request.onreadystatechange = function () {
		if (this.readyState != 4 || this.status != 200)
			return;

		refreshExplorer(this.responseText);
	};

	request
		.open(
			"GET", 
			"explorer.php?documentName=" 
				+ encodeURIComponent(documentName) 
				+ (typeof selectedIsFolder != "undefined" && selectedIsFolder 
					? "&folderId=" + selectedElementId
					: "")
				+ "&preventCache=" 
				+ Math.random(),
			true
		);

	request.send();
}

// function editName() {
// 
// }

function deleteDocument() {
	if(typeof selectedElementId == "undefined")
		return;

	var request = new XMLHttpRequest();

	request.onreadystatechange = function () {
		if (this.readyState != 4 || this.status != 200)
			return;

		refreshExplorer(this.responseText);
	};

	request
		.open(
			"GET", 
			"explorer.php?deleteId=" 
				+ encodeURIComponent(selectedElementId)
				+ "&element="
				+ (selectedIsFolder 
					? "folder"
					: "document")
				+ "&preventCache=" 
				+ Math.random(),
			true
		);

	request.send();

	hideDelete();
}

function showDelete() {
	changeElementDisplayStyle("deletemodal", "block");
}

function hideDelete() {
	changeElementDisplayStyle("deletemodal", "none");
}

function hideRequest() {
	changeElementDisplayStyle("requestmodal", "none");
}

var isCreatingFolder = false;
var isCreatingDocument = false;

function showRequest(title, type) {
	var modalTitle = document.getElementById("modal-title");
	modalTitle.textContent = title;

	isCreatingDocument 	= type === "document";
	isCreatingFolder 	= type === "folder";

	changeElementDisplayStyle("requestmodal", "block");
}

function executeExplorerOperation() {
	var name = document.getElementById("name-field").value;

	if(isCreatingFolder)
		newFolder(name);

	if(isCreatingDocument)
		newDocument(name);

	hideRequest();
	document.getElementById("name-field").value = "";
}