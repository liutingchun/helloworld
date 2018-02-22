<?php
class OrderMadeDao {
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
		
		$sql = "SELECT * FROM order_made ORDER BY o_date desc";
		$stmt = $mysqli->prepare($sql);
	
		$stmt->execute();
		
		$stmt->bind_result($oId,$mUsername,$oDate,$oFname,$oLname,$oEmail,$oPhone,$oLocation,$oStatus,$oItems,$oTotal,$oPaid,$oSerial,$oArchive,$oMethod,$oRemarks);
		 
		
		$output = array();
		
		while ($stmt->fetch()) {
			$bean = new OrderMadeBean();
						
			$bean->oId = $oId;
			$bean->mUsername = $mUsername;
			$bean->oDate = $oDate;
			$bean->oFname = $oFname;
			$bean->oLname = $oLname;
			$bean->oEmail = $oEmail;
			$bean->oPhone = $oPhone;
			$bean->oLocation = $oLocation;
			$bean->oStatus = $oStatus;
			$bean->oItems = $oItems;
			$bean->oTotal = $oTotal;
			$bean->oPaid = $oPaid;
			$bean->oSerial = $oSerial;
			$bean->oArchive = $oArchive;
			$bean->oMethod = $oMethod;
			$bean->oRemarks = $oRemarks;
			
			array_push($output, $bean);
		}
   
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
	
	function findByOArchive($archive) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "SELECT * FROM order_made WHERE o_archive = ? ORDER BY o_date desc";
		$stmt = $mysqli->prepare($sql);
	
		$stmt->bind_param('d', $archive);
		$stmt->execute();
		
		$stmt->bind_result($oId,$mUsername,$oDate,$oFname,$oLname,$oEmail,$oPhone,$oLocation,$oStatus,$oItems,$oTotal,$oPaid,$oSerial,$oArchive,$oMethod,$oRemarks);
		 
		
		$output = array();
		
		while ($stmt->fetch()) {
			$bean = new OrderMadeBean();
						
			$bean->oId = $oId;
			$bean->mUsername = $mUsername;
			$bean->oDate = $oDate;
			$bean->oFname = $oFname;
			$bean->oLname = $oLname;
			$bean->oEmail = $oEmail;
			$bean->oPhone = $oPhone;
			$bean->oLocation = $oLocation;
			$bean->oStatus = $oStatus;
			$bean->oItems = $oItems;
			$bean->oTotal = $oTotal;
			$bean->oPaid = $oPaid;
			$bean->oSerial = $oSerial;
			$bean->oArchive = $oArchive;
			$bean->oMethod = $oMethod;
			$bean->oRemarks = $oRemarks;
			
			array_push($output, $bean);
		}
   
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
		
	function findByOPhoneAndOEmail($phone, $email) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "SELECT * FROM order_made WHERE o_phone = ? AND o_email = ? ORDER BY o_date desc";
		$stmt = $mysqli->prepare($sql);
	
		$stmt->bind_param('ss', $phone, $email);
		$stmt->execute();
		
		$stmt->bind_result($oId,$mUsername,$oDate,$oFname,$oLname,$oEmail,$oPhone,$oLocation,$oStatus,$oItems,$oTotal,$oPaid,$oSerial,$oArchive,$oMethod,$oRemarks);
		 
		
		$output = array();
		
		while ($stmt->fetch()) {
			$bean = new OrderMadeBean();
						
			$bean->oId = $oId;
			$bean->mUsername = $mUsername;
			$bean->oDate = $oDate;
			$bean->oFname = $oFname;
			$bean->oLname = $oLname;
			$bean->oEmail = $oEmail;
			$bean->oPhone = $oPhone;
			$bean->oLocation = $oLocation;
			$bean->oStatus = $oStatus;
			$bean->oItems = $oItems;
			$bean->oTotal = $oTotal;
			$bean->oPaid = $oPaid;
			$bean->oSerial = $oSerial;
			$bean->oArchive = $oArchive;
			$bean->oMethod = $oMethod;
			$bean->oRemarks = $oRemarks;
			
			array_push($output, $bean);
		}
   
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
	
	function findByOId($id) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "SELECT * FROM order_made WHERE o_id = ?";
		$stmt = $mysqli->prepare($sql);
	
