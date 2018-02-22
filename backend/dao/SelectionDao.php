<?php
class SelectionDao {
	private $hostname;
	private $database;
	private $username;
	private $password;
	
	function __construct($propertyLoader) {		
		$this->hostname = $propertyLoader->hostname;
		$this->database = $propertyLoader->database;
		$this->username = $propertyLoader->username;
		$this->password = $propertyLoader->password;
	}
	
	function findSValueBySCategory($sCategory) {

		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		//$sql = "INSERT INTO `users` (id, name, gender, location) VALUES (?, ?, ?, ?)";
		$sql = "SELECT s_value FROM selection WHERE s_category = ? ORDER BY s_value";
		$stmt = $mysqli->prepare($sql);
		
		$stmt->bind_param('s', $sCategory);
		$stmt->execute();
				
		$stmt->bind_result($sValue);
		
		$output = array();
		
		while ($stmt->fetch()) {
		//while ($row = $result->fetch_assoc()) {			
			array_push($output, $sValue);
		}
   
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
}

?>