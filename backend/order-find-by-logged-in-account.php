<?php

require_once('utility/RequestResultWrap.php');

require_once('utility/PropertyLoader.php');
require_once('utility/SessionLoader.php');
require_once('utility/AuthenticationLoader.php');

require_once('bean/MemberBean.php');
require_once('dao/MemberDao.php');
require_once('bean/OrderMadeBean.php');
require_once('dao/OrderMadeDao.php');

$propertyLoader = new PropertyLoader();
$orderMadeDao = new OrderMadeDao($propertyLoader);
$memberDao = new MemberDao($propertyLoader);

$sessionLoader = new SessionLoader();
$authenticationLoader = new AuthenticationLoader($sessionLoader, $memberDao);

$requestResultWrap = new RequestResultWrap();

$memberBean = $authenticationLoader -> getLoggedInUser();

if (isset($memberBean)) {	
	$orderList = $orderMadeDao -> findByMUsername($memberBean -> mUsername);
	
	if (sizeof($orderList) > 0) {			
		for ($i = 0; $i < sizeof($orderList); $i++) {
			$orderList[$i]->oId = 0;
		}
		
		$requestResultWrap -> status = 'success';
		$requestResultWrap -> message = $orderList;
	}
	else {
		$requestResultWrap -> status = 'failure';
		$requestResultWrap -> message = 'oym.action.msg.history.failure.no.record';
	}
}
else {
	$requestResultWrap -> status = 'failure';
	$requestResultWrap -> message = 'oym.action.msg.history.failure.no.match';
}


echo json_encode($requestResultWrap);


?>