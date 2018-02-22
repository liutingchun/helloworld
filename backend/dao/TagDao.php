<?php
class TagDao {
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
	
	function add($entity) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "INSERT INTO tag (p_id, t_name) VALUES (?, ?)";
		$stmt = $mysqli->prepare($sql);
		
		$stmt->bind_param('ds', $entity->pId, $entity->tName);
		$stmt->execute();
		
		$stmt->free_result();
		$mysqli->close();
	}
	
	function remove($entity) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "DELETE FROM tag WHERE p_id = ? AND t_name = ?";
		$stmt = $mysqli->prepare($sql);
		
		$stmt->bind_param('ds', $entity->pId, $entity->tName);
		$stmt->execute();
		
		$stmt->free_result();
		$mysqli->close();
	}
	
	function findAll() {

		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		//$sql = "INSERT INTO `users` (id, name, gender, location) VALUES (?, ?, ?, ?)";
		$sql = "SELECT * FROM tag ORDER BY p_id";
		$stmt = $mysqli->prepare($sql);
		
		//$stmt->bind_param('dsss', $source_id, $source_name, $source_gender, $source_location);
		$stmt->execute();
		
		$stmt->bind_result($pId,$tName);
		
		$output = array();
		
		while ($stmt->fetch()) {
			$bean = new TagBean();
						
			$bean->pId = $pId;
			$bean->tName = $tName;
			
			array_push($output, $bean);
		}
   
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
	
	function findByPId($pId) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "SELECT * FROM tag WHERE p_id = ?";
		$stmt = $mysqli->prepare($sql);
		
		$stmt->bind_param('d', $pId);
		$stmt->execute();
		
		$stmt->bind_result($pId,$tName);
		
		$output = array();
		
		while ($stmt->fetch()) {
			$bean = new TagBean();
						
			$bean->pId = $pId;
			$bean->tName = $tName;
			
			array_push($output, $bean);
		}
   
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
}

?>