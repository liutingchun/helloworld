<?php

require_once('utility/RequestResultWrap.php');

require_once('utility/PropertyLoader.php');
require_once('utility/SessionLoader.php');
require_once('utility/RequestLoader.php');
require_once('utility/AuthenticationLoader.php');

require_once('bean/MemberBean.php');
require_once('dao/MemberDao.php');

$requestLoader = new RequestLoader();
$sessionLoader = new SessionLoader();
$propertyLoader = new PropertyLoader();

$username = $requestLoader -> getParameter('username');
$password = $requestLoader -> getParameter('password');

$memberDao = new MemberDao($propertyLoader);

$authenticationLoader = new AuthenticationLoader($sessionLoader, $memberDao);



$requestResultWrap = new RequestResultWrap();

if ($authenticationLoader -> isLoggedIn()) {
	$requestResultWrap -> status = 'failure';
	$requestResultWrap -> message = 'oym.action.msg.login.failure.duplicate';
}
else {
	$memberBean = $authenticationLoader -> login($username, $password);

	if ($memberBean != null) {
		$requestResultWrap -> status = 'success';
		$requestResultWrap -> message = $memberBean;
	}
	else {
		$requestResultWrap -> status = 'failure';
		$requestResultWrap -> message = 'oym.action.msg.login.failure.incorrect.info';
	}
}

echo json_encode($requestResultWrap);

?>