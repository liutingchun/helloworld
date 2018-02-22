<?php
require_once('utility/PropertyLoader.php');

require_once('utility/PropertyLoader.php');
require_once('utility/SessionLoader.php');
require_once('utility/AuthenticationLoader.php');
require_once('utility/RequestLoader.php');

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
	
	$requestLoader = new RequestLoader();
	
	$form = $requestLoader -> getParameter('entity');
	
	$orderMadeDao = new OrderMadeDao($propertyLoader);
	
	$list = $orderMadeDao->findByOId($form['oId']);
	$entity = $list[0];
	
	$entity -> mUsername = $form['mUsername'];
	$entity -> oDate = $form['oDate'];
	$entity -> oFname = $form['oFname'];
	$entity -> oLname = $form['oLname'];
	$entity -> oEmail = $form['oEmail'];
	$entity -> oPhone = $form['oPhone'];
	$entity -> oLocation = $form['oLocation'];
	$entity -> oStatus = $form['oStatus'];
	$entity -> oItems = $form['oItems'];
	$entity -> oTotal = $form['oTotal'];
	$entity -> oPaid = $form['oPaid'];
	$entity -> oMethod = $form['oMethod'];
	$entity -> oSerial = $form['oSerial'];
	$entity -> oArchive = $form['oArchive'];
		
	$orderMadeDao -> updateForced($entity);
		
	$requestResultWrap -> status = 'success';
	$requestResultWrap -> message = $entity;
	
	$output = $requestResultWrap;
}
else {
	$output = 'Access denied.';
}

echo json_encode($output);

?>