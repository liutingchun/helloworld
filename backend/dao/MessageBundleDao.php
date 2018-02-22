<?php
class MessageBundleDao {
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
		
		$sql = "SELECT * FROM message_bundle";
		$stmt = $mysqli->prepare($sql);
				
		$stmt->execute();
		
		/*$result = $stmt->get_result();
		$num_of_rows = $result->num_rows;*/
		
		$stmt->bind_result($messageKey,$en,$zhTW,$zhCN);
		
		$output = array();
		
		while ($stmt->fetch()) {
		//while ($row = $result->fetch_assoc()) {
			$bean = new MessageBundleBean();
									
			$bean->messageKey = $messageKey;
			$bean->en = $en;
			$bean->zhTW = $zhTW;
			$bean->zhCN = $zhCN;
			
			array_push($output, $bean);
		}
   
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
}

?>