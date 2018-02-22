<?php
require_once('utility/PropertyLoader.php');
require_once('utility/SessionLoader.php');
require_once('utility/AuthenticationLoader.php');

require_once('bean/MemberBean.php');
require_once('dao/MemberDao.php');
require_once('bean/OrderMadeBean.php');
require_once('dao/OrderMadeDao.php');

require_once('utility/RequestResultWrap.php');

$sessionLoader = new SessionLoader();
$propertyLoader = new PropertyLoader();

$memberDao = new MemberDao($propertyLoader);

$authenticationLoader = new AuthenticationLoader($sessionLoader, $memberDao);

if ($authenticationLoader->isAdmin()) {	
	$requestResultWrap = new RequestResultWrap();
	
	$orderMadeDao = new OrderMadeDao($propertyLoader);

	$orderList = $orderMadeDao->findByOArchive(0);
	
	if (sizeof($orderList) > 0) {			
		$requestResultWrap -> status = 'success';
		$requestResultWrap -> message = $orderList;
	}
	else {
		$requestResultWrap -> status = 'failure';
		$requestResultWrap -> message = 'oym.action.msg.history.failure.no.record';
	}
	
	$output = $requestResultWrap;
}
else {
	$output = 'Access denied.';
}

echo json_encode($output);

?>