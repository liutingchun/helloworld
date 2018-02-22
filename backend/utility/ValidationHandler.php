<?php

class ValidationHandler {	
	function username($input) {
		if (strlen($input) < 8)
			return false;
		
		if (strlen($input) > 16)
			return false;
		
		if (!ctype_alnum($input))
			return false;
		
		return true;
	}
	
	function password($input) {
		if (strlen($input) < 8)
			return false;
		
		if (strlen($input) > 16)
			return false;
				
		return true;
	}
	
	function phone($input) {
		if (strlen($input) < 8)
			return false;
		
		if (strlen($input) > 32)
			return false;
				
		return true;
	}
	
	function email($input) {		
		if (strlen($input) > 64)
			return false;
				
		return true;
	}
	
	function name($input) {		
		if (strlen($input) > 64)
			return false;
				
		return true;
	}
	
	function gender($input) {		
		if (strlen($input) > 10)
			return false;
				
		return true;
	}
	
	function address($input) {		
		if (strlen($input) > 200)
			return false;
				
		return true;
	}
}

?>