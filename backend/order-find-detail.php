<?php
require_once('utility/RequestResultWrap.php');

require_once('utility/PropertyLoader.php');
require_once('utility/RequestLoader.php');

require_once('bean/OrderMadeBean.php');
require_once('dao/OrderMadeDao.php');

require_once('bean/OrderSubBean.php');
require_once('dao/OrderSubDao.php');

$requestLoader = new RequestLoader();

$serial = $requestLoader -> getParameter('serial');
$phone = $requestLoader -> getParameter('phone');
$email = $requestLoader -> getParameter('email');

$propertyLoader = new PropertyLoader();
$orderMadeDao = new OrderMadeDao($propertyLoader);

$requestResultWrap = new RequestResultWrap();

$orderList = $orderMadeDao -> findByOSerialAndOPhoneAndOEmail($serial, $phone, $email);

if (sizeof($orderList) > 0) {
	$orderSubDao = new OrderSubDao($propertyLoader);
	$orderSubList = $orderSubDao->findByOId($orderList[0]->oId);
	
	$output = array(
		"orderDetail" => $orderList[0],
		"orderSub" => $orderSubList
	);
	
	$requestResultWrap -> status = 'success';
	$requestResultWrap -> message = $output;
}
else {
	$requestResultWrap -> status = 'failure';
	$requestResultWrap -> message = 'oym.action.msg.history.failure.no.match';
}	

echo json_encode($requestResultWrap);


?>