<?php
	require_once "markdownDb.php";

	if(isset($_POST["documentId"])) {
		updateDocumentMarkdown(
			$_POST["documentId"],
			$_POST["text"]
		);
	}
?>