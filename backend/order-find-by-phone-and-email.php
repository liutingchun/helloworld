<?php
require_once('utility/RequestResultWrap.php');

require_once('utility/PropertyLoader.php');
require_once('utility/RequestLoader.php');

require_once('bean/OrderMadeBean.php');
require_once('dao/OrderMadeDao.php');

$requestLoader = new RequestLoader();

$phone = $requestLoader -> getParameter('phone');
$email = $requestLoader -> getParameter('email');

$propertyLoader = new PropertyLoader();
$orderMadeDao = new OrderMadeDao($propertyLoader);

$requestResultWrap = new RequestResultWrap();

$orderList = $orderMadeDao -> findByOPhoneAndOEmail($phone, $email);

if (sizeof($orderList) > 0) {	
	for ($i = 0; $i < sizeof($orderList); $i++) {
		$orderList[$i]->oId = 0;
	}
	
	$requestResultWrap -> status = 'success';
	$requestResultWrap -> message = $orderList;
}
else {
	$requestResultWrap -> status = 'failure';
	$requestResultWrap -> message = 'oym.action.msg.history.failure.no.match';
}	

echo json_encode($requestResultWrap);


?>