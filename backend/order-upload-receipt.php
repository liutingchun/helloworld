<?php

require_once('utility/PropertyLoader.php');
require_once('utility/RequestResultWrap.php');

require_once('bean/OrderMadeBean.php');
require_once('dao/OrderMadeDao.php');

require_once('bean/ReceiptBean.php');
require_once('dao/ReceiptDao.php');

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$serial = $_POST['serial'];
$phone = $_POST['phone'];
$email = $_POST['email'];

$ack = new RequestResultWrap();

//Check Madatory field
if (
	is_null($serial) ||
	is_null($phone) ||
	is_null($email)
) {
	$ack->status = 'failure';
	$ack->message = 'Access denied.';
}

//Check File Exist
else if (empty($_FILES)) {
	$ack->status = 'failure';
	$ack->message = 'oym.action.msg.payment.failure.corrupted';
}

//File Size Check
else if ($_FILES["file"]["size"] > 4*1024*1024) {
	$ack->status = 'failure';
	$ack->message = 'oym.action.msg.payment.failure.too.large';
}

else {
	$propertyLoader = new PropertyLoader();
	$orderMadeDao = new OrderMadeDao($propertyLoader);
	
	$orderList = $orderMadeDao -> findByOSerialAndOPhoneAndOEmail($serial, $phone, $email);
	if (sizeof($orderList) > 0) {
		$orderBean = $orderList[0];
			
		if (strcmp($orderBean->oStatus, 'received') == 0) {
		
			$oid = $orderBean->oId;
			$randomKey = generateRandomString(16);
			$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
			$path = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'receipt' . DIRECTORY_SEPARATOR;
			
			$tempPath = $_FILES['file']['tmp_name'];
			$uploadPath = $path . $oid . '_' . $randomKey . '.' . $extension;
			//$uploadPath = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $_FILES['file']['name'];
			move_uploaded_file($tempPath, $uploadPath);
			
			$receiptDao = new ReceiptDao($propertyLoader);
			
			$receiptBean = new ReceiptBean();
			$receiptBean->oId = $oid;
			$receiptBean->rSub = $randomKey;
			$receiptBean->rExtension = $extension;
			
			$receiptDao->add($receiptBean);
			
			$orderBean->oStatus = 'paid';
			$orderBean->oMethod = 'deposit';
			
			$orderMadeDao->update($orderBean);
			
			$ack->status = 'success';
			$ack->message = $orderBean;
			//$ack->message = array("oid" => $oid, "randomKey" => $randomKey, "extension" => $extension, "path" => $path);


		}
		else {
			$ack->status = 'failure';
			$ack->message = 'oym.action.msg.payment.failure.already.paid';
		}
	}
	else {
		$requestResultWrap -> status = 'failure';
		$requestResultWrap -> message = 'Access denied.';
	}	
}

echo json_encode($ack);


?>