<?php
require_once('utility/RequestResultWrap.php');
require_once('utility/SessionLoader.php');
require_once('utility/PropertyLoader.php');
require_once('utility/TransactionHandler.php');
require_once('utility/CartLoader.php');

require_once('bean/InventoryBean.php');
require_once('bean/MemberBean.php');
require_once('bean/OrderMadeBean.php');
require_once('bean/OrderSubBean.php');
require_once('bean/ProductBean.php');

require_once('dao/InventoryDao.php');
require_once('dao/MemberDao.php');
require_once('dao/OrderMadeDao.php');
require_once('dao/OrderSubDao.php');
require_once('dao/ProductDao.php');

$ack = new RequestResultWrap();

$sessionLoader = new SessionLoader();
$cartLoader = new CartLoader($sessionLoader);

$propertyLoader = new PropertyLoader();
$inventoryDao = new InventoryDao($propertyLoader);
$memberDao = new MemberDao($propertyLoader);
$orderMadeDao = new OrderMadeDao($propertyLoader);
$orderSubDao = new OrderSubDao($propertyLoader);
$productDao = new ProductDao($propertyLoader);

$transactionHandler = new TransactionHandler($cartLoader, $inventoryDao, $orderMadeDao, $orderSubDao, $productDao, null);

//Check stock
if ($transactionHandler->checkAndRemoveSoldOutItem(false)) {
	$ack->status = "success";
}
else {
	$ack->status = "failure";
}


echo json_encode($ack);

?>