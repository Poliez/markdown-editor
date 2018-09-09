<!doctype html>
<html lang="it-IT">
<head>
	<title>Markdown Editor</title>
    <link rel="stylesheet" href="../css/style.css" />
	<link rel="stylesheet" href="../css/editor.css" />
    <script src="./../js/util/displayUtil.js"></script>
    <script src="./../js/explorer.js"></script>
</head>
<body class="flex-container">
	<aside class="editor-element">
    <div class="flex-container flex-space-around">
        <img class="operation" onclick="showRequest('Nuova cartella', 'folder');"    src="./../images/create-new-folder.svg" alt="Nuova Cartella"/>
        <img class="operation" onclick="showRequest('Nuovo documento', 'document');"      src="./../images/add-note.svg" alt="Nuovo Documento"/>
        <!--<img class="operation" onclick="editName()"     src="./../images/edit.svg" />-->
        <img class="operation" onclick="showDelete();"   src="./../images/delete.svg" alt="Elimina elemento selezionato"/>
    </div>
    <div id="explorer" class="explorer">
<?php
    require "explorer.php";

    echo "<script>";
        echo "selectFile(document.getElementById('firstDoc'), $firstDocId)";
    echo "</script>";


    echo "</div>";

    echo "</aside>";

    echo "<textarea id='writing-area' onchange='timedUpload(this.value, selectedDocumentId);' class='editor-element writing'>";

        echo "$firstDocMarkdown";

    echo "</textarea>";

    if(!isset($firstDocId))
    {
        echo "<script>";
        echo "resetTextArea();";
        echo "</script>";
    }
?>

    <div id="converted-container" class="editor-element writing markdown">
        
    </div>

<?php
    require "./../html/infoModal.html";
?>

    <div id="requestmodal" class="modal">
        <div class="modal-content animate" >
            <div class="container">
                <h2 id="modal-title" class="loginHead"></h2>
                <span onclick="hideRequest()" class="close" title="Chiudi">&times;</span>
                <input id="name-field" type="text" placeholder="Nome" />
            </div>
            <div class="flex-container flex-space-around">
                <button class="confirm" type="button" onclick="executeExplorerOperation();">Conferma</button>
                <button class="cancel" type="button" onclick="hideRequest();">Annulla</button>
            </div>
        </div>
    </div>

    <div id="deletemodal" class="modal">
        <div class="modal-content animate" >
            <div class="container">
                <h1 class="loginHead">Sicuro di voler eliminare l'elemento selezionato?</h1>
                <span onclick="hideDelete()" class="close" title="Chiudi">&times;</span>
            </div>
            <div class="flex-container flex-space-around">
                <button class="confirm" type="button" onclick="deleteDocument();">Conferma</button>
                <button class="cancel" type="button" onclick="hideDelete();">Annulla</button>
            </div>
        </div>
    </div>

    <script src="./../js/editor.js"></script>
</body>
</html>