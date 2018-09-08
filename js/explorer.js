"use strict";

var selectedElement = undefined;
var selectedElementId = undefined;
var selectedColor = undefined;
var selectedIsFolder = undefined;

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
	setElementParams(false, fileId);
	selectElem(file);
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
}

function newFolder() {
	var folderName = prompt("Scegli un nome per la nuova cartella!");

	if(folderName == "")
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
			"explorer.php?folderName=" 
				+ encodeURIComponent(folderName) 
				+ "&preventCache=" 
				+ Math.random(),
			true
		);

	request.send();
}

function newNote() {
	var documentName = prompt("Scegli un nome per il nuovo file! Se hai selezionato una cartella verrà inserito nella cartella selezionata (Oppure nella tua cartella 'I Miei Documenti').");

	if(documentName == "")
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

function deleteNote() {

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
}