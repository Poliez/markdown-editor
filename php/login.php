<?php
    require_once "./util/dbManager.php";
    require_once "./util/sessionUtil.php";
 	require_once "./util/navigationUtil.php";
 	require_once "./php/util/cookieManager.php";

	$username = $_POST['username'];
	$password = $_POST['password'];
	
	$errorMessage = login($username, $password);
	
	if($errorMessage === null){
		$cookieManager->setUserCookie();
		goToEditor();
		exit;
	}
	
	header('location: ./../index.php?errorMessage=' . $errorMessage );


	function login($username, $password){   
		if ($username != null && $password != null){
			$userId = authenticate($username, $password);
    		if ($userId > 0){
    			session_start();
    			setSession($username, $userId);
    			return null;
    		}
    	}
    	
    	return 'Nome Utente o Password non validi.';
	}
	
	function authenticate ($username, $password){   
		global $db;
		$username = $db->sqlInjectionFilter($username);
		$password = $db->sqlInjectionFilter($password);

		$query = "SELECT * FROM User WHERE ( Username ='" . $username . "' OR Email ='". $username."')  AND PasswordHash='" . password_hash($password, PASSWORD_BCRYPT) . "'";

		$result = $db->performQuery($query);
		$numRow = mysqli_num_rows($result);
		if ($numRow != 1)
			return -1;
		
		$userRow = $result->fetch_assoc();
		
		// $db->closeConnection();
		return $userRow['Id'];
	}

?>