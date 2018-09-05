<?php

	function goToEditor(){
		header('Location: ./editor.php');
	}

	function goHome(){
		header('Location: ./../../index.php');
	}

	function goHomeWithError($errorMessage){
		header("Location: ./../index.php?errorMessage=" . $errorMessage );
	}

?>