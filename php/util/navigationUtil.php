<?php

	function goToEditor(){
		header('Location: /php/editor.php');
	}

	function goHome(){
		header('Location: /');
	}

	function goHomeWithError($errorMessage){
		header("Location: /?errorMessage=" . $errorMessage );
	}

?>