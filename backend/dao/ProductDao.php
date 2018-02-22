<?php
class ProductDao {
	private $hostname;
	private $database;
	private $username;
	private $password;
		
	function getCurrentTime() {
		date_default_timezone_set('Asia/Hong_Kong');
		return $date = date('Y-m-d H:i:s', time());	
	}
		
	function __construct($propertyLoader) {		
		$this->hostname = $propertyLoader->hostname;
		$this->database = $propertyLoader->database;
		$this->username = $propertyLoader->username;
		$this->password = $propertyLoader->password;
	}
		
	function findAll() {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "SELECT * FROM product ORDER BY pid DESC";
		$stmt = $mysqli->prepare($sql);
		
		$stmt->execute();
		
		$stmt->bind_result($pid,$name,$officalCode,$category,$brand,$color,$sex,$price,$fakeprice,$description,$image,$hide,$lastEdit,$viewCount);
		
		$output = array();
		
		while ($stmt->fetch()) {
			$bean = new ProductBean();
									
			$bean->pid = $pid;
			$bean->name = $name;
			$bean->officalCode = $officalCode;
			$bean->category = $category;
			$bean->brand = $brand;
			$bean->color = $color;
			$bean->sex = $sex;
			$bean->price = $price;
			$bean->fakeprice = $fakeprice;
			$bean->description = $description;
			$bean->image = $image;
			$bean->hide = $hide;
			$bean->lastEdit = $lastEdit;
			$bean->viewCount = $viewCount;
			
			array_push($output, $bean);
		}
   
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
	
	function findByHideNot($hide) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "SELECT * FROM product WHERE hide <> ? ORDER BY pid DESC";
		$stmt = $mysqli->prepare($sql);
		
		$stmt->bind_param("s", $hide);
		
		$stmt->execute();
		
		$stmt->bind_result($pid,$name,$officalCode,$category,$brand,$color,$sex,$price,$fakeprice,$description,$image,$hide,$lastEdit,$viewCount);
		
		$output = array();
		
		while ($stmt->fetch()) {
			$bean = new ProductBean();
									
			$bean->pid = $pid;
			$bean->name = $name;
			$bean->officalCode = $officalCode;
			$bean->category = $category;
			$bean->brand = $brand;
			$bean->color = $color;
			$bean->sex = $sex;
			$bean->price = $price;
			$bean->fakeprice = $fakeprice;
			$bean->description = $description;
			$bean->image = $image;
			$bean->hide = $hide;
			$bean->lastEdit = $lastEdit;
			$bean->viewCount = $viewCount;
			
			array_push($output, $bean);
		}
   
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
	
	function findByPid($pid) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "SELECT * FROM product WHERE pid = ?";
		$stmt = $mysqli->prepare($sql);
		
		$stmt->bind_param("s", $pid);
		
		$stmt->execute();
		
		$stmt->bind_result($pid,$name,$officalCode,$category,$brand,$color,$sex,$price,$fakeprice,$description,$image,$hide,$lastEdit,$viewCount);
		
		$output = array();
		
		while ($stmt->fetch()) {
			$bean = new ProductBean();
									
			$bean->pid = $pid;
			$bean->name = $name;
			$bean->officalCode = $officalCode;
			$bean->category = $category;
			$bean->brand = $brand;
			$bean->color = $color;
			$bean->sex = $sex;
			$bean->price = $price;
			$bean->fakeprice = $fakeprice;
			$bean->description = $description;
			$bean->image = $image;
			$bean->hide = $hide;
			$bean->lastEdit = $lastEdit;
			$bean->viewCount = $viewCount;
			
			array_push($output, $bean);
		}
   
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
	
	function findByPidAndHide($pid, $hide) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "SELECT * FROM product WHERE pid = ? AND hide = ?";
		$stmt = $mysqli->prepare($sql);
		
		$stmt->bind_param("sd", $pid, $hide);
		
		$stmt->execute();
		
		$stmt->bind_result($pid,$name,$officalCode,$category,$brand,$color,$sex,$price,$fakeprice,$description,$image,$hide,$lastEdit,$viewCount);
		
		$output = array();
		
		while ($stmt->fetch()) {
			$bean = new ProductBean();
									
			$bean->pid = $pid;
			$bean->name = $name;
			$bean->officalCode = $officalCode;
			$bean->category = $category;
			$bean->brand = $brand;
			$bean->color = $color;
			$bean->sex = $sex;
			$bean->price = $price;
			$bean->fakeprice = $fakeprice;
			$bean->description = $description;
			$bean->image = $image;
			$bean->hide = $hide;
			$bean->lastEdit = $lastEdit;
			$bean->viewCount = $viewCount;
			
			array_push($output, $bean);
		}
   
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
	
	function getAllVisibleDistinctCategory() {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "SELECT DISTINCT category FROM product WHERE hide <> '1' ORDER BY category ASC";
		$stmt = $mysqli->prepare($sql);
		
		$stmt->execute();
				
		$stmt->bind_result($category);
		
		$output = array();
		
		while ($stmt->fetch()) {		
			array_push($output, $category);
		}
   
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
	
	function getNextPid() {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "SELECT MAX(pid) AS pid FROM product";
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
		
		$sql = "INSERT INTO product (pid, name, offical_code, category, brand, color, sex, price, fakeprice, description, image, hide, last_edit, view_count) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $mysqli->prepare($sql);
		
		$entity->pid = $this->getNextPid();
		$entity->image = 0;
		$entity->hide = 1;
		$entity->lastEdit = $this->getCurrentTime();
		$entity->viewCount = 0;
		
		$stmt->bind_param('dssssssddsddsd', 
			$entity->pid,
			$entity->name,
			$entity->officalCode,
			$entity->category,
			$entity->brand,
			$entity->color,
			$entity->sex,
			$entity->price,
			$entity->fakeprice,
			$entity->description,
			$entity->image,
			$entity->hide,
			$entity->lastEdit,
			$entity->viewCount
		);
		
		$stmt->execute();
		
		$stmt->free_result();
		$mysqli->close();
	}
	
	function update($entity) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "UPDATE product SET pid = ?, name = ?, offical_code = ?, category = ?, brand = ?, color = ?, sex = ?, price = ?, fakeprice = ?, description = ?, image = ?, hide = ?, last_edit = ?, view_count = ? WHERE pid = ?";
		$stmt = $mysqli->prepare($sql);
		
		$entity->lastEdit = $this->getCurrentTime();
		
		$stmt->bind_param('dssssssddsddsdd', 
			$entity->pid,
			$entity->name,
			$entity->officalCode,
			$entity->category,
			$entity->brand,
			$entity->color,
			$entity->sex,
			$entity->price,
			$entity->fakeprice,
			$entity->description,
			$entity->image,
			$entity->hide,
			$entity->lastEdit,
			$entity->viewCount,
			$entity->pid
		);
		
		$stmt->execute();
		
		$stmt->free_result();
		$mysqli->close();
	}
	
	function updateViewCount($pid) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "UPDATE product SET view_count = view_count + 1 WHERE pid = ?";
		$stmt = $mysqli->prepare($sql);
		
		$stmt->bind_param("s", $pid);
		
		$stmt->execute();
		
		$stmt->free_result();
		$mysqli->close();
	}
}

?>