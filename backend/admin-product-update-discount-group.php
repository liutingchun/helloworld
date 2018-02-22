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

	$form = $requestLoader -> getParameter('entity');

	$discountGroupBean = new DiscountGroupBean();
	
	$discountGroupBean->dId = $form['dId'];
	$discountGroupBean->dName = $form['dName'];
	$discountGroupBean->dType = $form['dType'];
	$discountGroupBean->dValue = $form['dValue'];
	$discountGroupBean->dQuantity = $form['dQuantity'];
	$discountGroupBean->dDescEn = $form['dDescEn'];
	$discountGroupBean->dDescZhTW = $form['dDescZhTW'];
	$discountGroupBean->dDescZhCN = $form['dDescZhCN'];
	
	$discountGroupDao = new DiscountGroupDao($propertyLoader);

	$discountGroupDao->update($discountGroupBean);
		
	$requestResultWrap->status = 'success';
	$requestResultWrap->message = $discountGroupBean;
}
else {
	$requestResultWrap->status = 'failure';
	$requestResultWrap->message = 'Access denied.';
}

echo json_encode($requestResultWrap);

?>