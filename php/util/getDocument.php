<?php
	require_once "markdownDb.php";

	if(isset($_GET["documentId"])) {
		$markdown = getDocumentMarkdown(
			$_GET["documentId"]
		);
		
		if(!is_null($markdown))
			echo $markdown;
	}
?>