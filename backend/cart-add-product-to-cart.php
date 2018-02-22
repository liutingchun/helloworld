<?php

require_once('utility/RequestResultWrap.php');
require_once('utility/RequestLoader.php');
require_once('utility/SessionLoader.php');
require_once('utility/CartLoader.php');
require_once('utility/PropertyLoader.php');

require_once('bean/InventoryBean.php');
require_once('dao/InventoryDao.php');

require_once('utility/TransactionHandler.php');

$requestLoader = new RequestLoader();

$pid = $requestLoader -> getParameter('pid');
$productStatus = $requestLoader -> getParameter('productStatus');
$productColor = $requestLoader -> getParameter('productColor');
$productSize = $requestLoader -> getParameter('productSize');

$propertyLoader = new PropertyLoader();
$inventoryDao = new InventoryDao($propertyLoader);

$sessionLoader = new SessionLoader();
$cartLoader = new CartLoader($sessionLoader);

$transactionHandler = new TransactionHandler($cartLoader, $inventoryDao, null, null, null, null);

$result = new RequestResultWrap();

if ($transactionHandler -> checkEnoughStockToAdd($pid, $productStatus, $productColor, $productSize)) {
	$cartLoader -> addItem($pid, $productStatus, $productColor, $productSize);
	$result->status = 'success';
}
else {
	$result->status = 'failure';
	$result->message = 'oym.action.msg.cart.add.failure.no.stock';
}

echo json_encode($result);

?>