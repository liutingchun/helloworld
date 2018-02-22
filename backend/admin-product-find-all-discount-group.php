<?php
require_once('utility/PropertyLoader.php');
require_once('utility/SessionLoader.php');
require_once('utility/AuthenticationLoader.php');

require_once('bean/MemberBean.php');
require_once('dao/MemberDao.php');
require_once('bean/DiscountGroupBean.php');
require_once('dao/DiscountGroupDao.php');


$sessionLoader = new SessionLoader();
$propertyLoader = new PropertyLoader();

$memberDao = new MemberDao($propertyLoader);

$authenticationLoader = new AuthenticationLoader($sessionLoader, $memberDao);

if ($authenticationLoader->isAdmin()) {		
	$discountGroupDao = new DiscountGroupDao($propertyLoader);

	$output = $discountGroupDao->findAll();
}
else {
	$output = 'Access denied.';
}

echo json_encode($output);

?>