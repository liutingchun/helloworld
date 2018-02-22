<?php
class BannerDao {
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
	
	function findAll() {

		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		//$sql = "INSERT INTO `users` (id, name, gender, location) VALUES (?, ?, ?, ?)";
		$sql = "SELECT * FROM banner ORDER BY b_position, b_order";
		$stmt = $mysqli->prepare($sql);
		
		//$stmt->bind_param('dsss', $source_id, $source_name, $source_gender, $source_location);
		$stmt->execute();
		
		/*$result = $stmt->get_result();
		$num_of_rows = $result->num_rows;*/
		
		$stmt->bind_result($bId,$bSource,$bType,$bTarget,$bOrder,$bPosition);
		
		$output = array();
		
		while ($stmt->fetch()) {
		//while ($row = $result->fetch_assoc()) {
			$bean = new BannerBean();
						
			$bean->bId = $bId;
			$bean->bSource = $bSource;
			$bean->bType = $bType;
			$bean->bTarget = $bTarget;
			$bean->bOrder = $bOrder;
			$bean->bPosition = $bPosition;
			
			array_push($output, $bean);
		}
   
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
	
	function getNextBId() {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "SELECT MAX(b_id) AS b_id FROM banner";
		$stmt = $mysqli->prepare($sql);
		
		$stmt->execute();
		$stmt->bind_result($max);
		
		$stmt->fetch();
		$output = $max + 1;
		
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
	
	function getNextBOrder() {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "SELECT MAX(b_order) FROM banner";
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
		
		$sql = "INSERT INTO banner (b_id, b_source, b_type, b_target, b_order, b_position) VALUES (?, ?, ?, ?, ?, ?)";
		$stmt = $mysqli->prepare($sql);
				
		$stmt->bind_param('dsssdd', 
			$entity->bId,
			$entity->bSource,
			$entity->bType,
			$entity->bTarget,
			$entity->bOrder,
			$entity->bPosition
		);
		
		$stmt->execute();
		
		$stmt->free_result();
		$mysqli->close();
	}
	
	function update($entity) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "UPDATE banner SET b_source = ?, b_type = ?, b_target = ?, b_order = ?, b_position = ? WHERE b_id = ?";
		$stmt = $mysqli->prepare($sql);
				
		$stmt->bind_param('sssddd', 
			$entity->bSource,
			$entity->bType,
			$entity->bTarget,
			$entity->bOrder,
			$entity->bPosition,
			$entity->bId
		);
		
		$stmt->execute();
		
		$stmt->free_result();
		$mysqli->close();
	}
	
	function remove($entity) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "DELETE FROM banner WHERE b_id = ?";
		$stmt = $mysqli->prepare($sql);
				
		$stmt->bind_param('d', $entity->bId);
		
		$stmt->execute();
		
		$stmt->free_result();
		$mysqli->close();
	}
}

?>