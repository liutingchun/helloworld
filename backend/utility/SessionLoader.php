<?php

class SessionLoader {
	function __construct() {
		if (!isset($_SESSION)) {
			session_start();
		}
	}
	
	function setProperty($key, $value) {
		$_SESSION[$key] = $value;
	}
	
	function getProperty($key) {
		return (isset($_SESSION[$key])) ? $_SESSION[$key] : null;
	}
	
	function clearProperty($key) {
		if (isset($_SESSION[$key]))
			unset($_SESSION[$key]);
	}
}

?>