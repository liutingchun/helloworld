<?php

class RequestLoader {	
	public $dataArray;

	function __construct() {
		$this->dataArray = json_decode(file_get_contents('php://input'), true);
	}

	function getParameter($key) {
		return $this->dataArray[$key];
	}
}

?>