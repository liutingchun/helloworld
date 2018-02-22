<?php
class DiscountGroupDao {
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
	
	function getNextDId() {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "SELECT MAX(d_id) AS d_id FROM discount_group";
		$stmt = $mysqli->prepare($sql);
		
		$stmt->execute();
		$stmt->bind_result($max);
		
		$stmt->fetch();
		$output = $max + 1;
		
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
	
	function add($entity) {
		$entity->dId = $this->getNextDId();
		
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "INSERT INTO discount_group (d_id, d_name, d_type, d_value, d_quantity, d_descEn, d_descZhTW, d_descZhCN) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $mysqli->prepare($sql);
				
		$stmt->bind_param('dssddsss', 
			$entity->dId,
			$entity->dName,
			$entity->dType,
			$entity->dValue,
			$entity->dQuantity,
			$entity->dDescEn,
			$entity->dDescZhTW,
			$entity->dDescZhCN
		);
		
		$stmt->execute();
		
		$stmt->free_result();
		$mysqli->close();
	}
	
	function update($entity) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "UPDATE discount_group SET d_name = ?, d_type = ?, d_value = ?, d_quantity = ?, d_descEn = ?, d_descZhTW = ?, d_descZhCN = ? WHERE d_id = ?";
		$stmt = $mysqli->prepare($sql);
				
		$stmt->bind_param('ssddsssd', 
			$entity->dName,
			$entity->dType,
			$entity->dValue,
			$entity->dQuantity,
			$entity->dDescEn,
			$entity->dDescZhTW,
			$entity->dDescZhCN,
			$entity->dId
		);
		
		$stmt->execute();
		
		$stmt->free_result();
		$mysqli->close();
	}
	
	function findAll() {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "SELECT * FROM discount_group";
		$stmt = $mysqli->prepare($sql);
	
		$stmt->execute();
		
		$stmt->bind_result($dId, $dName, $dType, $dValue, $dQuantity, $dDescEn, $dDescZhTW, $dDescZhCN);
		 
		
		$output = array();
		
		while ($stmt->fetch()) {
			$bean = new DiscountGroupBean();
						
			$bean->dId = $dId;
			$bean->dName = $dName;
			$bean->dType = $dType;
			$bean->dValue = $dValue;
			$bean->dQuantity = $dQuantity;
			$bean->dDescEn = $dDescEn;
			$bean->dDescZhTW = $dDescZhTW;
			$bean->dDescZhCN = $dDescZhCN;
			
			array_push($output, $bean);
		}
   
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
	
	function joinDiscountGroupTagFindByPid($pid) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "SELECT * FROM discount_group WHERE d_id IN (SELECT d_id FROM discount_group_tag WHERE pid = ?)";
		$stmt = $mysqli->prepare($sql);
	
		$stmt->bind_param('d', $pid);
	
		$stmt->execute();
		
		$stmt->bind_result($dId, $dName, $dType, $dValue, $dQuantity, $dDescEn, $dDescZhTW, $dDescZhCN);
		 
		
		$output = array();
		
		while ($stmt->fetch()) {
			$bean = new DiscountGroupBean();
						
			$bean->dId = $dId;
			$bean->dName = $dName;
			$bean->dType = $dType;
			$bean->dValue = $dValue;
			$bean->dQuantity = $dQuantity;
			$bean->dDescEn = $dDescEn;
			$bean->dDescZhTW = $dDescZhTW;
			$bean->dDescZhCN = $dDescZhCN;
			
			array_push($output, $bean);
		}
   
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
}

?>