<?php

	require_once "dbManager.php";
	require_once "models.php";

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

		$nrows = $statement->affected_rows;
		$userId = $db->getLastId();
		$statement->close();

		if($nrows === 0) 
			return 0;
		
		$statement =
			$db->createStatement(
				"INSERT INTO Folder (IdUser, Name) ".
				"VALUES (?, 'I Miei Documenti')"
			);

		$statement->bind_param(
			"i",
			$userId
		);

		$statement->execute();

		$nrows = $statement->affected_rows;
		$statement->close();
		
		if($nrows === 0)
			return 0;

		return $userId;
	}

	function getUserId($username){
		global $db;

		$statement = 
			$db->createStatement(
				"SELECT Id ".
				"FROM User ". 
				"WHERE Username = ? OR Email = ? "
			);

		$statement->bind_param(
			"ss",
			$username,
			$username
		);

		$statement->execute();

		$result = $statement->get_result();

		$nrows = $result->num_rows;
		$userRow = $result->fetch_assoc();
		$statement->close();

		if($nrows === 0)
			return 0;

		return $userRow["Id"];
	}

	function getFolders($userId){
		global $db;

		$folders = [];

		$statement = 
			$db->createStatement(
				"SELECT * ".
				"FROM Folder ".
				"WHERE IdUser = ? ".
				"ORDER BY Id"
			);

		$statement->bind_param(
			"i",
			$userId
		);

		$statement->execute();

		$result = $statement->get_result();

		$nrows = $result->num_rows;

		if(!$nrows){
			$statement->close();
			return null;
		}

		$statement->close();

		while($folder = $result->fetch_object())
		{
			$folder->Documents = [];
			$folders[] = $folder;
		}

		return $folders;
	}


	function getFoldersWithDocuments($userId){
		global $db;

		$folders = getFolders($userId);

		$statement = 
			$db->createStatement(
				"SELECT D.* ".
				"FROM Document D INNER JOIN Folder F ON D.IdFolder = F.Id ".
				"WHERE F.IdUser = ? ".
				"ORDER BY D.IdFolder"
			);

		$statement->bind_param(
			"i",
			$userId
		);

		$statement->execute();

		$result = $statement->get_result();

		$nrows = $result->num_rows;

		if(!$nrows){
			$statement->close();
			return $folders;
		}

		$nfolders = count($folders);
		$doc = $result->fetch_object("Document");
		for($i = 0; $i < $nfolders; $i++){
			while($doc->IdFolder == $folders[$i]->Id){
				throw new Exception("YUP2");
				$folders[$i]->Documents[] = $doc;
				$doc = $result->fetch_object("Document");
			}
		}

		$statement->close();

		return $folders;
	}

?>