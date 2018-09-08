<!doctype html>
<html lang="it-IT">
<head>
	<title>Markdown Editor</title>
	<link rel="stylesheet" href="../css/editor.css" />
    <script type="text/javascript" src="./../js/explorer.js"></script>
</head>
<body class="flex-container">
	<aside class="editor-element">
    <div class="explorer-operations">
        <img class="operation" onclick="newFolder()"    src="./../images/create-new-folder.svg"/>
        <img class="operation" onclick="newNote()"      src="./../images/add-note.svg" />
        <!--<img class="operation" onclick="editName()"     src="./../images/edit.svg" />-->
        <img class="operation" onclick="deleteNote()"   src="./../images/delete.svg" />
    </div>
    <div id="explorer">
        <?php
            require "explorer.php"
        ?>
    </div>

    </aside>

    <textarea class="editor-element writing"></textarea>

    <div id="converted-container" class="editor-element writing markdown">
        
    </div>
</body>
</html>