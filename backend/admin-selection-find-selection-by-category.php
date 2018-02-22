<?php
require_once('utility/PropertyLoader.php');
require_once('utility/SessionLoader.php');
require_once('utility/AuthenticationLoader.php');

require_once('bean/MemberBean.php');
require_once('dao/MemberDao.php');

require_once('bean/SelectionBean.php');
require_once('dao/SelectionDao.php');

require_once('utility/RequestLoader.php');

$sessionLoader = new SessionLoader();
$propertyLoader = new PropertyLoader();

$memberDao = new MemberDao($propertyLoader);

$authenticationLoader = new AuthenticationLoader($sessionLoader, $memberDao);

if ($authenticationLoader->isAdmin()) {	
	$requestLoader = new RequestLoader();
	$sCategory = $requestLoader -> getParameter('sCategory');

	$selectionDao = new SelectionDao($propertyLoader);
	
	$output = $selectionDao->findSValueBySCategory($sCategory);
	
}
else {
	$output = 'Access denied.';
}

echo json_encode($output);

?>
