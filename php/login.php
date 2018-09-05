<?php
    require_once "./util/markdownDb.php";
    require_once "./util/sessionUtil.php";
 	require_once "./util/navigationUtil.php";
 	require_once "./util/cookieManager.php";

	$username = $_POST['username'];
	$password = $_POST['password'];
	
	$errorMessage = authenticateUser($username, $password);
	
	if($errorMessage === null){
		session_start();
		$cookieManager->setUserCookie($username);
		goToEditor();
		exit;
	}
	
	goHomeWithError("Nome Utente o Password non validi.");
?>