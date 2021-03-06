<?php  
	require_once "dbConfig.php";
    
    $db = new MarkdownDbManager();

	class MarkdownDbManager {
		private $mysqli_conn = null;
	
		function MarkdownDbManager(){
			$this->openConnection();
		}
    
    	function openConnection(){
    		if (!$this->isOpened()){
    			global $dbHostname;
    			global $dbUsername;
    			global $dbPassword;
    			global $dbName;
    			
    			$this->mysqli_conn = new mysqli($dbHostname, $dbUsername, $dbPassword);
				if ($this->mysqli_conn->connect_error) 
					die('Connect Error (' . $this->mysqli_conn->connect_errno . ') ' . $this->mysqli_conn->connect_error);

				$this->mysqli_conn->select_db($dbName) or
					die ('Can\'t select $dbName: ' . mysqli_error());
			}
    	}
    
    	function isOpened(){
       		return ($this->mysqli_conn != null);
    	}

		function performQuery($queryText) {
			if (!$this->isOpened())
				$this->openConnection();
			
			return $this->mysqli_conn->query($queryText);
		}
		
		function sqlInjectionFilter($parameter){
			if(!$this->isOpened())
				$this->openConnection();
				
			return $this->mysqli_conn->real_escape_string($parameter);
		}

		function closeConnection(){
 	       	if($this->mysqli_conn !== null)
				$this->mysqli_conn->close();
			
			$this->mysqli_conn = null;
		}

		function createStatement($query){
			return $this->mysqli_conn->prepare($query);
		}

		function getLastId(){
			return $this->mysqli_conn->insert_id;
		}
	}

?>