<?php
	
require_once('utility/RequestLoader.php');	
	
$requestLoader = new RequestLoader();
$locale = $requestLoader -> getParameter('locale');
	
$path = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'message-bundle' . DIRECTORY_SEPARATOR;	
$file = $locale . '.json';
	
$str = file_get_contents($path . $file);
	
echo $str;	
	
?>