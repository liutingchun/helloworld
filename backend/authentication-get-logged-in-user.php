<?php

require_once('utility/RequestResultWrap.php');

require_once('utility/PropertyLoader.php');
require_once('utility/SessionLoader.php');
require_once('utility/AuthenticationLoader.php');

require_once('bean/MemberBean.php');
require_once('dao/MemberDao.php');


$sessionLoader = new SessionLoader();
$propertyLoader = new PropertyLoader();
$memberDao = new MemberDao($propertyLoader);
$authenticationLoader = new AuthenticationLoader($sessionLoader, $memberDao);

$requestResultWrap = new RequestResultWrap();

$memberBean = $authenticationLoader -> getLoggedInUser();

if (isset($memberBean)) {
	$requestResultWrap -> status = 'success';
	$requestResultWrap -> message = $memberBean;
}
else {
	$requestResultWrap -> status = 'failure';
}

echo json_encode($requestResultWrap);

?>