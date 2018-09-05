<?php
	require_once "./util/markdownDb.php";
	require_once "./util/navigationUtil.php";
	require_once "./util/sessionUtil.php";
	require_once "./util/cookieManager.php";

	$name 		= $_POST["name"];
	$surname 	= $_POST["surname"];
	$username 	= $_POST["username"];
	$email 		= $_POST["email"];
	$password 	= $_POST["password"];
	$confirm	= $_POST["confirm"];

	$errors = "";

	if($name === null)
		$errors = $errors . "%0ADevi%20inserire%20il%20tuo%20nome";

	if($surname === null)
		$errors = $errors . "%0ADevi%20inserire%20il%20tuo%20cognome";

	if($username === null)
		$errors = $errors . "%0ADevi%20inserire%20il%20tuo%20nome%20utente";

	$success = 
		preg_match(
			"~[^@]+@[^@]+\.[a-zA-Z]{2,6}~", 
			$email, 
			$match
		);

	if(!$success)
		$errors = $errors . "%0ADevi%20inserire%20un%20indirizzo%20email%20valido";

	$success = 
		preg_match(
			"~^(?=.*[A-Z])(?=.*\d)(?=.*[!-/:-@\[-`{-\~])[A-Za-zÀ-ÿ\d!-/:-@\[-`{-\~]{8,32}~", 
			$password, 
			$match
		);

	if(!$success)
		$errors = $errors . "%0ALa%20password%20non%20rispetta%20gli%20standard%20di%20sicurezza";

	if($password !== $confirm)
		$errors = $errors . "%0ALe%20password%20inserite%20sono%20diverse";

	if($errors !== "")
	{
		goHomeWithError($errors);
		exit;
	}

	$check = 
		registerUser(
			$name, 
			$surname, 
			$username, 
			$email, 
			$password
		);

	if($check){
		session_start();
		$cookieManager->setUserCookie($username);
		goToEditor();
		exit;
	}

	goHomeWithError("Registrazione fallita.");
?>