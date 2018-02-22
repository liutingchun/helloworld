<?php
require_once('utility/PropertyLoader.php');

require_once('utility/PropertyLoader.php');
require_once('utility/SessionLoader.php');
require_once('utility/AuthenticationLoader.php');

require_once('bean/MemberBean.php');
require_once('dao/MemberDao.php');
require_once('bean/ReceiptBean.php');
require_once('dao/ReceiptDao.php');

require_once('utility/RequestLoader.php');
require_once('utility/RequestResultWrap.php');

$sessionLoader = new SessionLoader();
$propertyLoader = new PropertyLoader();

$memberDao = new MemberDao($propertyLoader);

$authenticationLoader = new AuthenticationLoader($sessionLoader, $memberDao);

if ($authenticationLoader->isAdmin()) {	
	$requestLoader = new RequestLoader();
	$oId = $requestLoader -> getParameter('oId');

	$requestResultWrap = new RequestResultWrap();
	
	$receiptDao = new ReceiptDao($propertyLoader);

	$receiptList = $receiptDao->findByOId($oId);
	
	if (sizeof($receiptList) > 0) {			
		$requestResultWrap -> status = 'success';
		$requestResultWrap -> message = $receiptList;
	}
	else {
		$requestResultWrap -> status = 'failure';
		$requestResultWrap -> message = 'oym.order.detail.admin.msg.no.receipt';
	}
	
	$output = $requestResultWrap;
}
else {
	$output = 'Access denied.';
}

echo json_encode($output);

?>