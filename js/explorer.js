"use strict";

var selectedElement = undefined;
var selectedElementId = undefined;
var selectedColor = undefined;
var selectedIsFolder = undefined;
var selectedDocumentId = undefined;
var selectedFileName = "Guida";

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
	selectedFileName = file.textContent;
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
	selectedFileName = "Guida";

	resetTextArea();
}

function resetTextArea() {
	var textArea = document.getElementById("writing-area");
	textArea.disabled = true;
	textArea.value = "# Guida\n**Realtime markdown editor** ti consente di scrivere con un *subset* del markup [markdown](http://https://daringfireball.net/projects/markdown/syntax) e vederne il risultato in HTML in tempo reale!\n\nNonostante l'editor converta soltanto un subset del markup markdown è possibile fare svariate cose:\n* Puoi fare liste non ordinate\n* Puoi scrivere del testo **in grassetto**, *in corsivo*, in entrambe le ***modalità***... *Ma **non** necessariamente **contemporaneamente***\nOvviamente non possono mancare:\n1. Le liste ordinate.\n2. I blocchi di `codice inline`\n\n```\nPuoi anche includere blocchi di testo \npreformattato. Magari può servirti per scrivere del codice indentato!\nThe Zen of Python:\n    >>> import this\n    Beautiful is better than ugly.\n    Explicit is better than implicit.\n    Simple is better than complex.\n    Complex is better than complicated.\n    [ . . . ]\n```\n\nCon un click sopra l'icona ![Nuova cartella](./../images/create-new-folder.svg) avrai la possibilità di creare una cartella.\nTi verrà mostrata una finestra di dialogo in cui inserire il nome della nuova cartella.\n\nCon un click sopra l'icona ![Nuovo documento](./../images/add-note.svg) avrai la possibilità di creare un nuovo documento.\nAnche in questo caso ti verrà mostrata una finestra di dialogo in cui inserire il nome del nuovo documento.\n\n\nCon un click sopra l'icona ![Modifica](./../images/edit.svg) avrai la possibilità di cambiare il nome di un elemento, che potrai inserire all'interno di una finestra di dialogo.\n\nCon un click sopra l'icona ![Scarica](./../images/save.svg) potrai scaricare il risultato della conversione.\nPuoi pure provarci subito! Scaricherai il contenuto di questa guida!\n\nCon un click sopra l'icona ![Logout](./../images/logout.svg) potrai effettuare il logout.\n\nInfine, cliccando sull'icona ![Cestino](./../images/delete.svg) avrai la possibilità di eliminare l'elemento selezionato.\nNon preoccuparti! Ti verrà sempre chiesta la conferma prima di eliminare un tuo oggetto!\n**N.B.**: Eliminando una cartella eliminerai anche il suo contenuto!";

	updateConverted();
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

function editName(newName) {
	var request = new XMLHttpRequest();

	request.onreadystatechange = function () {
		if (this.readyState != 4 || this.status != 200)
			return;

		refreshExplorer(this.responseText);
	};

	request
		.open(
			"GET", 
			"explorer.php?editName="
				+ encodeURIComponent(newName) 
				+ (typeof selectedIsFolder != "undefined" && selectedIsFolder 
					? "&folderId="
					: "&documentId=") + selectedElementId
				+ "&preventCache=" 
				+ Math.random(),
			true
		);

	request.send();
}

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
var isEditing = false;

function showRequest(title, type) {
	var modalTitle = document.getElementById("modal-title");
	modalTitle.textContent = title;

	isCreatingDocument 	= type === "document";
	isCreatingFolder 	= type === "folder";
	isEditing			= type === "edit";

	changeElementDisplayStyle("requestmodal", "block");
}

function executeExplorerOperation() {
	var name = document.getElementById("name-field").value;

	if(isCreatingFolder)
		newFolder(name);

	if(isCreatingDocument)
		newDocument(name);

	if(isEditing)
		editName(name);

	hideRequest();
	document.getElementById("name-field").value = "";
}

function saveHtml() {

	var htmlContainer = document.getElementById("converted-container");

	var html = 
		"<!doctype html><html><head><title>"
		+ selectedFileName 
		+ "</title></head><body>" 
		+ htmlContainer.innerHTML
		+ "</body></html>";

	var blob = 
		new Blob(
			[html]
			,{type: "text/html;charset=utf-8"}
		);

	saveAs(blob, selectedFileName + ".html");
}