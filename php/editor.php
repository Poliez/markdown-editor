<!doctype html>
<html lang="it-IT">
<head>
	<title>Markdown Editor</title>
	<link rel="stylesheet" href="../css/editor.css" />
    <script src="./../js/explorer.js"></script>
</head>
<body class="flex-container">
	<aside class="editor-element">
    <div class="explorer-operations">
        <img class="operation" onclick="newFolder()"    src="./../images/create-new-folder.svg" alt="Nuova Cartella"/>
        <img class="operation" onclick="newNote()"      src="./../images/add-note.svg" alt="Nuovo Documento"/>
        <!--<img class="operation" onclick="editName()"     src="./../images/edit.svg" />-->
        <img class="operation" onclick="deleteNote()"   src="./../images/delete.svg" alt="Elimina elemento selezionato"/>
    </div>
    <div id="explorer">
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
?>

    <div id="converted-container" class="editor-element writing markdown">
        
    </div>

    <script src="./../js/editor.js"></script>
</body>
</html>