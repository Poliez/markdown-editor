<?php
    session_start();
    require_once "./util/markdownDb.php";

    $userId = $_SESSION["userId"];

    if(isset($_GET["folderName"])) {
        $newFolderName = $_GET["folderName"];

        if($newFolderName)
            createFolder($userId, $newFolderName);
    }

    if(isset($_GET["documentName"])) {
        $newDocumentName = $_GET["documentName"];

        $folderId = null;

        if(isset($_GET["folderId"]))
            $folderId = $_GET["folderId"];

        if($newDocumentName)
            createDocument($userId, $folderId, $newDocumentName);
    }

    if(isset($_GET["deleteId"])) {
        $elementId = $_GET["deleteId"];

        if(!isset($_GET["element"]))
            throw new Exception("Errore nella logica di cancellazione dell'elemento.\nContattare lo sviluppatore dell'applicazione.");

        if($_GET["element"] == "folder")
            deleteFolder($elementId);
        else
            deleteDocument($elementId);
    }

    $folders = getFoldersWithDocuments($userId);

    $nfolders = count($folders);

    for($i = 0; $i < $nfolders; $i++) {
        global $firstDocId;
        global $firstDocMarkdown;

        echo "<div class='folder'>";

        $id = $folders[$i]->Id;

        printTd(
            $folders[$i]->Name,
            "dir",
            "showHideFolder(this, \"folder$id\")"
        );

        echo "<div id='folder$id' class='folder-content'>";

        $docs = $folders[$i]->Documents;

        if($i == 0){
            $firstDocId = $docs[0]->Id;
            $firstDocMarkdown = $docs[0]->Markdown;
        }

        $ndocs = count($docs);
        for($j = 0; $j < $ndocs; $j++) {
            $docId = $docs[$j]->Id;
            printTd(
                $docs[$j]->Name,
                "file",
                "selectFile(this, $docId);downloadSelectedDocument($docId);" . ($i == 0 && $j == 0 ? "' id='firstDoc" : "") 
            );
        }

        echo "</div>";
        echo "</div>";
    }

    function printTd($name, $class, $onclick){
        print("
            <div class='$class' onclick='$onclick'>
                $name
            </div>");
    }

?>