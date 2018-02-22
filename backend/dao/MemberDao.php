<?php
class MemberDao {
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
		
		$sql = "INSERT INTO member (m_id, m_username, m_pw, m_level, m_email, m_phone, m_region, m_fname, m_lname, m_gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $mysqli->prepare($sql);
		
		$entity->mId = $this->getNextOId();
		$entity->mLevel = 'member';
		$entity->lName = '';
		
		$stmt->bind_param('dsssssssss', 
			$entity->mId,
			$entity->mUsername,
			$entity->mPw,
			$entity->mLevel,
			$entity->mEmail,
			$entity->mPhone,
			$entity->mRegion,
			$entity->mFname,
			$entity->lName,
			$entity->mGender
		);
		
		$stmt->execute();
		
		$stmt->free_result();
		$mysqli->close();
	}
	
	function getNextOId() {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "SELECT MAX(m_id) AS m_id FROM member";
		$stmt = $mysqli->prepare($sql);
		
		$stmt->execute();
		$stmt->bind_result($max);
		
		$stmt->fetch();
		$output = $max + 1;
		
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
	
	function findByUsername($username) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "SELECT * FROM member WHERE m_username = ?";
		$stmt = $mysqli->prepare($sql);
		
		$stmt->bind_param('s', $username);
		$stmt->execute();
		
		$stmt->bind_result($mId,$mUsername,$mPw,$mLevel,$mEmail,$mPhone,$mRegion,$mFname,$mLname,$mGender);
		//$num_of_rows = $result->num_rows;
		
		$output = array();
		
		while ($stmt->fetch()) {
			$bean = new MemberBean();
						
			$bean->mId = $mId;
			$bean->mUsername = $mUsername;
			$bean->mPw = $mPw;
			$bean->mLevel = $mLevel;
			$bean->mEmail = $mEmail;
			$bean->mPhone = $mPhone;
			$bean->mRegion = $mRegion;
			$bean->mFname = $mFname;
			$bean->mLname = $mLname;
			$bean->mGender = $mGender;
			
			array_push($output, $bean);
		}
   
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
	
	function findByMUsernameAndMPw($username, $password) {
		$mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		$mysqli->set_charset("utf8");
		
		$sql = "SELECT * FROM member WHERE m_username = ? AND m_pw = ?";
		$stmt = $mysqli->prepare($sql);
		
		$stmt->bind_param('ss', $username, $password);
		$stmt->execute();
		
		$stmt->bind_result($mId,$mUsername,$mPw,$mLevel,$mEmail,$mPhone,$mRegion,$mFname,$mLname,$mGender);
		//$num_of_rows = $result->num_rows;
		
		$output = array();
		
		while ($stmt->fetch()) {
			$bean = new MemberBean();
						
			$bean->mId = $mId;
			$bean->mUsername = $mUsername;
			$bean->mPw = $mPw;
			$bean->mLevel = $mLevel;
			$bean->mEmail = $mEmail;
			$bean->mPhone = $mPhone;
			$bean->mRegion = $mRegion;
			$bean->mFname = $mFname;
			$bean->mLname = $mLname;
			$bean->mGender = $mGender;
			
			array_push($output, $bean);
		}
   
		$stmt->free_result();
		$mysqli->close();
				
		return $output;
	}
}

?>