<?php
class DiscountGroupTagDao {
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
		
		$sql = "INSERT INTO discount_group_tag (d_id, pid) VALUES (?, ?)";
		$stmt = $mysqli->prepare($sql);
				
		$stmt->bind_param('dd', 
			$entity->dId,
			$entity->pid
		);
		
		$stmt->execute();
		
		$stmt->free_result();
		$mysqli->close();
	}
	
	function remove($entity) {		
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "DELETE FROM discount_group_tag WHERE d_id = ? AND pid = ?";
		$stmt = $mysqli->prepare($sql);
				
		$stmt->bind_param('dd', 
			$entity->dId,
			$entity->pid
		);
		
		$stmt->execute();
		
		$stmt->free_result();
		$mysqli->close();
	}
		
	function findByPid($pid) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "SELECT * FROM discount_group_tag WHERE pid = ?";
		$stmt = $mysqli->prepare($sql);
	
		$stmt->bind_param('d', 
			$pid
		);
	
		$stmt->execute();
		
		$stmt->bind_result($dId, $pid);
		 
		
		$output = array();
		
		while ($stmt->fetch()) {
			$bean = new DiscountGroupTagBean();
						
			$bean->dId = $dId;
			$bean->pid = $pid;
			
			array_push($output, $bean);
		}
   
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
}

?>