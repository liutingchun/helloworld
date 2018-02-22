<?php
class OrderSubDao {
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
		
		$sql = "SELECT * FROM order_sub WHERE o_id = ? ORDER BY i_id";
		$stmt = $mysqli->prepare($sql);
	
		$stmt->bind_param('s', $oid);
		$stmt->execute();
		
		$stmt->bind_result($iId,$iPrice,$iStatus,$oId,$pId,$pColor,$pSize);
		
		$output = array();
		
		while ($stmt->fetch()) {
			$bean = new OrderSubBean();
						
			$bean->iId = $iId;
			$bean->iPrice = $iPrice;
			$bean->iStatus = $iStatus;
			$bean->oId = $oId;
			$bean->pId = $pId;
			$bean->pColor = $pColor;
			$bean->pSize = $pSize;
			
			array_push($output, $bean);
		}
   
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}	
		
	function getNextIId() {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "SELECT MAX(i_id) AS i_id FROM order_sub";
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
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "INSERT INTO order_sub (i_id, i_price, i_status, o_id, p_id, p_color, p_size) VALUES (?, ?, ?, ?, ?, ?, ?)";
		$stmt = $mysqli->prepare($sql);
		
		$stmt->bind_param('ddsddss', 
			$entity->iId,
			$entity->iPrice,
			$entity->iStatus,
			$entity->oId,
			$entity->pId,
			$entity->pColor,
			$entity->pSize
		);
		
		$stmt->execute();
		
		$stmt->free_result();
		$mysqli->close();
	}
}

?>