<?php
require_once('utility/PropertyLoader.php');

require_once('utility/PropertyLoader.php');
require_once('utility/SessionLoader.php');
require_once('utility/AuthenticationLoader.php');
require_once('utility/TransactionHandler.php');
require_once('utility/RequestLoader.php');

require_once('bean/MemberBean.php');
require_once('dao/MemberDao.php');

require_once('bean/OrderMadeBean.php');
require_once('bean/InventoryBean.php');
require_once('bean/OrderSubBean.php');

require_once('dao/OrderMadeDao.php');
require_once('dao/InventoryDao.php');
require_once('dao/OrderSubDao.php');

require_once('utility/RequestResultWrap.php');

$sessionLoader = new SessionLoader();
$propertyLoader = new PropertyLoader();

$memberDao = new MemberDao($propertyLoader);

$authenticationLoader = new AuthenticationLoader($sessionLoader, $memberDao);

if ($authenticationLoader->isAdmin()) {	
	$requestResultWrap = new RequestResultWrap();
	
	$requestLoader = new RequestLoader();
	
	$oId = $requestLoader -> getParameter('oId');
	
	$inventoryDao = new InventoryDao($propertyLoader);
	$orderMadeDao = new OrderMadeDao($propertyLoader);
	$orderSubDao = new OrderSubDao($propertyLoader);
	
	$transactionHandler = new TransactionHandler(null, $inventoryDao, $orderMadeDao, $orderSubDao, null);
	$result = $transactionHandler->cancelOrderAndReleaseQuantity($oId);

	$requestResultWrap -> status = 'success';
	$requestResultWrap -> message = $result;
	
	$output = $requestResultWrap;
}
else {
	$output = 'Access denied.';
}

echo json_encode($output);

?>