<?php
require_once('utility/RequestResultWrap.php');

require_once('utility/PropertyLoader.php');
require_once('utility/SessionLoader.php');
require_once('utility/RequestLoader.php');
require_once('utility/AuthenticationLoader.php');

require_once('bean/MemberBean.php');
require_once('dao/MemberDao.php');
require_once('bean/TagBean.php');
require_once('dao/TagDao.php');

$requestResultWrap = new RequestResultWrap();

$sessionLoader = new SessionLoader();
$propertyLoader = new PropertyLoader();

$memberDao = new MemberDao($propertyLoader);

$authenticationLoader = new AuthenticationLoader($sessionLoader, $memberDao);

if ($authenticationLoader->isAdmin()) {
	$requestLoader = new RequestLoader();

	$pid = $requestLoader -> getParameter('pid');
	$value = $requestLoader -> getParameter('value');
	
	$tagBean = new TagBean();
	
	$tagBean->pId = $pid;
	$tagBean->tName = $value;
	
	$tagDao = new TagDao($propertyLoader);

	$tagDao->add($tagBean);
		
	$requestResultWrap->status = 'success';
}
else {
	$requestResultWrap->status = 'failure';
	$requestResultWrap->message = 'Access denied.';
}

echo json_encode($requestResultWrap);

?>