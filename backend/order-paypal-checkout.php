<?php
require_once('utility/RequestResultWrap.php');

require_once('utility/PropertyLoader.php');
require_once('utility/RequestLoader.php');

require_once('bean/OrderMadeBean.php');
require_once('dao/OrderMadeDao.php');

require_once('bean/OrderSubBean.php');
require_once('dao/OrderSubDao.php');

$requestLoader = new RequestLoader();

$paypalResponse = $requestLoader -> getParameter('paypalResponse');
$serial = $requestLoader -> getParameter('serial');
$phone = $requestLoader -> getParameter('phone');
$email = $requestLoader -> getParameter('email');

$ack = new RequestResultWrap();

if (
	is_null($paypalResponse) ||
	is_null($serial) ||
	is_null($phone) ||
	is_null($email)
) {
	$ack->status = 'failure';
	$ack->message = 'Access denied.';
}
else {
	$propertyLoader = new PropertyLoader();
	$orderMadeDao = new OrderMadeDao($propertyLoader);
	
	$orderList = $orderMadeDao -> findByOSerialAndOPhoneAndOEmail($serial, $phone, $email);
	
	if (sizeof($orderList) > 0 && ($paypalResponse['state'] == 'created' || $paypalResponse['state'] == 'approved')) {
		$orderBean = $orderList[0];
		
		$orderBean->oStatus = 'paid';
		$orderBean->oMethod = 'paypal';
		$orderBean->oRemarks = $paypalResponse['transactions'][0]['amount']['total'];
		
		$orderMadeDao->update($orderBean);
		
		$ack->status = 'success';
		$ack->message = $orderBean;
	}
	else {
		$ack -> status = 'failure';
		$ack -> message = 'oym.action.msg.paypal.checkout.failure';
	}	
}

echo json_encode($ack);






?>