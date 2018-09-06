<?php
	session_start();
	require_once "./util/markdownDb.php";
?>

<!doctype html>
<html lang="it-IT">
<head>
	<title>Markdown Editor</title>
	<link rel="stylesheet" href="../css/files.css" />
</head>
<body>

    <script>
        function showHideElem(id){
            let elem = document.getElementById(id);

            if(elem.style.display == "none"){
                elem.style.display = "block";
                return;
            }

            elem.style.display = "none";
        }
    </script>

	<div id="docs">
    <h1>Documenti</h1>
    <div class="explorer">
        <?php
            $folders = getFoldersWithDocuments($_SESSION["userId"]);

            $nfolders = count($folders);

            for($i = 0; $i < $nfolders; $i++) {
                
                echo "<div class='folder'>";

                    printTd(
                            $folders[$i]->Name,
                            $folders[$i]->Id,
                            "dir",
                            "showHideElem(\"div$i\")"
                        );
                    
                    echo "<div id='div$i' class='folder-content'>";

                    $docs = $folders[$i]->Documents;
                    $ndocs = count($docs);
                    for($j = 0; $j < $ndocs; $j++)
                        printTd(
                            $docs[$j]->Name,
                            $docs[$j]->Id,
                            "file",
                            null
                        );

                    echo "</div>";
                echo "</div>";
            }

            function printTd($name, $namehref, $class, $onclick){
                print("
                    <div class='$class' onclick='$onclick'>
                        $name
                    </div>");
            }
        ?>

    </div>

</body>
</html>