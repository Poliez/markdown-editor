<?php

	require_once "dbManager.php";

	function authenticateUser($username, $password){
		global $db;

		$statement = 
			$db->createStatement(
				"SELECT * ".
				"FROM User ".
				"WHERE ( Username = ? OR Email = ?) ".
				"AND PasswordHash = ? "
			);

		$statement->bind_param(
			"sss",
			$username,
			$username, 
			password_hash($password, PASSWORD_BCRYPT)
		);

		$result = $statement->get_result();

		if($result->num_rows === 0)
			return false;

		// $userRow = $result->fetch_assoc();

		$statement->close();

		return true;
	}

	function registerUser($name, $surname, $username, $email, $password){
		global $db;
		
		$statement = 
			$db->createStatement(
				"INSERT INTO User (Email, Name, Surname, Username, PasswordHash, RegistrationDate) ".
				"VALUES (?, ?, ?, ?, ?, now())"
			);

		$statement->bind_param(
			"sssss",
			$email,
			$name,
			$surname,
			$username, 
			password_hash($password, PASSWORD_BCRYPT)
		);

		$statement->execute();

		if($statement->affected_rows === 0) 
			return false;
		
		$statement->close();

		return true;
	}

	function getUserId($username){
		global $db;

		$statement = 
			$db->createStatement(
				"SELECT Id ".
				"FROM User ".
				"WHERE Username = ?"
			);

		$statement->bind_param(
			"s",
			$username
		);

		$result = $statement->get_result();

		if($result->num_rows === 0)
			return 0;

		$userRow = $result->fetch_assoc();

		$statement->close();

		return $userRow["Id"];
	}

?>