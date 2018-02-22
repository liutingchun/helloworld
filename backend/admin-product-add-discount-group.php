<?php
require_once('utility/RequestResultWrap.php');

require_once('utility/PropertyLoader.php');
require_once('utility/SessionLoader.php');
require_once('utility/RequestLoader.php');
require_once('utility/AuthenticationLoader.php');

require_once('bean/MemberBean.php');
require_once('dao/MemberDao.php');
require_once('bean/DiscountGroupBean.php');
require_once('dao/DiscountGroupDao.php');

$requestResultWrap = new RequestResultWrap();

$sessionLoader = new SessionLoader();
$propertyLoader = new PropertyLoader();

$memberDao = new MemberDao($propertyLoader);

$authenticationLoader = new AuthenticationLoader($sessionLoader, $memberDao);

if ($authenticationLoader->isAdmin()) {
	$requestLoader = new RequestLoader();

	$form = $requestLoader -> getParameter('discountGroupForm');

	$discountGroupBean = new DiscountGroupBean();
	
	$discountGroupBean->dName = $form['groupName'];
	$discountGroupBean->dType = $form['type'];
	$discountGroupBean->dValue = $form['discountValue'];
	$discountGroupBean->dQuantity = $form['triggerQuantity'];
	$discountGroupBean->dDescEn = $form['descriptionEn'];
	$discountGroupBean->dDescZhTW = $form['descriptionZhTW'];
	$discountGroupBean->dDescZhCN = $form['descriptionZhCN'];
	
	$discountGroupDao = new DiscountGroupDao($propertyLoader);

	$discountGroupDao->add($discountGroupBean);
		
	$requestResultWrap->status = 'success';
	$requestResultWrap->message = $discountGroupBean;
}
else {
	$requestResultWrap->status = 'failure';
	$requestResultWrap->message = 'Access denied.';
}

echo json_encode($requestResultWrap);

?>