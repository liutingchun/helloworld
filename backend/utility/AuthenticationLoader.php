<?php

class AuthenticationLoader {
	private $sessionLoader;
	private $memberDao;
	
	function __construct($sessionLoader, $memberDao) {
		$this->sessionLoader = $sessionLoader;
		$this->memberDao = $memberDao;
	}
	
	function isAdmin() {
		$memberBean = $this->sessionLoader -> getproperty('memberBean');
		
		return (isset($memberBean) && $memberBean->mLevel == 'admin');
	}
	
	function isLoggedIn() {
		$memberBean = $this->sessionLoader -> getproperty('memberBean');
		
		return isset($memberBean);
	}
	
	function getLoggedInUser() {
		$memberBean = $this->sessionLoader -> getproperty('memberBean');
		if (isset($memberBean)) {
			$memberBean->mPw = '';
			return $memberBean;
		}
		else {
			return null;
		}
	}
	
	function login($username, $password) {
		$memberList = $this->memberDao -> findByMUsernameAndMPw($username, $password);
				
		if (sizeof($memberList) > 0) {
			$memberBean = $memberList[0];
			$this->sessionLoader -> setProperty('memberBean', $memberBean);
			$memberBean->mPw = '';
	
			return $memberBean;
		}
		else {
			return null;
		}
	}
	
	function logout() {
		$this->sessionLoader -> clearProperty('memberBean');
	}
}

?>