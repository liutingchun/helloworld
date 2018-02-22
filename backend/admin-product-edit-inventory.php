<?php
require_once('utility/RequestResultWrap.php');

require_once('utility/PropertyLoader.php');
require_once('utility/SessionLoader.php');
require_once('utility/RequestLoader.php');
require_once('utility/AuthenticationLoader.php');

require_once('bean/MemberBean.php');
require_once('dao/MemberDao.php');
require_once('bean/InventoryBean.php');
require_once('dao/InventoryDao.php');

$requestResultWrap = new RequestResultWrap();

$sessionLoader = new SessionLoader();
$propertyLoader = new PropertyLoader();

$memberDao = new MemberDao($propertyLoader);

$authenticationLoader = new AuthenticationLoader($sessionLoader, $memberDao);

if ($authenticationLoader->isAdmin()) {
	$requestLoader = new RequestLoader();

	$pid = $requestLoader -> getParameter('pid');
	$productStatus = $requestLoader -> getParameter('productStatus');
	$productColor = $requestLoader -> getParameter('productColor');
	$productSize = $requestLoader -> getParameter('productSize');
	$productPrice = $requestLoader -> getParameter('productPrice');
	$quantity = $requestLoader -> getParameter('quantity');
	
	$inventoryBean = new InventoryBean();
	
	$inventoryBean->pid = $pid;
	$inventoryBean->productStatus = $productStatus;
	$inventoryBean->productColor = $productColor;
	$inventoryBean->productSize = $productSize;
	$inventoryBean->productPrice = $productPrice;
	$inventoryBean->quantity = $quantity;
	
	$inventoryDao = new InventoryDao($propertyLoader);

	$inventoryDao->update($inventoryBean);
		
	$requestResultWrap->status = 'success';
}
else {
	$requestResultWrap->status = 'failure';
	$requestResultWrap->message = 'Access denied.';
}

echo json_encode($requestResultWrap);

?>