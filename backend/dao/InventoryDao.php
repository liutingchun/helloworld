<?php
class InventoryDao {
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
	
	function findByPid($pid) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		//$sql = "INSERT INTO `users` (id, name, gender, location) VALUES (?, ?, ?, ?)";
		$sql = "SELECT * FROM inventory WHERE pid = ? ORDER BY product_color, product_size";
		$stmt = $mysqli->prepare($sql);
		
		$stmt->bind_param('d', $pid);
		$stmt->execute();
		
		$stmt->bind_result($pid,$productStatus,$productColor,$productSize,$productPrice,$quantity);
		
		$output = array();
		
		while ($stmt->fetch()) {
			$bean = new InventoryBean();
						
			$bean->pid = $pid;
			$bean->productStatus = $productStatus;
			$bean->productColor = $productColor;
			$bean->productSize = $productSize;
			$bean->productPrice = $productPrice;
			$bean->quantity = $quantity;
			
			array_push($output, $bean);
		}
   
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
	
	function findByPidAndQuantityNot($pid, $quantity) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		//$sql = "INSERT INTO `users` (id, name, gender, location) VALUES (?, ?, ?, ?)";
		$sql = "SELECT * FROM inventory WHERE pid = ? AND quantity <> ? ORDER BY product_color, product_size";
		$stmt = $mysqli->prepare($sql);
		
		$stmt->bind_param('dd', $pid, $quantity);
		$stmt->execute();
		
		$stmt->bind_result($pid,$productStatus,$productColor,$productSize,$productPrice,$quantity);
		
		$output = array();
		
		while ($stmt->fetch()) {
			$bean = new InventoryBean();
						
			$bean->pid = $pid;
			$bean->productStatus = $productStatus;
			$bean->productColor = $productColor;
			$bean->productSize = $productSize;
			$bean->productPrice = $productPrice;
			$bean->quantity = $quantity;
			
			array_push($output, $bean);
		}
   
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
	
	function findByPidAndProductStatusAndProductColorAndProductSize($pid, $productStatus, $productColor, $productSize) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		//$sql = "INSERT INTO `users` (id, name, gender, location) VALUES (?, ?, ?, ?)";
		$sql = "SELECT * FROM inventory WHERE pid = ? AND product_status = ? AND product_color = ? AND product_size = ? ORDER BY product_color, product_size";
		$stmt = $mysqli->prepare($sql);
		
		$stmt->bind_param('dsss', $pid, $productStatus, $productColor, $productSize);
		$stmt->execute();
		
		$stmt->bind_result($pid,$productStatus,$productColor,$productSize,$productPrice,$quantity);
		
		$output = array();
		
		while ($stmt->fetch()) {
			$bean = new InventoryBean();
						
			$bean->pid = $pid;
			$bean->productStatus = $productStatus;
			$bean->productColor = $productColor;
			$bean->productSize = $productSize;
			$bean->productPrice = $productPrice;
			$bean->quantity = $quantity;
			
			array_push($output, $bean);
		}
   
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
	
	function update($entity) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "UPDATE inventory SET product_price = ?, quantity = ? WHERE pid = ? AND product_status = ? AND product_color = ? AND product_size = ?";
		$stmt = $mysqli->prepare($sql);
		
		$stmt->bind_param('dddsss', $entity->productPrice, $entity->quantity, $entity->pid, $entity->productStatus, $entity->productColor, $entity->productSize);
		$stmt->execute();
		
		$stmt->free_result();
		$mysqli->close();
	}
	
	function add($entity) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "INSERT INTO inventory (pid, product_status, product_color, product_size, product_price, quantity) VALUES (?, ?, ?, ?, ?, ?)";
		$stmt = $mysqli->prepare($sql);
								
		$stmt->bind_param('dsssdd', 
			$entity->pid,
			$entity->productStatus,
			$entity->productColor,
			$entity->productSize,
			$entity->productPrice,
			$entity->quantity
		);
		
		$stmt->execute();
		
		$stmt->free_result();
		$mysqli->close();
	}
	
	function remove($entity) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "DELETE FROM inventory WHERE pid = ? AND product_status = ? AND product_color = ? AND product_size = ?";
		$stmt = $mysqli->prepare($sql);
		
		$stmt->bind_param('dsss', $entity->pid, $entity->productStatus, $entity->productColor, $entity->productSize);
		$stmt->execute();
		
		$stmt->free_result();
		$mysqli->close();
	}
}

?>