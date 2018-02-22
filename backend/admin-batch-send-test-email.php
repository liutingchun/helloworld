<?php
require_once('utility/PropertyLoader.php');
require_once('utility/SessionLoader.php');
require_once('utility/AuthenticationLoader.php');
require_once('utility/EmailHandler.php');

require_once('bean/MemberBean.php');
require_once('dao/MemberDao.php');

require_once('utility/RequestResultWrap.php');

require_once('bean/OrderMadeBean.php');

$sessionLoader = new SessionLoader();
$propertyLoader = new PropertyLoader();

$memberDao = new MemberDao($propertyLoader);

$authenticationLoader = new AuthenticationLoader($sessionLoader, $memberDao);

$requestResultWrap = new RequestResultWrap();

if ($authenticationLoader->isAdmin()) {	

	$emailHandler = new EmailHandler($propertyLoader);
	
	$emailHandler->sendEmail();
	
	$requestResultWrap->status = 'success';
}
else {
	$requestResultWrap->status = 'failure';
	$requestResultWrap->message = 'Access denied.';
}

echo json_encode($requestResultWrap);

?>
