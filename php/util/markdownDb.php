<?php

	require_once "dbManager.php";
	require_once "models.php";

	function authenticateUser($username, $password){
		global $db;

		$statement = 
			$db->createStatement(
				"SELECT * ".
				"FROM User ".
				"WHERE ( Username = ? OR Email = ?) "
			);

		$pass_hash = password_hash($password, PASSWORD_BCRYPT);

		$statement->bind_param(
			"ss",
			$username,
			$username
		);

		$statement->execute();

		$result = $statement->get_result();

		$nrows = $result->num_rows;
		
		if(!$nrows){
			$statement->close();
			return 0;
		}

		$statement->close();

		$user = $result->fetch_object();

		if(password_verify($password, $user->PasswordHash))
			return $user->Id;
		
		return 0;
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
		
		$nrows = createFolder($userId, "I Miei Documenti");
		
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
				"AND Deleted = 0 ".
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
				"AND D.Deleted = 0 ".
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
			while($doc !== null && $doc->IdFolder == $folders[$i]->Id){
				$folders[$i]->Documents[] = $doc;
				$doc = $result->fetch_object("Document");
			}
		}

		$statement->close();

		return $folders;
	}

	function createFolder($userId, $folderName){
		global $db;

		$statement =
			$db->createStatement(
				"INSERT INTO Folder (IdUser, Name) ".
				"VALUES (?, ?)"
			);

		$statement->bind_param(
			"is",
			$userId,
			$folderName
		);

		$statement->execute();

		$nrows = $statement->affected_rows;
		$statement->close();

		return $nrows;
	}

	function createDocument($userId, $folderId, $documentName) {
		global $db;

		if(is_null($folderId)) {
			$statement =
				$db->createStatement(
					"SELECT F.* ".
					"FROM User U INNER JOIN Folder F ON U.Id = F.IdUser ".
					"WHERE F.Name = 'I Miei Documenti'".
					"AND U.Id = ? ".
					"AND F.Deleted = 0 " 
				);

			$statement->bind_param(
				"i",
				$userId
			);

			$statement->execute();

			$result = $statement->get_result();
			$nrows = $result->num_rows;

			$statement->close();

			if(!$nrows)
				throw new Exception("Cartella 'I Miei Documenti' non trovata!");
			
			$folderId = $result->fetch_assoc()["Id"];
		}

		$statement =
			$db->createStatement(
				"INSERT INTO Document (IdFolder, Name) ".
				"VALUES (?, ?)"
			);

		$statement->bind_param(
			"is",
			$folderId,
			$documentName
		);

		$statement->execute();

		$nrows = $statement->affected_rows;
		$statement->close();

		return $nrows;
	}

	function deleteDocument($documentId) {
		global $db;

		$statement =
			$db->createStatement(
				"UPDATE Document ".
				"SET Deleted = 1 ".
				"WHERE Id = ?"
			);

		$statement->bind_param(
			"i",
			$documentId
		);

		$statement->execute();

		$nrows = $statement->affected_rows;
		$statement->close();

		return $nrows;
	}

	function deleteFolder($folderId) {
		global $db;

		$statement =
			$db->createStatement(
				"UPDATE Folder ".
				"SET Deleted = 1 ".
				"WHERE Id = ?"
			);

		$statement->bind_param(
			"i",
			$folderId
		);

		$statement->execute();

		$nrows = $statement->affected_rows;
		$statement->close();

		return $nrows;
	}
?>