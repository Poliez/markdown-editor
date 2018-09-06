<?php
	session_start();

	function setSession($userId, $username){
		$_SESSION["username"] = $username;
		$_SESSION["userId"] = $userId;
	}

?>