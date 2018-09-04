<?php
	
	function setSession($username, $userId){
		$_SESSION['userId'] = $userId;
		$_SESSION['username'] = $username;
	}

	function isLogged(){		
		if(isset($_SESSION['userId']))
			return $_SESSION['userId']
		
		return false;
	}

?>