		$stmt->bind_param('s', $id);
		$stmt->execute();
		
		$stmt->bind_result($oId,$mUsername,$oDate,$oFname,$oLname,$oEmail,$oPhone,$oLocation,$oStatus,$oItems,$oTotal,$oPaid,$oSerial,$oArchive,$oMethod,$oRemarks);
		 
		
		$output = array();
		
		while ($stmt->fetch()) {
			$bean = new OrderMadeBean();
						
			$bean->oId = $oId;
			$bean->mUsername = $mUsername;
			$bean->oDate = $oDate;
			$bean->oFname = $oFname;
			$bean->oLname = $oLname;
			$bean->oEmail = $oEmail;
			$bean->oPhone = $oPhone;
			$bean->oLocation = $oLocation;
			$bean->oStatus = $oStatus;
			$bean->oItems = $oItems;
			$bean->oTotal = $oTotal;
			$bean->oPaid = $oPaid;
			$bean->oSerial = $oSerial;
			$bean->oArchive = $oArchive;
			$bean->oMethod = $oMethod;
			$bean->oRemarks = $oRemarks;
			
			array_push($output, $bean);
		}
   
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
	
	function findByOIdAndOPhoneAndOEmail($id, $phone, $email) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "SELECT * FROM order_made WHERE o_id = ? AND o_phone = ? AND o_email = ? ORDER BY o_date desc";
		$stmt = $mysqli->prepare($sql);
	
		$stmt->bind_param('sss', $id, $phone, $email);
		$stmt->execute();
		
		$stmt->bind_result($oId,$mUsername,$oDate,$oFname,$oLname,$oEmail,$oPhone,$oLocation,$oStatus,$oItems,$oTotal,$oPaid,$oSerial,$oArchive,$oMethod,$oRemarks);
		 
		
		$output = array();
		
		while ($stmt->fetch()) {
			$bean = new OrderMadeBean();
						
			$bean->oId = $oId;
			$bean->mUsername = $mUsername;
			$bean->oDate = $oDate;
			$bean->oFname = $oFname;
			$bean->oLname = $oLname;
			$bean->oEmail = $oEmail;
			$bean->oPhone = $oPhone;
			$bean->oLocation = $oLocation;
			$bean->oStatus = $oStatus;
			$bean->oItems = $oItems;
			$bean->oTotal = $oTotal;
			$bean->oPaid = $oPaid;
			$bean->oSerial = $oSerial;
			$bean->oArchive = $oArchive;
			$bean->oMethod = $oMethod;
			$bean->oRemarks = $oRemarks;
			
			array_push($output, $bean);
		}
   
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
	
	function findByOSerialAndOPhoneAndOEmail($serial, $phone, $email) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "SELECT * FROM order_made WHERE o_serial = ? AND o_phone = ? AND o_email = ? ORDER BY o_date desc";
		$stmt = $mysqli->prepare($sql);
	
		$stmt->bind_param('sss', $serial, $phone, $email);
		$stmt->execute();
		
		$stmt->bind_result($oId,$mUsername,$oDate,$oFname,$oLname,$oEmail,$oPhone,$oLocation,$oStatus,$oItems,$oTotal,$oPaid,$oSerial,$oArchive,$oMethod,$oRemarks);
		 
		
		$output = array();
		
		while ($stmt->fetch()) {
			$bean = new OrderMadeBean();
						
			$bean->oId = $oId;
			$bean->mUsername = $mUsername;
			$bean->oDate = $oDate;
			$bean->oFname = $oFname;
			$bean->oLname = $oLname;
			$bean->oEmail = $oEmail;
			$bean->oPhone = $oPhone;
			$bean->oLocation = $oLocation;
			$bean->oStatus = $oStatus;
			$bean->oItems = $oItems;
			$bean->oTotal = $oTotal;
			$bean->oPaid = $oPaid;
			$bean->oSerial = $oSerial;
			$bean->oArchive = $oArchive;
			$bean->oMethod = $oMethod;
			$bean->oRemarks = $oRemarks;
			
			array_push($output, $bean);
		}
   
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
	
	function findByMUsername($username) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "SELECT * FROM order_made WHERE m_username = ? ORDER BY o_date desc";
		$stmt = $mysqli->prepare($sql);
	
		$stmt->bind_param('s', $username);
		$stmt->execute();
		
