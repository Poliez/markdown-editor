<?php
    require_once "./util/markdownDb.php";
    require_once "./util/sessionUtil.php";
 	require_once "./util/navigationUtil.php";
 	require_once "./util/cookieManager.php";

	$username = $_POST['username'];
	$password = $_POST['password'];
	$remember = $_POST['remember'];

	$userId = authenticateUser($username, $password);
	echo $userId;
	if($userId){
		session_start();
		setSession($userId, $username);

		if($remember)
			$cookieManager->setUserCookie($username);
		
		goToEditor();
		exit;
	}
	
	goHomeWithError("Nome Utente o Password non validi.");
?>