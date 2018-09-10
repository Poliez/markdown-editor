<!doctype html>
<html lang="it-IT">
<head>
	<title>Markdown Editor</title>
    <link rel="stylesheet" href="../css/style.css" />
	<link rel="stylesheet" href="../css/editor.css" />
    <link rel="stylesheet" href="../css/marked.css" />
    <script src="./../js/markdown-processor.js"></script>
    <script src="./../js/util/displayUtil.js"></script>
    <script src="./../js/explorer.js"></script>
    <script src="./../js/editor.js"></script>
</head>
<body class="flex-container">
	<aside class="editor-element">
    <div class="flex-container flex-space-around">
        <img class="operation" onclick="showRequest('Nuova cartella', 'folder');" src="./../images/create-new-folder.svg" alt="Nuova Cartella"/>
        <img class="operation" onclick="showRequest('Nuovo documento', 'document');" src="./../images/add-note.svg" alt="Nuovo Documento"/>
        <img class="operation" onclick="showRequest('Modifica', 'edit');" src="./../images/edit.svg" alt="Modifica"/>
        <img class="operation" onclick="saveHtml()" src="./../images/save.svg" alt="Salva">
        <img class="operation" onclick="showDelete();"   src="./../images/delete.svg" alt="Elimina elemento selezionato"/>
        <a href="logout.php"><img class="operation" src="./../images/logout.svg" alt="Logout"/></a>
    </div>
    <div id="explorer" class="explorer">
<?php
    require "explorer.php";

    if(isset($firstDocId))
    {
        echo "<script>";
        echo "selectFile(document.getElementById('firstDoc'), $firstDocId)";
        echo "</script>";
    }


    echo "</div>";

    echo "</aside>";

    echo "<textarea id='writing-area' onKeyDown='timedUpload(this.value, selectedDocumentId);' class='editor-element writing'>";

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

    <div id="requestmodal" class="modal">
        <div class="modal-content animate" >
            <div class="container">
                <h2 id="modal-title" class="loginHead">.</h2>
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
    <script async="" src="https://cdn.rawgit.com/eligrey/FileSaver.js/e9d941381475b5df8b7d7691013401e171014e89/FileSaver.min.js"></script>
</body>
</html>