		$stmt->bind_result($oId,$mUsername,$oDate,$oFname,$oLname,$oEmail,$oPhone,$oLocation,$oStatus,$oItems,$oTotal,$oPaid,$oSerial,$oArchive,$oMethod,$oRemarks);
		 
		
		$output = array();
		
		while ($stmt->fetch()) {
			$bean = new OrderMadeBean();
						
			$bean->oId = $oId;
			$bean->mUsername = $mUsername;
			$bean->oDate = $oDate;
			$bean->oFname = $oFname;
			$bean->oLname = $oLname;
			$bean->oEmail = $oEmail;
			$bean->oPhone = $oPhone;
			$bean->oLocation = $oLocation;
			$bean->oStatus = $oStatus;
			$bean->oItems = $oItems;
			$bean->oTotal = $oTotal;
			$bean->oPaid = $oPaid;
			$bean->oSerial = $oSerial;
			$bean->oArchive = $oArchive;
			$bean->oMethod = $oMethod;
			$bean->oRemarks = $oRemarks;
			
			array_push($output, $bean);
		}
   
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
	
	function getNextOId() {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "SELECT MAX(o_id) AS o_id FROM order_made";
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
		
		$sql = "INSERT INTO order_made (o_id, m_username, o_date, o_fname, o_lname, o_email, o_phone, o_location, o_status, o_items, o_total, o_paid, o_serial, o_archive, o_method, o_remarks) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $mysqli->prepare($sql);
				
		$entity->oArchive = 0;		
				
		$stmt->bind_param('dssssssssdddsdss', 
			$entity->oId,
			$entity->mUsername,
			$entity->oDate,
			$entity->oFname,
			$entity->oLname,
			$entity->oEmail,
			$entity->oPhone,
			$entity->oLocation,
			$entity->oStatus,
			$entity->oItems,
			$entity->oTotal,
			$entity->oPaid,
			$entity->oSerial,
			$entity->oArchive,
			$entity->oMethod,
			$entity->oRemarks
		);
		
		$stmt->execute();
		
		$stmt->free_result();
		$mysqli->close();
	}
	
	function update($entity) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "UPDATE order_made SET o_id = ?, m_username = ?, o_date = ?, o_fname = ?, o_lname = ?, o_email = ?, o_phone = ?, o_location = ?, o_status = ?, o_items = ?, o_total = ?, o_paid = ?, o_serial = ?, o_archive = ?, o_method = ?, o_remarks = ? WHERE o_id = ?";
		$stmt = $mysqli->prepare($sql);
		
		$entity->oArchive = 0;		
		
		$stmt->bind_param('dssssssssdddsdssd', 
			$entity->oId,
			$entity->mUsername,
			$entity->oDate,
			$entity->oFname,
			$entity->oLname,
			$entity->oEmail,
			$entity->oPhone,
			$entity->oLocation,
			$entity->oStatus,
			$entity->oItems,
			$entity->oTotal,
			$entity->oPaid,
			$entity->oSerial,
			$entity->oArchive,
			$entity->oMethod,
			$entity->oRemarks,
			$entity->oId
		);
		
		$stmt->execute();
		
		$stmt->free_result();
		$mysqli->close();
	}
	
	function updateForced($entity) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "UPDATE order_made SET o_id = ?, m_username = ?, o_date = ?, o_fname = ?, o_lname = ?, o_email = ?, o_phone = ?, o_location = ?, o_status = ?, o_items = ?, o_total = ?, o_paid = ?, o_serial = ?, o_archive = ?, o_method = ?, o_remarks = ? WHERE o_id = ?";
		$stmt = $mysqli->prepare($sql);
				
		$stmt->bind_param('dssssssssdddsdssd', 
			$entity->oId,
			$entity->mUsername,
			$entity->oDate,
			$entity->oFname,
			$entity->oLname,
			$entity->oEmail,
			$entity->oPhone,
			$entity->oLocation,
			$entity->oStatus,
			$entity->oItems,
			$entity->oTotal,
			$entity->oPaid,
			$entity->oSerial,
			$entity->oArchive,
			$entity->oMethod,
			$entity->oRemarks,
			$entity->oId
		);
		
		$stmt->execute();
		
		$stmt->free_result();
		$mysqli->close();
	}
}

?>