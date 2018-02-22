<?php
class ReceiptDao {
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
	
	function findByOId($oid) {
		
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		//$sql = "INSERT INTO `users` (id, name, gender, location) VALUES (?, ?, ?, ?)";
		$sql = "SELECT * FROM receipt WHERE o_id = ?";
		$stmt = $mysqli->prepare($sql);
		
		$stmt->bind_param('s', $oid);
		$stmt->execute();
		
		$stmt->bind_result($oId,$rSub,$rExtension);
		
		$output = array();
		
		while ($stmt->fetch()) {
			$bean = new ReceiptBean();
						
			$bean->oId = $oId;
			$bean->rSub = $rSub;
			$bean->rExtension = $rExtension;
			
			array_push($output, $bean);
		}
   
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
	
	function add($entity) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "INSERT INTO receipt (o_id, r_sub, r_extension) VALUES (?, ?, ?)";
		$stmt = $mysqli->prepare($sql);
		
		$stmt->bind_param('sss', $entity->oId, $entity->rSub, $entity->rExtension);
		$stmt->execute();
		
		$stmt->free_result();
		$mysqli->close();
	}
}

?>